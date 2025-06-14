<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
        <link rel="stylesheet" href="{{ asset('admin/css/login.css') }}">
    <style>
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Đặt lại mật khẩu</h2>

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

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="email">Địa chỉ email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>

            <label for="password">Mật khẩu mới:</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirmation">Xác nhận mật khẩu:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>

            <button type="submit">Đặt lại mật khẩu</button>
        </form>

        <p>Đã nhớ mật khẩu? <a href="{{ route('login') }}">Đăng nhập ngay</a></p>
    </div>
</body>
</html>
