<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('foods')) {
            return view('home', [
                'categories'       => collect(),
                'popularItems'     => collect(),
                'latestItems'      => collect(),
                'recommendedItems' => collect(),
                'todaySpecials'    => collect(),
            ]);
        }

        $categories = Category::orderBy('name')->get();
        $popularItems = Food::with('category')
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->latest()
            ->take(6)
            ->get();

        $latestItems = Food::with('category')
            ->latest()
            ->take(6)
            ->get();

        $recommendedItems = Food::with('category')
            ->inRandomOrder()
            ->take(6)
            ->get();

        $todaySpecials = Food::with('category')
            ->latest()
            ->take(3)
            ->get();

        return view('home', [
            'categories'       => $categories,
            'popularItems'     => $popularItems,
            'latestItems'      => $latestItems,
            'recommendedItems' => $recommendedItems,
            'todaySpecials'    => $todaySpecials,
        ]);
    }

    public function menu(Request $request)
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('foods')) {
            return view('menu', [
                'categories' => collect(),
                'foods' => new LengthAwarePaginator([], 0, 12),
            ]);
        }

        $categories = Category::orderBy('name')->get();

        $foodsQuery = Food::with('category')->latest();

        if ($request->filled('search')) {
            $foodsQuery->where('name', 'like', '%'.$request->string('search').'%');
        }

        if ($request->filled('category')) {
            $foodsQuery->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->string('category'));
            });
        }

        if (in_array($request->type, ['veg', 'non-veg'], true)) {
            $foodsQuery->where('type', $request->type);
        }

        $foods = $foodsQuery->paginate(12)->withQueryString();

        $cart = session('cart', []);
        $cartTotal = collect($cart)->sum(fn ($item) => $item['unit_price'] * $item['quantity']);

        return view('menu', [
            'categories' => $categories,
            'foods' => $foods,
            'cart' => $cart,
            'cartTotal' => $cartTotal,
        ]);
    }
}
