<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Toko Online</title>
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>
<body>
    <nav class="navbar">
        <h2>Toko Online</h2>
        <a href="{{ route('cart.index') }}">Kembali ke Keranjang</a>
    </nav>

    <main class="container">
        <h1>Checkout Pesanan</h1>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="card">
            <h2>Ringkasan Produk</h2>

            <div class="table-wrapper">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item['product']->nama_barang }}</td>

                                <td>
                                    Rp{{ number_format($item['product']->harga, 0, ',', '.') }}
                                </td>

                                <td>{{ $item['quantity'] }}</td>

                                <td>
                                    Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="total">
                Total: Rp{{ number_format($total, 0, ',', '.') }}
            </div>
        </section>

        <section class="card">
            <h2>Alamat Pengiriman</h2>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="alamat_pengiriman">
                        Alamat Pengiriman
                    </label>

                    <textarea
                        id="alamat_pengiriman"
                        name="alamat_pengiriman"
                        rows="4"
                        required
                    >{{ old('alamat_pengiriman', Auth::user()->alamat) }}</textarea>
                </div>

                <div class="actions">
                    <a href="{{ route('cart.index') }}"
                       class="button button-secondary">
                        Kembali ke Keranjang
                    </a>

                    <button type="submit" class="button">
                        Buat Pesanan
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
