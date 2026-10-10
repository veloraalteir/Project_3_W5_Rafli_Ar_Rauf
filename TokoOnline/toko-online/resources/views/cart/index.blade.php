<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Toko Online</title>
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
</head>
<body>
    <nav class="navbar">
        <h2>Keranjang Belanja</h2>
        <div>
            <a href="{{ route('products.index') }}">Katalog Produk</a>
        </div>
    </nav>

    <main class="container">
        <h1>Keranjang Belanja</h1>

        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="message-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (count($items) > 0)
            <div class="cart-wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item['product']->nama_barang }}</td>
                                <td>
                                    Rp{{ number_format($item['product']->harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    <form
                                        action="{{ route('cart.update', $item['product']->id_barang) }}"
                                        method="POST"
                                        class="quantity-form"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="number"
                                            name="quantity"
                                            min="1"
                                            max="{{ $item['product']->stok }}"
                                            value="{{ $item['quantity'] }}"
                                            required
                                        >

                                        <button type="submit" class="button">
                                            Ubah
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>
                                <td>
                                    <form
                                        action="{{ route('cart.remove', $item['product']->id_barang) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="button remove-button"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary">
                <a href="{{ route('products.index') }}" class="button">
                    Lanjut Belanja
                </a>

                <div class="total">
                    Total: Rp{{ number_format($total, 0, ',', '.') }}
                </div>

                <a href="{{ route('checkout.index') }}" class="button">
                    Checkout
                </a>
            </div>
        @else
            <div class="empty">
                <h3>Keranjang masih kosong</h3>
                <p>Silakan pilih produk dari katalog untuk mulai berbelanja.</p>

                <a href="{{ route('products.index') }}" class="button">
                    Lihat Katalog
                </a>
            </div>
        @endif
    </main>
</body>
</html>
