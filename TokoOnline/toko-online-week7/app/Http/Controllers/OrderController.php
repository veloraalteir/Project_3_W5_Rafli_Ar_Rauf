<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('id_user', $request->user()->id_user)
            ->orderByDesc('tanggal_order')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, string $id)
    {
        $order = Order::where('id_order', $id)
            ->where('id_user', $request->user()->id_user)
            ->with('details.product')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}