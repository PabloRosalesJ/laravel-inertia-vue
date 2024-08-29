<?php
declare(strict_types=1);

namespace App\services\Order;
use App\Models\Order;

final class OrdersFinder
{
    public function __construct(private Order $model) {}

    public function getPaginated($perPage = 10) {
        return Order::with('client:id,name,email')
        ->withCount('details')
        ->withSum('details', 'unit_price')
        ->orderBy('created_at','desc')
        ->paginate($perPage);
    }
}
