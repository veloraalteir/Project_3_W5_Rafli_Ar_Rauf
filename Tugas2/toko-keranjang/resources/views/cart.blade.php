<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Keranjang</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <div class="container">
        <h1>Toko Alat Tulis</h1>
        <p class="subtitle">Pilih produk dan masukkan ke keranjang</p>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <h2>Daftar Produk</h2>

        @foreach ($products as $id => $product)
            <div class="product">
                <div>
                    <strong>{{ $product['name'] }}</strong>
                    <p>Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                </div>

                <form action="{{ url('/cart/add/' . $id) }}" method="POST">
                    @csrf
                    <button type="submit">Tambah</button>
                </form>
            </div>
        @endforeach

        <h2>Keranjang Belanja</h2>

        @forelse ($cart as $id => $item)
            <div class="cart-item">
                <strong>{{ $item['name'] }}</strong>
                <p>Harga: Rp{{ number_format($item['price'], 0, ',', '.') }}</p>

                <form action="{{ url('/cart/update/' . $id) }}" method="POST">
                    @csrf
                    <label for="quantity-{{ $id }}">Jumlah</label>
                    <input
                        type="number"
                        id="quantity-{{ $id }}"
                        name="quantity"
                        value="{{ $item['quantity'] }}"
                        min="1"
                        max="99"
                        required
                    >
                    <button type="submit">Perbarui</button>
                </form>

                <p>Subtotal: Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>

                <form action="{{ url('/cart/remove/' . $id) }}" method="POST">
                    @csrf
                    <button type="submit" class="danger">Hapus</button>
                </form>
            </div>
        @empty
            <p>Keranjang masih kosong.</p>
        @endforelse

        <div class="total">
            Total Belanja: Rp{{ number_format($total, 0, ',', '.') }}
        </div>

        @if (count($cart) > 0)
            <form action="{{ url('/cart/clear') }}" method="POST">
                @csrf
                <button type="submit" class="danger">Kosongkan Keranjang</button>
            </form>
        @endif
    </div>
</body>
</html>
