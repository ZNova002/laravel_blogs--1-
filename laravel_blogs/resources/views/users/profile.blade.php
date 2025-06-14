@extends('layouts.dashboard')
<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
@section('content')
<div class="main-content">
    <div class="container">
        <h1>Thông Tin Cá Nhân</h1>

        <form action="{{ route('users.update', auth()->user()) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Họ tên:</label>
                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
            </div>

            <div class="form-group">
                <label>Địa chỉ:</label>
                <input type="text" name="address" class="form-control" value="{{ auth()->user()->address }}">
            </div>

            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="phone_number" class="form-control" value="{{ auth()->user()->phone_number }}">
            </div>

            <div class="form-group">
                <label>Avatar hiện tại:</label><br>
                @if ($user->avatar)
                    <img src="{{ asset($user->avatar) }}" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                @else
                    <span>No avatar</span>
                @endif
            </div>

            <div class="form-group">
                <label>Đổi avatar:</label>
                <input type="file" name="avatar" class="form-control">
            </div>

            <div class="form-group">
                <label>Mật khẩu mới (nếu muốn đổi):</label>
                <input type="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-success mt-3">Cập nhật</button>
        </form>
    </div>
</div>
@endsection
