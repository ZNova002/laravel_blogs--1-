<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QLBLOGS</title>
    <link rel="stylesheet" href="{{ asset('admin/css/login.css') }}">
</head>
<body>
    <div class="form-container">
        <h2>Đăng nhập</h2>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <label for="name">Tên người dùng:</label>
            <input type="text" id="name" name="name" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
            <button type="submit">Đăng nhập</button>
        </form>

        <p>Quên mật khẩu? <a href="/forgot-password">Nhấn vào đây để lấy lại mật khẩu</a></p>
    </div>
</body>
</html>
