<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label>Nama:</label>
        <input type="text" name="name" required>
        <br>

        <label>Username:</label>
        <input type="text" name="username" required>
        <br>

        <label>Email:</label>
        <input type="email" name="email" required>
        <br>

        <label>Password:</label>
        <input type="password" name="password" required>
        <br>

        <label>Konfirmasi Password:</label>
        <input type="password" name="password_confirmation" required>
        <br>

        @error('email')
            <p style="color: red;">{{ $message }}</p>
        @enderror

        <button type="submit">Register</button>
    </form>

    <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
</body>
</html>
