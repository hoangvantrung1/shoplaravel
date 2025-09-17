<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Tổng số lượng
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();

        // Tổng doanh thu - Sửa thành chữ thường 'completed'
        $totalRevenue = (float) Order::sum('total');

        $statusCounts = [
            'pending' => Order::whereRaw('LOWER(status) = ?', ['pending'])->count(),
            'processing' => Order::whereRaw('LOWER(status) = ?', ['processing'])->count(),
            'completed' => Order::whereRaw('LOWER(status) = ?', ['completed'])->count(),
            'cancelled' => Order::whereRaw('LOWER(status) = ?', ['cancelled'])->count(),
        ];

        // Doanh thu theo tháng
        $monthlyRevenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as revenue')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('revenue', 'month')
            ->toArray();

        // Tạo mảng đầy đủ 12 tháng
        $monthlyRevenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as revenue')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('revenue', 'month')
            ->toArray();
        $monthlyRevenueFull = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenueFull[] = isset($monthlyRevenue[$i]) ? (float) $monthlyRevenue[$i] : 0;
        }
        // Top 5 sản phẩm bán chạy
        $topProducts = OrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();    

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'statusCounts',
            'monthlyRevenueFull',
            'topProducts'
        ));
    }
}