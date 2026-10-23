
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <div class="alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="message-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div id="cart-message"></div>

        <div
            id="cart-content"
            data-update-url="{{ route('cart.update') }}"
        >
            <p>Memuat keranjang...</p>
        </div>
    </main>

    <script>
        const cartContent = document.getElementById('cart-content');
        const cartMessage = document.getElementById('cart-message');
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        ).content;

        function getCart() {
            try {
                const cart = JSON.parse(
                    localStorage.getItem('shopping_cart') || '{}'
                );

                if (
                    cart === null ||
                    typeof cart !== 'object' ||
                    Array.isArray(cart)
                ) {
                    return {};
                }

                return cart;
            } catch {
                return {};
            }
        }

        function saveCart(cart) {
            localStorage.setItem(
                'shopping_cart',
                JSON.stringify(cart)
            );
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[character]);
        }

        async function validateCart(cart) {
            const updateUrl = cartContent.dataset.updateUrl;

            const response = await fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    cart: cart
                })
            });

            let data;

            try {
                data = await response.json();
            } catch {
                throw new Error(
                    'Server tidak mengembalikan JSON yang valid.'
                );
            }

            if (!response.ok) {
                const errors = data.errors
                    ? Object.values(data.errors).flat().join('\n')
                    : data.message;

                throw new Error(
                    errors || 'Keranjang gagal diperiksa.'
                );
            }

            if (!Array.isArray(data.items)) {
                throw new Error(
                    'Format data keranjang dari server tidak sesuai.'
                );
            }

            const validatedCart = {};

            data.items.forEach(item => {
                validatedCart[String(item.id)] = item.quantity;
            });

            saveCart(validatedCart);

            return data;
        }

        function renderCart(data) {
            if (!data.items || data.items.length === 0) {
                cartContent.innerHTML = `
                    <div class="empty">
                        <h3>Keranjang masih kosong</h3>
                        <p>Silakan pilih produk terlebih dahulu.</p>
                        <a href="{{ route('products.index') }}">
                            Lihat Katalog
                        </a>
                    </div>
                `;

                return;
            }

            let rows = '';

            data.items.forEach(item => {
                const id = escapeHtml(item.id);

                rows += `
                    <tr>
                        <td>${escapeHtml(item.name)}</td>

                        <td>
                            Rp${Number(item.price).toLocaleString('id-ID')}
                        </td>

                        <td>
                            <input
                                type="number"
                                min="1"
                                max="99"
                                value="${Number(item.quantity)}"
                                id="quantity-${id}"
                                aria-label="Jumlah ${escapeHtml(item.name)}"
                            >
                        </td>

                        <td>
                            Rp${Number(item.subtotal).toLocaleString('id-ID')}
                        </td>

                        <td>
                            <button
                                type="button"
                                onclick="updateQuantity('${id}')"
                            >
                                Ubah
                            </button>

                            <button
                                type="button"
                                onclick="removeItem('${id}')"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
            });

            cartContent.innerHTML = `
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
                            ${rows}
                        </tbody>
                    </table>
                </div>

                <div class="cart-summary">
                    <a href="{{ route('products.index') }}">
                        Lanjut Belanja
                    </a>

                    <div class="total">
                        Total: Rp${Number(data.total).toLocaleString('id-ID')}
                    </div>

                    <a href="{{ route('checkout.index') }}">
                        Checkout
                    </a>
                </div>
            `;
        }

        async function loadCart() {
            try {
                const data = await validateCart(getCart());
                renderCart(data);
            } catch (error) {
                cartContent.innerHTML = `
                    <p class="message-error">
                        ${escapeHtml(error.message)}
                    </p>
                `;
            }
        }

        async function updateQuantity(id) {
            const input = document.getElementById(`quantity-${id}`);
            const quantity = Number(input.value);

            if (
                !Number.isInteger(quantity) ||
                quantity < 1 ||
                quantity > 99
            ) {
                alert(
                    'Jumlah harus berupa bilangan bulat antara 1 dan 99.'
                );
                return;
            }

            const cart = getCart();
            cart[id] = quantity;

            try {
                const data = await validateCart(cart);
                cartMessage.innerHTML = '';
                renderCart(data);
            } catch (error) {
                alert(error.message);
            }
        }

        async function removeItem(id) {
            const cart = getCart();
            delete cart[id];

            try {
                const data = await validateCart(cart);
                cartMessage.innerHTML = '';
                renderCart(data);
            } catch (error) {
                alert(error.message);
            }
        }

        loadCart();
    </script>
</body>
</html>
