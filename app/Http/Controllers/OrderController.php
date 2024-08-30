<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateRequest;
use App\Models\Client;
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
        $clients = Client::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'name', 'price')->orderBy('name')->get();
        return Inertia::render("Order/views/New", compact('clients', 'products'));
    }

    public function store(CreateRequest $request)
    {
        Order::newOrder(
            $request->validated('client'),
            $request->validated('productsList'),
        );

        return to_route('dashboard');
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
