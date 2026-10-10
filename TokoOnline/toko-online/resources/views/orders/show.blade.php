<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Toko Online</title>
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
        <a href="{{ route('orders.index') }}" class="back-link">
            ← Kembali ke Riwayat Pesanan
        </a>

        <section class="card">
            <h1>Detail Pesanan</h1>

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="order-info">
                <div>
                    <span class="info-label">Nomor Pesanan</span>
                    <strong>{{ $order->id_order }}</strong>
                </div>

                <div>
                    <span class="info-label">Tanggal Pesanan</span>
                    <strong>
                        {{ $order->tanggal_order ? $order->tanggal_order->format('d-m-Y H:i') : '-' }}
                    </strong>
                </div>

                <div>
                    <span class="info-label">Alamat Pengiriman</span>
                    <strong>{{ $order->alamat_pengiriman }}</strong>
                </div>
            </div>

            <h2>Produk yang Dipesan</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($order->details as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $detail->product ? $detail->product->nama_barang : $detail->id_barang }}
                                </td>

                                <td>
                                    Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td>{{ $detail->Jumlah_beli }}</td>

                                <td>
                                    Rp{{ number_format($detail->harga_satuan * $detail->Jumlah_beli, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Tidak ada detail produk pada pesanan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="order-total">
                <span>Total Pesanan</span>
                <strong>
                    Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                </strong>
            </div>
        </section>

        <a href="{{ route('products.index') }}" class="button">
            Kembali Belanja
        </a>
    </main>
</body>
</html>
