<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Toko Online</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <h1>Registrasi</h1>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap"
                   value="{{ old('nama_lengkap') }}" required>

            <label>Email</label>
            <input type="email" name="email"
                   value="{{ old('email') }}" required>

            <label>Username</label>
            <input type="text" name="username"
                   value="{{ old('username') }}" required>

            <label>Password (minimal 8 karakter)</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>

            <label>Nomor HP</label>
            <input type="text" name="no_hp"
                   value="{{ old('no_hp') }}" required>

            <label>Alamat</label>
            <textarea name="alamat" required>{{ old('alamat') }}</textarea>

            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
        <p><a href="{{ route('products.index') }}">Kembali ke katalog</a></p>
    </div>
</body>
</html>