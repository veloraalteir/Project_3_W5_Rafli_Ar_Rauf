<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $products = Product::whereIn('id_barang', array_keys($cart))
            ->get()
            ->keyBy('id_barang');

        $items = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            if (!isset($products[$id])) {
                continue;
            }

            $product = $products[$id];

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->harga * $quantity,
            ];

            $total += $product->harga * $quantity;
        }

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => 'Produk dalam keranjang tidak tersedia.']);
        }

        return view('checkout.index', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alamat_pengiriman' => 'required|string',
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        try {
            $order = DB::transaction(function () use (
                $cart,
                $validated,
                $request
            ) {
                $products = Product::whereIn('id_barang', array_keys($cart))
                    ->orderBy('id_barang')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id_barang');

                if (count($cart) !== $products->count()) {
                    throw new RuntimeException(
                        'Ada produk dalam keranjang yang sudah tidak tersedia.'
                    );
                }

                $total = 0;

                foreach ($cart as $id => $quantity) {
                    $product = $products[$id] ?? null;

                    if (
                        !$product ||
                        !is_numeric($quantity) ||
                        (int) $quantity < 1 ||
                        (int) $quantity > $product->stok
                    ) {
                        throw new RuntimeException(
                            'Stok atau jumlah produk tidak valid. Periksa keranjang.'
                        );
                    }

                    $total += $product->harga * (int) $quantity;
                }

                $order = Order::create([
                    'id_order' => 'ORD' . Str::upper(Str::random(12)),
                    'id_user' => $request->user()->id_user,
                    'tanggal_order' => now(),
                    'total_harga' => $total,
                    'alamat_pengiriman' => $validated['alamat_pengiriman'],
                ]);

                foreach ($cart as $id => $quantity) {
                    $product = $products[$id];
                    $quantity = (int) $quantity;

                    OrderDetail::create([
                        'id_order' => $order->id_order,
                        'id_barang' => $product->id_barang,
                        'harga_satuan' => $product->harga,
                        'Jumlah_beli' => $quantity,
                    ]);

                    $product->decrement('stok', $quantity);
                }

                return $order;
            }, 3);
        } catch (RuntimeException $exception) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => $exception->getMessage()]);
        }

        $request->session()->forget('cart');

        return redirect()->route('orders.show', $order->id_order)
            ->with('success', 'Checkout berhasil. Pesanan berhasil dibuat.');
    }
}
