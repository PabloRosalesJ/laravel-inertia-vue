<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = Order::find($request->order)
            ->addProduct((int) $request->product);

        return $this->success($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderDetail $orders_detail)
    {

        $orders_detail->updateProductQuantity(
            $request->newQuantity,
        );

        return $this->success(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderDetail $orders_detail)
    {
        $orders_detail->delete();
        return $this->success(null, 204);
    }
}
