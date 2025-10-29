<?php

if (!function_exists('getPaymentStatusVi')) {
    function getPaymentStatusVi($status)
    {
        $statusMap = [
            // Trạng thái hệ thống
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
            
            // Trạng thái VNPay
            'Total' => 'Đã thanh toán',
            'Unpaid' => 'Chưa thanh toán', 
            'Pending' => 'Đang xử lý',
            'Processing' => 'Đang xử lý',
            'Completed' => 'Hoàn thành',
            'Failed' => 'Thất bại',
            'Cancelled' => 'Đã hủy',
            'Refunded' => 'Đã hoàn tiền',
            'Expired' => 'Hết hạn',
            'Paid' => 'Đã thanh toán',
            'Success' => 'Thành công',
            'Error' => 'Lỗi',
        ];
        
        return $statusMap[$status] ?? $status;
    }
}

if (!function_exists('getStatusColor')) {
    function getStatusColor($status)
    {
        return match($status) {
            'completed', 'Total', 'Paid', 'Success' => 'bg-green-100 text-green-800',
            'pending', 'Unpaid', 'Pending', 'Processing' => 'bg-yellow-100 text-yellow-800',
            'shipping' => 'bg-blue-100 text-blue-800',
            'cancelled', 'Failed', 'Error', 'Cancelled' => 'bg-red-100 text-red-800',
            'Refunded' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}