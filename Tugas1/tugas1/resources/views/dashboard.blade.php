
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <p class="subtitle">Halaman utama pengguna</p>

        <div class="welcome">
            Selamat datang,<br>
            <strong>{{ Auth::user()->nama_lengkap }}!</strong>
            <p>Anda berhasil login ke aplikasi.</p>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
