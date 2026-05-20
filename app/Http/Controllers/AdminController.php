<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use App\Models\Setting;
use App\Models\UserNotification;
use App\Models\Coupon;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalOrders = Order::count();
        $revenue = Order::sum('total_amount');
        $recentOrders = Order::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('totalOrders', 'revenue', 'recentOrders'));
    }

    public function categories(): View
    {
        $categories = Category::withCount('foods')->latest()->get();

        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ]);

        Category::create($validated);

        return back()->with('success', 'Category created.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,'.$category->id],
        ]);

        $category->update($validated);

        return back()->with('success', 'Category updated.');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function foods(): View
    {
        $foods = Food::with('category')->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('admin.foods', compact('foods', 'categories'));
    }

    public function storeFood(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price_half'  => ['nullable', 'numeric', 'min:0'],
            'price_full'  => ['nullable', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'type'        => ['required', 'in:veg,non-veg'],
            'is_available'=> ['nullable', 'boolean'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('foods', 'public');
        }

        Food::create($validated);

        return back()->with('success', 'Food item created.');
    }

    public function updateFood(Request $request, Food $food)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price_half'  => ['nullable', 'numeric', 'min:0'],
            'price_full'  => ['nullable', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'type'        => ['required', 'in:veg,non-veg'],
            'is_available'=> ['nullable', 'boolean'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('foods', 'public');
        }

        $food->update($validated);

        return back()->with('success', 'Food item updated.');
    }

    public function deleteFood(Food $food)
    {
        $food->delete();

        return back()->with('success', 'Food item deleted.');
    }

    public function orders(): View
    {
        $statuses = ['pending', 'accepted', 'preparing', 'ready', 'delivery_in_progress', 'delivered'];
        $orders = Order::with(['user', 'items.food'])->latest()->paginate(15);

        return view('admin.orders', compact('orders', 'statuses'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if ($order->is_cancelled) {
            return back()->withErrors(['order' => 'Cancelled orders cannot be updated.']);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,accepted,preparing,ready,delivery_in_progress,delivered'],
        ]);

        $order->update(['status' => $validated['status']]);

        UserNotification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Status Updated',
            'message' => 'Your order '.$order->order_number.' is now '.str_replace('_', ' ', $validated['status']).'.',
            'link' => route('orders.index'),
        ]);

        return back()->with('success', 'Order status updated.');
    }

    public function revenueAnalytics(): View
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dailyRevenueRows = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as day, SUM(total_amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $dailyRevenueMap = $dailyRevenueRows->pluck('total', 'day');
        $dailyLabels = [];
        $dailyValues = [];

        foreach (CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay()) as $date) {
            $key = $date->format('Y-m-d');
            $dailyLabels[] = $date->format('d M');
            $dailyValues[] = (float) ($dailyRevenueMap[$key] ?? 0);
        }

        $paymentBreakdown = Order::query()
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->get();

        $statusBreakdown = Order::query()
            ->selectRaw('status, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $totalRevenue = (float) Order::sum('total_amount');
        $totalOrders = (int) Order::count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('admin.revenue', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'dailyLabels' => $dailyLabels,
            'dailyValues' => $dailyValues,
            'paymentLabels' => $paymentBreakdown->pluck('payment_method')->map(fn ($method) => strtoupper((string) $method))->values(),
            'paymentCounts' => $paymentBreakdown->pluck('count')->map(fn ($count) => (int) $count)->values(),
            'paymentTotals' => $paymentBreakdown->pluck('total')->map(fn ($total) => (float) $total)->values(),
            'statusBreakdown'  => $statusBreakdown,
        ]);
    }

    public function settings(): View
    {
        $settings = [
            'opening_time'      => Setting::get('opening_time', '13:00'),
            'closing_time'      => Setting::get('closing_time', '22:00'),
            'is_manually_closed'=> (bool) Setting::get('is_manually_closed', false),
            'is_force_opened'   => (bool) Setting::get('is_force_opened', false),
            'closed_message'    => Setting::get('closed_message', 'We are currently closed. We open at {opening_time} and close at {closing_time}.'),
        ];

        $isOpen = Setting::isOpen();

        return view('admin.settings', compact('settings', 'isOpen'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'opening_time'       => ['required', 'date_format:H:i'],
            'closing_time'       => ['required', 'date_format:H:i', 'after:opening_time'],
            'is_manually_closed' => ['nullable', 'boolean'],
            'is_force_opened'    => ['nullable', 'boolean'],
            'closed_message'     => ['required', 'string', 'max:300'],
        ], [
            'closing_time.after' => 'Closing time must be after the opening time.',
        ]);

        Setting::set('opening_time',       $validated['opening_time']);
        Setting::set('closing_time',       $validated['closing_time']);
        Setting::set('is_manually_closed', (bool) ($validated['is_manually_closed'] ?? false));
        Setting::set('is_force_opened',    (bool) ($validated['is_force_opened'] ?? false));
        Setting::set('closed_message',     $validated['closed_message']);

        return back()->with('success', 'Restaurant hours updated successfully.');
    }

    public function coupons(): View
    {
        $coupons = Coupon::orderBy('id', 'desc')->paginate(15);
        return view('admin.coupons', compact('coupons'));
    }

    public function storeCoupon(Request $request)
    {
        $validated = $request->validate([
            'code'             => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type'             => ['required', 'in:fixed,percent'],
            'value'            => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['required', 'numeric', 'min:0'],
            'expires_at'       => ['nullable', 'date', 'after:today'],
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = true;

        Coupon::create($validated);

        return back()->with('success', 'Coupon code created successfully.');
    }

    public function deleteCoupon(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon code deleted successfully.');
    }

    public function toggleCoupon(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', 'Coupon status updated.');
    }
}
