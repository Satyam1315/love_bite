<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
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

        return view('cart.index', compact('cart', 'subtotal', 'couponCode', 'discount', 'total', 'activeCoupons'));
    }

    /**
     * Return cart data as JSON for AJAX sidebar updates.
     */
    public function data()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn ($item) => $item['unit_price'] * $item['quantity']);

        return response()->json([
            'cart' => array_values($cart),
            'total' => $total,
            'count' => count($cart),
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'food_id' => ['required', 'exists:foods,id'],
            'portion' => ['required', 'in:half,full'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $food = Food::findOrFail($validated['food_id']);

        if (! $food->is_available) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'This item is currently unavailable.'], 422);
            }
            return back()->withErrors(['food' => 'This item is currently unavailable.']);
        }

        $priceField = $validated['portion'] === 'half' ? 'price_half' : 'price_full';
        $unitPrice = (float) ($food->{$priceField} ?? 0);

        if ($unitPrice <= 0) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Selected portion is not available.'], 422);
            }
            return back()->withErrors(['portion' => 'Selected portion is not available for this item.']);
        }

        $cart = session('cart', []);
        $key = $food->id.'_'.$validated['portion'];

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += (int) $validated['quantity'];
        } else {
            $cart[$key] = [
                'key' => $key,
                'food_id' => $food->id,
                'name' => $food->name,
                'type' => $food->type,
                'portion' => $validated['portion'],
                'unit_price' => $unitPrice,
                'quantity' => (int) $validated['quantity'],
                'image' => $food->image,
            ];
        }

        // Stock column dropped, skipping quantity stock limit check

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return $this->cartJson('Item added to cart.');
        }
        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $validated['quantity'];
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            return $this->cartJson('Cart updated.');
        }
        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, string $key)
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return $this->cartJson('Item removed.');
        }
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = strtoupper($validated['code']);
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->withErrors(['coupon' => 'Invalid coupon code.']);
        }

        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn ($item) => $item['unit_price'] * $item['quantity']);

        if (!$coupon->is_active || ($coupon->expires_at && $coupon->expires_at->isPast())) {
            return back()->withErrors(['coupon' => 'This coupon has expired or is no longer active.']);
        }

        if ($subtotal < $coupon->min_order_amount) {
            return back()->withErrors(['coupon' => 'Minimum order amount to apply this coupon is ₹' . number_format($coupon->min_order_amount, 2)]);
        }

        session(['coupon_code' => $coupon->code]);

        return back()->with('success', 'Coupon "' . $coupon->code . '" applied successfully!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');
        return back()->with('success', 'Coupon removed.');
    }

    /**
     * Helper to return cart JSON response.
     */
    private function cartJson(string $message)
    {
        $cart = session('cart', []);
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

        $total = $subtotal - $discount;

        return response()->json([
            'success' => $message,
            'cart' => array_values($cart),
            'subtotal' => $subtotal,
            'coupon_code' => $couponCode,
            'discount' => $discount,
            'total' => $total,
            'count' => count($cart),
        ]);
    }
}
