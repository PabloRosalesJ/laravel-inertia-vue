<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\services\Order\OrdersFinder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function __construct(private OrdersFinder $finder) {}

    public function __invoke(Request $request) {
        $orders = $this->finder->getPaginated();

        return Inertia::render("Order/views/Index", compact('orders'));
    }

    public function index()
    {
        return $this->success($this->finder->getPaginated());
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($order)
    {
        $order = $this->finder->getById($order);
        $products = Product::select('id', 'name', 'price')->orderBy('name')->get();
        return Inertia::render("Order/views/Show", compact('order', 'products'));
    }

    public function showById($order)
    {
        $order = $this->finder->getById($order);
        return $this->success(compact('order'));
    }

    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return $this->success(null, 204);
    }
}
