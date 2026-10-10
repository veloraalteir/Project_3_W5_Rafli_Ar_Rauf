
<?php
if (isset($_GET['reset'])) {
    setcookie("nama_pengguna", "", time() - 3600, "/");
    header("Location: cookie_dasar.php");
    exit;
}

if (!isset($_COOKIE['nama_pengguna'])) {
    setcookie("nama_pengguna", "Budi", time() + 3600, "/");
    $pesan = "Cookie dibuat. Refresh halaman untuk melihat nilainya.";
} else {
    $pesan = "Nama pengguna: " . htmlspecialchars($_COOKIE['nama_pengguna']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengujian Cookie</title>
</head>
<body>
    <h1>Pengujian Cookie</h1>
    <p><?= $pesan ?></p>
    <p><a href="cookie_dasar.php">Refresh halaman</a></p>
    <p><a href="cookie_dasar.php?reset=1">Hapus cookie</a></p>
</body>
</html>
