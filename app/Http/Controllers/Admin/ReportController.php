<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Order::query()->whereIn('status', ['paid','completed']);
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);

        $orders = $query->latest()->paginate(20);
        $totalRevenue = (clone $query)->sum('total');

        $topProducts = OrderItem::selectRaw('product_id, SUM(quantity) as qty, SUM(price * quantity) as revenue')
            ->when($from, fn($q)=>$q->whereDate('created_at','>=',$from))
            ->when($to, fn($q)=>$q->whereDate('created_at','<=',$to))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->with('product')
            ->limit(10)
            ->get();

        return view('admin.reports.sales', compact('orders','totalRevenue','topProducts','from','to'));
    }
}



