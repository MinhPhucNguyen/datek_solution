<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class IncomeTrackerController extends Controller
{
    public function getIncome(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        Carbon::setLocale('vi');

        $orders = Order::whereBetween('order_date', [$startDate, $endDate])
            ->where('order_status', '!=', 'Đã hủy')
            ->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as month, SUM(order_total_price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $orders->pluck('month')->map(function ($month) {
            return ucfirst(Carbon::parse($month . '-01')->translatedFormat('F'));
        })->toArray();

        $income = $orders->pluck('total')->toArray();

        return response()->json([
            'labels' => $labels,
            'income' => $income,
        ]);
    }

    public function getOrderStats(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        $groupBy = $request->group_by ?? 'month';

        Carbon::setLocale('vi');

        $ordersQuery = Order::whereBetween('order_date', [$startDate, $endDate])
            ->where('order_status', '!=', 'Đã hủy');

        if ($groupBy === 'month') {
            $ordersQuery = $ordersQuery->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as period, COUNT(order_id) as total')
                ->groupBy('period')
                ->orderBy('period');
        } else {
            $ordersQuery = $ordersQuery->selectRaw('DATE_FORMAT(order_date, "%Y-%m-%d") as day, COUNT(order_id) as total')
                ->groupBy('day')
                ->orderBy('day');
        }

        $orders = $ordersQuery->get();

        $labels = $orders->pluck('period')->map(function ($period) use ($groupBy) {
            if ($groupBy === 'month') {
                return ucfirst(Carbon::parse($period . '-01')->translatedFormat('F'));
            } else {
                return Carbon::parse($period)->translatedFormat('d F Y');
            }
        })->toArray();

        $totalOrders = $orders->pluck('total')->toArray();

        return response()->json([
            'labels' => $labels,
            'total_orders' => $totalOrders,
        ]);
    }

    public function exportOrders(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        $orders = Order::with('user')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->get();

        $totalAmount = 0;
        $totalOrders = 0;
        $canceledOrders = 0;

        $csvData = [
            'Mã đơn hàng,Tên khách hàng,Email,Tổng tiền,Trạng thái,Ngày đặt hàng',
        ];

        foreach ($orders as $order) {
            $orderDate = Carbon::parse($order->order_date);

            $totalOrders++;

            if ($order->order_status === 'Đã hủy') {
                $canceledOrders++;
            } else {
                $totalAmount += $order->order_total_price;
            }

            $formattedTotal = number_format($order->order_total_price, 0, ',', '.') . ' ₫';

            $customerName = $order->user->lastname . ' ' . $order->user->firstname;
            $csvData[] = "{$order->order_id},{$customerName},{$order->user->email},{$formattedTotal},{$order->order_status}," . $orderDate->format('Y-m-d H:i:s');
        }

        $formattedTotalAmount = number_format($totalAmount, 0, ',', '.') . ' ₫';

        $csvData[] = '';
        $csvData[] = 'Tổng số đơn hàng,' . $totalOrders;
        $csvData[] = 'Doanh thu,' . $formattedTotalAmount;
        $csvData[] = 'Số đơn hàng đã hủy,' . $canceledOrders;

        return response(implode(PHP_EOL, $csvData))
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="orders.csv"');
    }
}
