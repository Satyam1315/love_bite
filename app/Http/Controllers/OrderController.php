<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    public function checkout(): View|RedirectResponse
    {
        if (! \App\Models\Setting::isOpen()) {
            return redirect()->route('cart.index')->withErrors(['cart' => \App\Models\Setting::closedMessage()]);
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('home')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['unit_price'] * $item['quantity']);
        $couponCode = session('coupon_code');
        $discount = 0.0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                session()->forget('coupon_code');
                $couponCode = null;
            }
        }

        $total = max(0.0, $subtotal - $discount);
        
        $activeCoupons = Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();

        $razorpayPayment = session('razorpay_payment');
        $pendingPaymentOrderId = session('pending_payment_order_id');

        return view('orders.checkout', compact('cart', 'subtotal', 'couponCode', 'discount', 'total', 'activeCoupons', 'razorpayPayment', 'pendingPaymentOrderId'));
    }

    public function place(Request $request): RedirectResponse
    {
        if (! \App\Models\Setting::isOpen()) {
            return redirect()->route('cart.index')->withErrors(['cart' => \App\Models\Setting::closedMessage()]);
        }

        $validated = $request->validate([
            'order_type' => ['required', 'in:delivery,takeaway,dine_in'],
            'address' => ['required_if:order_type,delivery', 'nullable', 'string', 'min:10', 'max:500'],
            'table_number' => ['required_if:order_type,dine_in', 'nullable', 'string', 'max:50'],
            'pickup_time' => ['required_if:order_type,takeaway', 'nullable', 'date', 'after:now'],
            'payment_method' => ['required', 'in:cod,upi'],
        ], [
            'address.required_if' => 'Delivery address is required for delivery orders.',
            'table_number.required_if' => 'Table number is required for dine-in orders.',
            'pickup_time.required_if' => 'Pickup time is required for takeaway orders.',
            'pickup_time.after' => 'Pickup time must be in the future.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->withErrors(['cart' => 'Your cart is empty.']);
        }

        foreach ($cart as $item) {
            $food = Food::find($item['food_id']);

            if (! $food || ! $food->is_available) {
                return redirect()->route('cart.index')->withErrors([
                    'cart' => 'One or more items are unavailable. Please update your cart.',
                ]);
            }
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['unit_price'] * $item['quantity']);
        $couponCode = session('coupon_code');
        $discount = 0.0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                session()->forget('coupon_code');
                $couponCode = null;
            }
        }

        $total = max(0.0, $subtotal - $discount);

        if ($validated['payment_method'] === 'upi' && (! config('services.razorpay.key') || ! config('services.razorpay.secret'))) {
            return back()->withErrors([
                'payment_method' => 'Razorpay is not configured. Please add test keys in your environment file.',
            ]);
        }

        $order = DB::transaction(function () use ($validated, $cart, $request, $total, $couponCode, $discount) {
            $order = Order::create([
                'order_number' => 'LB-'.strtoupper(uniqid()),
                'user_id' => $request->user()->id,
                'total_amount' => $total,
                'coupon_code' => $couponCode,
                'discount_amount' => $discount,
                'order_type' => $validated['order_type'],
                'status' => 'pending',
                'address' => $validated['order_type'] === 'delivery' ? $validated['address'] : null,
                'table_number' => $validated['order_type'] === 'dine_in' ? $validated['table_number'] : null,
                'pickup_time' => $validated['order_type'] === 'takeaway' ? $validated['pickup_time'] : null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'payment_id' => null,
            ]);

            foreach ($cart as $item) {
                $food = Food::find($item['food_id']);

                if (! $food) {
                    continue;
                }

                $order->items()->create([
                    'food_id' => $food->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'],
                ]);
            }

            return $order;
        });

        if ($validated['payment_method'] === 'upi') {
            session()->flash('razorpay_payment', $this->buildRazorpayPaymentOptions($order, $request->user()));
            session()->flash('pending_payment_order_id', $order->id);

            return redirect()->route('orders.checkout')->with('success', 'Complete the payment to confirm your order.');
        }

        $this->finalizePlacedOrder($order, $request->user(), $cart, false);

        session()->forget('cart');
        session()->forget('coupon_code');

        return redirect()->route('orders.success', $order)->with('success', 'Order placed successfully.');
    }

    public function paymentSuccess(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'payment_id' => ['required', 'string', 'max:255'],
            'razorpay_order_id' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::with(['items.food'])->where('user_id', $request->user()->id)->findOrFail($validated['order_id']);

        if ($order->payment_method !== 'upi') {
            return back()->withErrors(['payment_method' => 'This order does not require Razorpay payment.']);
        }

        if ($order->payment_status === 'paid' && $order->payment_id === $validated['payment_id']) {
            return redirect()->route('orders.success', $order)->with('success', 'Payment already confirmed.');
        }

        DB::transaction(function () use ($order, $request, $validated) {
            $order->update([
                'payment_status' => 'paid',
                'payment_id' => $validated['payment_id'],
            ]);

            $cartItems = $order->items->map(fn ($item) => [
                'food_id' => $item->food_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
            ])->all();

            $this->finalizePlacedOrder($order, $request->user(), $cartItems, true);
        });

        session()->forget(['cart', 'pending_payment_order_id']);

        return redirect()->route('orders.success', $order)->with('success', 'Payment successful.');
    }

    public function success(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);

        return view('orders.success', compact('order'));
    }

    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->with('items.food')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if (! $order->canBeCancelledByUser()) {
            return back()->withErrors([
                'order' => 'This order cannot be cancelled at its current stage.',
            ]);
        }

        $order->update([
            'is_cancelled' => true,
            'cancelled_at' => now(),
            'cancel_reason' => 'Cancelled by customer',
        ]);

        UserNotification::create([
            'user_id' => $request->user()->id,
            'title' => 'Order Cancelled',
            'message' => 'Your order '.$order->order_number.' has been cancelled successfully.',
            'link' => route('orders.index'),
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotification::create([
                'user_id' => $admin->id,
                'title' => 'Order Cancelled By Customer',
                'message' => $request->user()->name.' cancelled order '.$order->order_number.'.',
                'link' => route('admin.orders.index'),
            ]);
        }

        return back()->with('success', 'Order cancelled successfully.');
    }

    private function buildRazorpayPaymentOptions(Order $order, User $user): array
    {
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) round($order->total_amount * 100),
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        return [
            'key' => config('services.razorpay.key'),
            'amount' => (int) round($order->total_amount * 100),
            'currency' => 'INR',
            'name' => config('app.name', 'Love Bite'),
            'description' => 'Payment for '.$order->order_number,
            'order_id' => $razorpayOrder['id'] ?? null,
            'prefill' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'notes' => [
                'order_id' => (string) $order->id,
                'order_number' => $order->order_number,
            ],
            'theme' => [
                'color' => '#d9480f',
            ],
            'pending_order_id' => $order->id,
        ];
    }

    private function finalizePlacedOrder(Order $order, User $user, array $cart, bool $paymentCompleted): void
    {
        foreach ($cart as $item) {
            $food = Food::find($item['food_id']);

            if (! $food) {
                continue;
            }

            $quantity = (int) $item['quantity'];
            $decrement = min((int) $food->stock, $quantity);

            if ($decrement > 0) {
                $food->decrement('stock', $decrement);
            }

            if ($food->fresh()->stock <= 0) {
                $food->update(['is_available' => false, 'stock' => 0]);
            }
        }

        UserNotification::create([
            'user_id' => $user->id,
            'title' => $paymentCompleted ? 'Payment Received' : 'Order Placed Successfully',
            'message' => $paymentCompleted
                ? 'Your payment for order '.$order->order_number.' was completed successfully.'
                : 'Your order '.$order->order_number.' has been placed and is pending confirmation.',
            'link' => route('orders.index'),
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotification::create([
                'user_id' => $admin->id,
                'title' => $paymentCompleted ? 'Order Payment Completed' : 'New Food Order Received',
                'message' => $paymentCompleted
                    ? $user->name.' completed payment for order '.$order->order_number.' for Rs '.number_format((float) $order->total_amount, 2).'.'
                    : $user->name.' placed order '.$order->order_number.' for Rs '.number_format((float) $order->total_amount, 2).'.',
                'link' => route('admin.orders.index'),
            ]);
        }
    }
}
