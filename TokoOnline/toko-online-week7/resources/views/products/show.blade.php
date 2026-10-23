
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                            @if (Auth::user()->role === 'customer')
                                <form
                                    action="{{ route('cart.add') }}"
                                    method="POST"
                                    data-cart-add
                                    data-product-id="{{ $product->id_barang }}"
                                >
                                    @csrf
                                    <button type="submit" class="button">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            @else
                                <button
                                    type="button"
                                    class="button button-disabled"
                                    disabled
                                >
                                    Khusus Customer
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="button">
                                Login untuk Membeli
                            </a>
                        @endauth
                    @else
                        <button
                            type="button"
                            class="button button-disabled"
                            disabled
                        >
                            Stok Habis
                        </button>
                    @endif

                    @auth
                        <a
                            href="{{ route('cart.index') }}"
                            class="button button-secondary"
                        >
                            Lihat Keranjang
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    <script>
        document.querySelectorAll('[data-cart-add]').forEach(form => {
            form.addEventListener('submit', async event => {
                event.preventDefault();

                const button = form.querySelector('button');
                button.disabled = true;

                try {
                    const cart = JSON.parse(
                        localStorage.getItem('shopping_cart') || '{}'
                    );

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector(
                                '[name="_token"]'
                            ).value
                        },
                        body: JSON.stringify({
                            cart: cart,
                            product_id: form.dataset.productId
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message || 'Produk gagal ditambahkan.'
                        );
                    }

                    const updatedCart = {};

                    data.items.forEach(item => {
                        updatedCart[String(item.id)] = item.quantity;
                    });

                    localStorage.setItem(
                        'shopping_cart',
                        JSON.stringify(updatedCart)
                    );

                    alert('Produk berhasil ditambahkan ke keranjang.');
                } catch (error) {
                    alert(error.message);
                } finally {
                    button.disabled = false;
                }
            });
        });
    </script>
</body>
</html>
