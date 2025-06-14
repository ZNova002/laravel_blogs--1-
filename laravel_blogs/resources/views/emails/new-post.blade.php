<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài viết mới: {{ $data['post']['title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff ;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .button {
            color: #ffffff !important;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding: 10px;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bài viết mới từ {{ env('APP_NAME', 'Blog của bạn') }}</h1>
        </div>
        <div class="content">
            <h2>{{ $data['post']['title'] }}</h2>
            <p>Xin chào,</p>
            <p>Chúng tôi rất vui mừng thông báo rằng một bài viết mới đã được đăng trên {{ env('APP_NAME', 'Blog của bạn') }}! Dưới đây là một số thông tin về bài viết:</p>
            <ul>
                <li><strong>Tiêu đề:</strong> {{ $data['post']['title'] }}</li>
                <li><strong>Ngày đăng:</strong> {{ \Carbon\Carbon::parse($data['post']['created_at'])->format('d/m/Y') }}</li>
                <li><strong>Tóm tắt:</strong> {{ \Illuminate\Support\Str::limit(strip_tags($data['post']['content']), 150) }}</li>
            </ul>
            <p>Để đọc toàn bộ bài viết, hãy nhấp vào nút dưới đây:</p>
            <a href="{{ env('FRONTEND_URL', 'https://phuctoanblog.toantran.io.vn') }}/post/{{ $data['post']['id'] }}" class="button">Xem bài viết</a>
            <p>Nếu bạn muốn khám phá thêm các bài viết khác, hãy truy cập trang chủ của chúng tôi: <a href="{{ env('FRONTEND_URL', 'https://phuctoanblog.toantran.io.vn') }}">{{ env('FRONTEND_URL', 'https://phuctoanblog.toantran.io.vn') }}</a>.</p>
            <p>Cảm ơn bạn đã đăng ký nhận thông báo từ chúng tôi! Nếu bạn có bất kỳ câu hỏi hoặc phản hồi nào, đừng ngần ngại liên hệ qua email <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a>.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ env('APP_NAME', 'Blog của bạn') }}. Tất cả quyền được bảo lưu.</p>
            <p>Không muốn nhận thông báo nữa? <a href="{{ url('/unsubscribe/' . base64_encode($data['email'])) }}">Hủy đăng ký</a></p>
            <p>Liên hệ: <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a> | Địa chỉ: [Địa chỉ của bạn]</p>
        </div>
    </div>
</body>
</html>
