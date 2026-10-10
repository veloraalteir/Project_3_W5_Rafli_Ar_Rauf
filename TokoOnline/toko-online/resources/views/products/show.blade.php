<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama_barang }} - Toko Online</title>
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('products.index') }}">← Kembali ke Katalog</a>

        <div class="nav-links">
            @auth
                <a href="{{ route('cart.index') }}">Keranjang</a>
                <a href="{{ route('orders.index') }}">Pesanan Saya</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </nav>

    <main class="container">
        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="message-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="product-detail">
            <div class="image-wrapper">
                <img
                    src="{{ asset('images/' . $product->gambar) }}"
                    alt="{{ $product->nama_barang }}"
                    class="product-image"
                >
            </div>

            <div class="product-info">
                <h1>{{ $product->nama_barang }}</h1>

                <p class="price">
                    Rp{{ number_format($product->harga, 0, ',', '.') }}
                </p>

                <p class="stock">
                    @if ($product->stok > 0)
                        Stok tersedia: {{ $product->stok }}
                    @else
                        Stok habis
                    @endif
                </p>

                <div class="description">
                    <h3>Deskripsi Produk</h3>
                    <p>{{ $product->deskripsi ?: 'Belum ada deskripsi produk.' }}</p>
                </div>

                <div class="purchase-actions">
                    @if ($product->stok > 0)
                        @auth
                            <form
                                action="{{ route('cart.add', $product->id_barang) }}"
                                method="POST"
                            >
                                @csrf
                                <button type="submit" class="button">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="button">
                                Login untuk Membeli
                            </a>
                        @endauth
                    @else
                        <button type="button" class="button button-disabled" disabled>
                            Stok Habis
                        </button>
                    @endif

                    @auth
                        <a href="{{ route('cart.index') }}" class="button button-secondary">
                            Lihat Keranjang
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>
</body>
</html>
