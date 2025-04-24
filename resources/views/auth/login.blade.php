<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>

        <label>Password:</label>
        <input type="password" name="password" required>
        <br>

        @error('email')
            <p style="color: red;">{{ $message }}</p>
        @enderror

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
</body>
</html>
