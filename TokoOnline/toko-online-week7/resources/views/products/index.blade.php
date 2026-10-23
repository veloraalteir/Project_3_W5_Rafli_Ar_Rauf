
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Produk - Toko Online</title>
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
</head>
<body>
    <nav class="navbar">
        <h2>Toko Online</h2>

        <div class="nav-links">
            @auth
                <span>Halo, {{ Auth::user()->nama_lengkap }}</span>

                @if (Auth::user()->role === 'customer')
                    <a href="{{ route('cart.index') }}">Keranjang</a>
                    <a href="{{ route('orders.index') }}">Pesanan Saya</a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-button">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar</a>
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

                            {{-- Stok hanya untuk staff dan admin --}}
                            @auth
                                @if (in_array(Auth::user()->role, ['staff', 'admin'], true))
                                    <p class="stock">
                                        @if ($product->stok > 0)
                                            Stok: {{ $product->stok }}
                                        @else
                                            Stok habis
                                        @endif
                                    </p>
                                @endif
                            @endauth

                            <a
                                href="{{ route('products.show', $product->id_barang) }}"
                                class="detail-link"
                            >
                                Lihat Detail
                            </a>

                            @if ($product->stok > 0)
                                @auth
                                    @if (Auth::user()->role === 'customer')
                                        <form
                                            action="{{ route('cart.add') }}"
                                            method="POST"
                                            class="add-cart-form"
                                            data-cart-add
                                            data-product-id="{{ $product->id_barang }}"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="add-cart-button"
                                            >
                                                Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <button
                                            type="button"
                                            class="add-cart-button disabled"
                                            disabled
                                        >
                                            Khusus Customer
                                        </button>
                                    @endauth
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="add-cart-button"
                                    >
                                        Login untuk Membeli
                                    </a>
                                @endauth
                            @else
                                <button
                                    type="button"
                                    class="add-cart-button disabled"
                                    disabled
                                >
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

                    const csrfToken = document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content;

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            cart: cart,
                            product_id: form.dataset.productId
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        const validationErrors = data.errors
                            ? Object.values(data.errors).flat().join('\n')
                            : data.message;

                        throw new Error(
                            validationErrors || 'Produk gagal ditambahkan.'
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
                    alert(
                        error.message ||
                        'Terjadi kesalahan saat menambahkan produk.'
                    );
                } finally {
                    button.disabled = false;
                }
            });
        });
    </script>
</body>
</html>
