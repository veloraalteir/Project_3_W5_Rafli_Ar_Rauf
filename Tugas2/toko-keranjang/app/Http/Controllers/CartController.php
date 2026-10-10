<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    private array $products = [
        1 => [
            'name' => 'Buku Tulis',
            'price' => 5000,
        ],
        2 => [
            'name' => 'Pulpen',
            'price' => 3000,
        ],
        3 => [
            'name' => 'Penggaris',
            'price' => 4000,
        ],
        4 => [
            'name' => 'Pensil 2B',
            'price' => 2500,
        ],
        5 => [
            'name' => 'Penghapus',
            'price' => 2000,
        ],
    ];

    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        return view('cart', [
            'products' => $this->products,
            'cart' => $cart,
            'total' => $this->calculateTotal($cart),
        ]);
    }

    public function add(Request $request, int $id)
    {
        if (!isset($this->products[$id])) {
            abort(404);
        }

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $this->products[$id]['name'],
                'price' => $this->products[$id]['price'],
                'quantity' => 1,
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$id])) {
            abort(404);
        }

        $cart[$id]['quantity'] = $validated['quantity'];

        $request->session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function remove(Request $request, int $id)
    {
        $cart = $request->session()->get('cart', []);

        unset($cart[$id]);

        $request->session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');

        return redirect('/cart')->with('success', 'Keranjang berhasil dikosongkan.');
    }

    private function calculateTotal(array $cart): int
    {
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}
