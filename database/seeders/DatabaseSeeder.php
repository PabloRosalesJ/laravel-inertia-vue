<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $products = Product::factory()->count(50)->create();

        Client::factory()->count(20)->create()->each(function (Client $client) use ($products) {
            Order::factory()
                ->count(random_int(1,3))
                ->create(['client_id' => $client->id])
                ->each(function (Order $order) use ($products) {

                    $products->random(random_int(1,5))
                    ->each(fn($product) =>
                        OrderDetail::factory()
                        ->create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => random_int(1,3),
                            'unit_price' => $product->price,
                        ])
                    );
                });
        });
    }
}
