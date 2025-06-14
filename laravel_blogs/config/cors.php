<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Chỉ áp dụng CORS cho các tuyến API
    'allowed_methods' => ['*'], // Chỉ cho phép các phương thức cần thiết
    'allowed_origins' => [
        'http://localhost:5173', // Vue cục bộ
        'http://127.0.0.1:8000', // Vue cục bộ
        'https://phuctoanblog.toantran.io.vn', // Domain Vue khi online
    ],
    'allowed_origins_patterns' => [], // Có thể dùng regex nếu cần
    'allowed_headers' => ['Content-Type', 'Authorization' , 'Cache-Control'/*, 'X-API-Key'*/], // Chỉ cho phép header cần thiết
    'exposed_headers' => [],
    'max_age' => 0, // Không cache preflight
    'supports_credentials' => false,
];
