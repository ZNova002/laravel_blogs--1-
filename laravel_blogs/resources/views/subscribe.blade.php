<!DOCTYPE html>
<html>
<head>
    <title>QLBLOGS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
    <style>
    .table-search-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        width: 100%;
        box-sizing: border-box;
    }

    .table-search-form {
        display: flex;
        align-items: center;
        gap: 5px;
        width: 100%;
        max-width: 350px;
        border-radius: 150px;
    }

    .table-search-form button {
        height: 38px;
        width: 45px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s ease;

        position: relative;
        top: -7px;
    }
    .table-search-form input[type="text"] {
        flex-grow: 1;
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .table-search-form input[type="text"]:focus {
        border-color: #007bff;
        background-color: #ffffff;
    }

    .table-search-form button {
        height: 38px;
        width: 80px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .table-search-form button:hover {
        background-color: #0056b3;
    }
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
    </style>
</head>
<body>
@extends('layouts.dashboard')
@section('content')
    <div class="main-content">
        <div class="container">
            <h1>Đăng ký nhận thông báo bài viết mới</h1>
            @if (session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif
            @if ($errors->any())
                <ul style="color: red;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <form method="POST" action="{{ route('subscribe.store') }}">
                @csrf
                <input type="email" name="email" placeholder="Nhập email của bạn" required>
                <button type="submit">Đăng ký</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchTrigger = document.querySelector('.search-trigger');
            const searchDropdown = document.querySelector('.table-search-dropdown');

            searchTrigger.addEventListener('click', function () {
                searchDropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (event) {
                if (!searchTrigger.contains(event.target) && !searchDropdown.contains(event.target)) {
                    searchDropdown.classList.remove('active');
                }
            });
        });
    </script>
@endsection
</body>
</html>
