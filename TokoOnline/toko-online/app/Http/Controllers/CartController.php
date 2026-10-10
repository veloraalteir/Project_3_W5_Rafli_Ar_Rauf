<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
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
            $subtotal = $product->harga * $quantity;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->stok < 1) {
            return back()->withErrors([
                'cart' => 'Produk sedang habis.',
            ]);
        }

        $cart = $request->session()->get('cart', []);
        $quantity = (int) ($cart[$id] ?? 0);

        if ($quantity + 1 > $product->stok) {
            return back()->withErrors([
                'cart' => 'Jumlah barang melebihi stok yang tersedia.',
            ]);
        }

        $cart[$id] = $quantity + 1;
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $quantity = (int) $validated['quantity'];

        if ($quantity > $product->stok) {
            return back()->withErrors([
                'quantity' => 'Jumlah barang melebihi stok yang tersedia.',
            ]);
        }

        $cart = $request->session()->get('cart', []);

        if (!array_key_exists($id, $cart)) {
            return redirect()->route('cart.index');
        }

        $cart[$id] = $quantity;
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    public function remove(Request $request, string $id)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$id]);

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
