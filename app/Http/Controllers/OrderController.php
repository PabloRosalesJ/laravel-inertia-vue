<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

    public function show(Order $order)
    {
        return Inertia::render("Order/views/Show", compact('order'));
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
