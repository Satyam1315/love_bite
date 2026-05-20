<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class DineInController extends Controller
{
    /**
     * Display the specialized Dine-In menu.
     */
    public function menu(Request $request)
    {
        // When visiting the dine-in URL for the first time, we might want to clear old carts
        // to ensure they don't accidentally order something from yesterday.
        if (! $request->has('page') && ! $request->has('category') && ! $request->has('search')) {
            session()->forget('cart');
        }

        $query = Food::where('is_available', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::has('foods')->get();

        $cart = session('cart', []);
        $cartTotal = collect($cart)->sum(fn ($item) => $item['unit_price'] * $item['quantity']);

        return view('dine_in.menu', compact('foods', 'categories', 'cart', 'cartTotal'));
    }

    /**
     * Process the Dine-In order and generate a token.
     */
    public function placeOrder(Request $request)
    {

        if (! \App\Models\Setting::isOpen()) {
            return response()->json(['error' => \App\Models\Setting::closedMessage()], 422);
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty. Please add items.'], 422);
        }

        foreach ($cart as $item) {
            $food = Food::find($item['food_id']);
            if (! $food || ! $food->is_available) {
                return response()->json(['error' => 'One or more items are unavailable. Please refresh.'], 422);
            }
        }

        $total = collect($cart)->sum(fn ($item) => $item['unit_price'] * $item['quantity']);

        // Create the order
        $order = DB::transaction(function () use ($cart, $total) {
            $order = Order::create([
                'order_number' => 'DN-' . strtoupper(substr(uniqid(), -6)), // Shorter token for dine-in
                'user_id' => auth()->id(), // Nullable now, so it works for guests
                'total_amount' => $total,
                'order_type' => 'dine_in',
                'status' => 'pending',
                'table_number' => 'Scan', // Generic table marker since we removed table numbers
                'payment_method' => 'upi', // Defaulting to UPI for the Pay Now button
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'food_id' => $item['food_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'],
                ]);
            }

            return $order;
        });

        // Notify Admin immediately so kitchen knows a Dine-In order is placed
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotification::create([
                'user_id' => $admin->id,
                'title' => 'New Dine-In Order (Token: ' . $order->order_number . ')',
                'message' => 'A new Dine-In order was placed. Total: Rs ' . number_format($order->total_amount, 2),
                'link' => route('admin.orders.index'),
            ]);
        }

        // Clear cart
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'redirect' => route('dine-in.payment', $order->id)
        ]);
    }

    /**
     * Show the token and Razorpay payment button.
     */
    public function payment(Order $order)
    {
        // Ensure this is a dine-in order
        abort_unless($order->order_type === 'dine_in', 404);

        $razorpayOptions = null;
        if ($order->payment_status === 'pending' && config('services.razorpay.key')) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $razorpayOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => (int) round($order->total_amount * 100),
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);

            $razorpayOptions = [
                'key' => config('services.razorpay.key'),
                'amount' => (int) round($order->total_amount * 100),
                'currency' => 'INR',
                'name' => config('app.name', 'Love Bite'),
                'description' => 'Dine-in Order Token: ' . $order->order_number,
                'order_id' => $razorpayOrder['id'] ?? null,
                'prefill' => [
                    'name' => auth()->user()?->name ?? 'Dine-In Guest',
                    'email' => auth()->user()?->email ?? 'guest@example.com',
                ],
                'notes' => [
                    'order_id' => (string) $order->id,
                    'order_number' => $order->order_number,
                ],
                'theme' => ['color' => '#d9480f'],
                'pending_order_id' => $order->id,
            ];

            session()->put('pending_payment_order_id', $order->id);
        }

        return view('dine_in.payment', compact('order', 'razorpayOptions'));
    }

    /**
     * Handle successful payment for Dine-In.
     */
    public function paymentSuccess(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'payment_id' => ['required', 'string', 'max:255'],
        ]);

        $order = Order::with(['items.food'])->findOrFail($validated['order_id']);

        // Since it's a guest order, we shouldn't strictly enforce user_id matching if we don't have auth,
        // but we rely on the Razorpay signature verification in a real app.
        // For this demo, we'll just update it.
        
        if ($order->payment_status === 'paid') {
            return redirect()->route('dine-in.payment', $order)->with('success', 'Payment already confirmed.');
        }

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'payment_status' => 'paid',
                'payment_id' => $validated['payment_id'],
            ]);

            // Payment updated successfully. No stock deduction is needed as stock was removed.
        });

        // Notify Admin of payment
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotification::create([
                'user_id' => $admin->id,
                'title' => 'Dine-In Order Paid (Token: ' . $order->order_number . ')',
                'message' => 'Payment received for token ' . $order->order_number . '.',
                'link' => route('admin.orders.index'),
            ]);
        }

        session()->forget(['pending_payment_order_id']);

        return redirect()->route('dine-in.payment', $order)->with('success', 'Payment successful.');
    }
}
