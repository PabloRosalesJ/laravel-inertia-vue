<?php
declare(strict_types=1);

namespace App\services\Order;
use App\Models\Order;
use DB;

final class OrdersFinder
{
    public function __construct(private Order $model) {}

    public function getPaginated($perPage = 10) {
        return Order::with('client:id,name,email')
        ->withCount('details')
        ->withSum('details as total_amount', DB::raw('unit_price * quantity'))
        ->orderBy('created_at','desc')
        ->paginate($perPage);
    }

    public function getById($id) {
        return Order::with([
            'client:id,name,phone,email,address',
            'details' => fn ($query) =>
                $query->select('id', 'order_id', 'product_id', 'quantity', 'unit_price')
                    ->with('product:id,name')
        ])->findOrFail($id);
    }
}
