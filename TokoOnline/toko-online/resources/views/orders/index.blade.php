<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Toko Online</title>
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
</head>
<body>
    <nav class="navbar">
        <h2>Toko Online</h2>
        <div class="nav-links">
            <a href="{{ route('products.index') }}">Katalog Produk</a>
            <a href="{{ route('cart.index') }}">Keranjang</a>
            <a href="{{ route('orders.index') }}">Pesanan Saya</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-button">Logout</button>
            </form>
        </div>
    </nav>

    <main class="container">
        <section class="card">
            <h1>Riwayat Pesanan</h1>
            <p class="description">
                Berikut adalah daftar pesanan yang pernah kamu lakukan.
            </p>

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($orders->isNotEmpty())
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nomor Pesanan</th>
                                <th>Tanggal Pesanan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->id_order }}</td>
                                    <td>
                                        {{ $order->tanggal_order
                                            ? $order->tanggal_order->format('d-m-Y H:i')
                                            : '-' }}
                                    </td>
                                    <td>
                                        Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('orders.show', $order->id_order) }}"
                                            class="button"
                                        >
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty">
                    <h3>Belum Ada Pesanan</h3>
                    <p>Kamu belum melakukan transaksi.</p>
                    <a href="{{ route('products.index') }}" class="button">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </section>

        <a href="{{ route('products.index') }}" class="back-link">
            ← Kembali ke Katalog
        </a>
    </main>
</body>
</html>