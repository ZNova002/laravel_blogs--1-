<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu</title>
    <link rel="stylesheet" href="{{ asset('admin/css/login.css') }}">
</head>
<body>
    <div class="form-container">
        <h2>Khôi phục mật khẩu</h2>

        @if (session('status'))
            <div class="errors">
                <ul>
                    <li>{{ session('status') }}</li>
                </ul>
            </div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <label for="email">Địa chỉ email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            <button type="submit">Gửi liên kết khôi phục</button>
        </form>

        <p>Đã nhớ mật khẩu? <a href="{{ route('login') }}">Đăng nhập ngay</a></p>
    </div>
</body>
</html>
