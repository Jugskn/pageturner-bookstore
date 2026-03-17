<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $orders = $user->orders()->with('items.book')->latest('placed_at')->get();
        $reviews = $user->reviews()->with('book')->latest()->limit(5)->get();

        $recentBooks = Book::whereHas('orderItems', function ($q) use ($user) {
            $q->whereHas('order', fn ($o) => $o->where('user_id', $user->id));
        })->latest()->limit(5)->get();

        return view('dashboard.customer', [
            'user' => $user,
            'totalOrders' => $orders->count(),
            'recentOrders' => $orders->take(5),
            'orderStatuses' => $orders->groupBy('status')->map->count(),
            'recentBooks' => $recentBooks,
            'reviews' => $reviews,
        ]);
    }

    public function admin()
    {
        $totalUsers = User::where('role', 'customer')->count();
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();

        $recentOrders = Order::with('user')
            ->latest('placed_at')
            ->limit(10)
            ->get();

        $orderStatuses = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentReviews = Review::with(['user', 'book'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalBooks',
            'totalCategories',
            'totalOrders',
            'recentOrders',
            'orderStatuses',
            'recentReviews'
        ));
    }
}
