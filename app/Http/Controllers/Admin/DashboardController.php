<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Tổng số đơn
        $totalOrders = Order::count();

        // Tổng doanh thu
        $totalRevenue = Order::sum('total');

        // Thống kê trạng thái đơn
        $statusCounts = [
            'pending' => Order::where('status','pending')->count(),
            'processing' => Order::where('status','processing')->count(),
            'completed' => Order::where('status','completed')->count(),
            'cancelled' => Order::where('status','cancelled')->count(),
        ];

        // Doanh thu theo tháng
        $monthlyRevenue = Order::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("SUM(total) as revenue")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('revenue','month')
        ->toArray();

        // Đảm bảo 12 tháng có dữ liệu
        $monthlyRevenueFull = [];
        for($m=1; $m<=12; $m++){
            $monthlyRevenueFull[$m] = $monthlyRevenue[$m] ?? 0;
        }

        // Top 5 sản phẩm bán chạy
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product')
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'statusCounts',
            'monthlyRevenueFull',
            'topProducts'
        ));
    }
}
