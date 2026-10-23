<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cart' => ['present', 'array'],
            'cart.*' => ['integer', 'min:1', 'max:99'],
            'product_id' => ['required', 'string'],
        ]);

        $cart = $this->validateCart($data['cart']);
        $product = Product::findOrFail($data['product_id']);

        $currentQuantity = $cart[$product->id_barang] ?? 0;

        if ($currentQuantity + 1 > $product->stok) {
            return response()->json([
                'message' => 'Jumlah barang melebihi stok yang tersedia.',
            ], 422);
        }

        $cart[$product->id_barang] = $currentQuantity + 1;

        return response()->json($this->calculate($cart));
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cart' => ['present', 'array'],
            'cart.*' => ['integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->validateCart($data['cart']);

        return response()->json($this->calculate($cart));
    }

    private function validateCart(array $cart): array
    {
        $validatedCart = [];

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);

            if (! $product) {
                abort(422, 'Produk dalam keranjang tidak tersedia.');
            }

            if ((int) $quantity > $product->stok) {
                abort(422, 'Jumlah barang melebihi stok yang tersedia.');
            }

            $validatedCart[$product->id_barang] = (int) $quantity;
        }

        return $validatedCart;
    }

    private function calculate(array $cart): array
    {
        $items = [];
        $total = 0;

        $products = Product::whereIn('id_barang', array_keys($cart))
            ->get()
            ->keyBy('id_barang');

        foreach ($cart as $id => $quantity) {
            $product = $products->get($id);

            if (! $product) {
                continue;
            }

            $price = (int) $product->harga;
            $subtotal = $price * $quantity;

            $items[] = [
                'id' => (string) $product->id_barang,
                'name' => $product->nama_barang,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }
}