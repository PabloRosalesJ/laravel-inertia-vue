<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $client_id
 * @property string $number
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id', 'number'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected function casts(): array {
        return [
            'created_at' => 'datetime:Y-m-d',
        ];
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function details() {
        return $this->hasMany(OrderDetail::class);
    }

    public static function newOrder(int $client, array $products) {
        try {
            DB::beginTransaction();

            $order = self::create([
                'client_id' => $client,
                'number' => self::getNewNumber()
            ]);

            $prices = Product::whereIn('id', collect($products)->select('product'))->select('id', 'price')->get();

            $details = array_map(fn($p) => new OrderDetail([
                'product_id' => $p['product'],
                'quantity' => $p['quantity'],
                'unit_price' => $prices->where('id', $p['product'])->first()->price,
            ]), $products);

            $order->details()->saveMany($details);

            DB::commit();

            return $order;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
        }
    }

    public function addProduct(int $product) {

        $product = Product::find($product);

        if ($this->details()->where('product_id', $product->id)->exists()) {
            $det = $this->details()->where('product_id', $product->id)->first();
            $det->increment('quantity');
            return $det;
        }

        return $this->details()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
        ]);
    }

    public static function getNewNumber() {
        return Str::upper(Str::random(10));
    }
}
