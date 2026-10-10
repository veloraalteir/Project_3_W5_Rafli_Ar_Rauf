
<?php
$produk = [
    1 => ['nama' => 'Buku Tulis', 'harga' => 5000],
    2 => ['nama' => 'Pulpen', 'harga' => 3000],
    3 => ['nama' => 'Penggaris', 'harga' => 4000],
];

$keranjang = isset($_COOKIE['keranjang'])
    ? json_decode($_COOKIE['keranjang'], true)
    : [];

if (!is_array($keranjang)) {
    $keranjang = [];
}

if (isset($_GET['tambah'])) {
    $id = (int) $_GET['tambah'];

    if (isset($produk[$id])) {
        $keranjang[$id] = (int) ($keranjang[$id] ?? 0) + 1;
        setcookie('keranjang', json_encode($keranjang), time() + 3600, '/');
    }

    header('Location: keranjang_cookie.php');
    exit;
}

if (isset($_GET['kosongkan'])) {
    setcookie('keranjang', '', time() - 3600, '/');
    header('Location: keranjang_cookie.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang dengan Cookies</title>
</head>
<body>
    <h1>Daftar Produk</h1>

    <ul>
        <?php foreach ($produk as $id => $p): ?>
            <li>
                <?= htmlspecialchars($p['nama']) ?>
                - Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                <a href="?tambah=<?= $id ?>">[Tambah]</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Keranjang Anda</h2>

    <?php if (empty($keranjang)): ?>
        <p>Keranjang kosong.</p>
    <?php else: ?>
        <?php $total = 0; ?>
        <ul>
            <?php foreach ($keranjang as $id => $jumlah): ?>
                <?php
                if (!isset($produk[$id]) || !is_numeric($jumlah)) {
                    continue;
                }

                $jumlah = max(0, (int) $jumlah);
                $subtotal = $produk[$id]['harga'] * $jumlah;
                $total += $subtotal;
                ?>
                <li>
                    <?= htmlspecialchars($produk[$id]['nama']) ?>
                    x <?= $jumlah ?>
                    = Rp <?= number_format($subtotal, 0, ',', '.') ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <p><strong>Total: Rp <?= number_format($total, 0, ',', '.') ?></strong></p>
        <a href="?kosongkan=1">Kosongkan keranjang</a>
    <?php endif; ?>
</body>
</html>
