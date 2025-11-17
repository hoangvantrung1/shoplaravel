<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // Dữ liệu biểu đồ mặc định (mọi tháng)
        $chartData = $this->buildSalesDataset('monthly');

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
            'topProducts',
            'chartData'
        ));
    }

    public function salesData(Request $request)
    {
        $range = $request->get('range', 'monthly');
        $dataset = $this->buildSalesDataset($range);
        return response()->json($dataset);
    }

    public function exportSales(Request $request): StreamedResponse
    {
        $range = $request->get('range', 'monthly');
        $dataset = $this->buildSalesDataset($range);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bao_cao_doanh_thu_'.$range.'.csv"',
        ];

        $callback = function () use ($dataset) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Khoảng', 'Doanh thu (VND)', 'Số đơn']);
            foreach ($dataset['labels'] as $index => $label) {
                fputcsv($handle, [
                    $label,
                    $dataset['revenue'][$index],
                    $dataset['orders'][$index],
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function buildSalesDataset(string $range): array
    {
        switch ($range) {
            case 'daily':
                return $this->buildDailyDataset();
            case 'weekly':
                return $this->buildWeeklyDataset();
            case 'monthly':
            default:
                return $this->buildMonthlyDataset();
        }
    }

    private function buildDailyDataset(): array
    {
        $start = Carbon::today()->subDays(6);
        $end = Carbon::today();

        $raw = Order::select(
            DB::raw('DATE(created_at) as day'),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('day');

        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();
            $labels[] = $date->format('d/m');
            $revenue[] = isset($raw[$key]) ? (float)$raw[$key]->revenue : 0;
            $orders[] = isset($raw[$key]) ? (int)$raw[$key]->orders : 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }

    private function buildWeeklyDataset(): array
    {
        $startWeek = Carbon::now()->startOfWeek()->subWeeks(7);

        $raw = Order::select(
            DB::raw("YEARWEEK(created_at, 1) as week_key"),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
            ->where('created_at', '>=', $startWeek->copy()->startOfWeek())
            ->groupBy(DB::raw("YEARWEEK(created_at, 1)"))
            ->get()
            ->keyBy('week_key');

        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 0; $i < 8; $i++) {
            $weekStart = $startWeek->copy()->addWeeks($i);
            $weekKey = $weekStart->format('oW');
            $weekLabel = 'Tuần ' . $weekStart->format('W') . ' (' . $weekStart->format('d/m') . ')';

            $labels[] = $weekLabel;
            $revenue[] = isset($raw[$weekKey]) ? (float)$raw[$weekKey]->revenue : 0;
            $orders[] = isset($raw[$weekKey]) ? (int)$raw[$weekKey]->orders : 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }

    private function buildMonthlyDataset(): array
    {
        $raw = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = 'T' . $i;
            $revenue[] = isset($raw[$i]) ? (float)$raw[$i]->revenue : 0;
            $orders[] = isset($raw[$i]) ? (int)$raw[$i]->orders : 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }
}