<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property float $unit_price
 */
class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'unit_price'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function updateProductQuantity(int $newQuantity) {
        $this->update([
            'quantity' => $newQuantity
        ]);
    }
}
