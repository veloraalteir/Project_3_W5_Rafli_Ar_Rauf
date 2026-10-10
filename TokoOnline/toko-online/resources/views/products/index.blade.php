<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Toko Online</title>
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
</head>
<body>
    <nav class="navbar">
        <h2>Toko Online</h2>

        <div class="nav-links">
            @auth
                <span>Halo, {{ Auth::user()->nama_lengkap }}</span>
                <a href="{{ route('cart.index') }}">Keranjang</a>
                <a href="{{ route('orders.index') }}">Pesanan Saya</a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-button">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar</a>
                <a href="{{ route('cart.index') }}">Keranjang</a>
            @endauth
        </div>
    </nav>

    <main class="container">
        <section class="heading">
            <h1>Katalog Produk</h1>
            <p>Temukan berbagai kebutuhan alat tulis untukmu.</p>
        </section>

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

        @if ($products->isNotEmpty())
            <div class="product-grid">
                @foreach ($products as $product)
                    <article class="product-card">
                        <img
                            src="{{ asset('images/' . $product->gambar) }}"
                            alt="{{ $product->nama_barang }}"
                        >

                        <div class="product-info">
                            <h3>{{ $product->nama_barang }}</h3>

                            <p class="price">
                                Rp{{ number_format($product->harga, 0, ',', '.') }}
                            </p>

                            <p class="stock">
                                @if ($product->stok > 0)
                                    Stok: {{ $product->stok }}
                                @else
                                    Stok habis
                                @endif
                            </p>

                            <a
                                href="{{ route('products.show', $product->id_barang) }}"
                                class="detail-link"
                            >
                                Lihat Detail
                            </a>

                            @if ($product->stok > 0)
                                @auth
                                    <form
                                        action="{{ route('cart.add', $product->id_barang) }}"
                                        method="POST"
                                        class="add-cart-form"
                                    >
                                        @csrf
                                        <button type="submit" class="add-cart-button">
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="add-cart-button">
                                        Login untuk Membeli
                                    </a>
                                @endauth
                            @else
                                <button type="button" class="add-cart-button disabled" disabled>
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty">
                <p>Belum ada produk yang tersedia.</p>
            </div>
        @endif
    </main>
</body>
</html>
