<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QLBLOGS</title>
    <link rel="stylesheet" href="{{ asset('admin/css/login.css') }}">
</head>
<body>
    <div class="form-container">
        <h2>Quên mật khẩu</h2>
        @if(session('status'))
            <p class="success-message">{{ session('status') }}</p>
        @endif
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <label for="email">Email của bạn:</label>
            <input type="email" name="email" id="email" required>
            <button type="submit">Gửi yêu cầu</button>
        </form>
    </div>
</body>
</html>
