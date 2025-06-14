@extends('layouts.dashboard')
<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
@section('content')
<div class="main-content">
    <div class="container container-small">
        <h1>{{ isset($user) ? 'Edit User' : 'Create User' }}</h1>

        <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="form-group">
                <input type="text" name="name" placeholder="Name" value="{{ old('name', $user->name ?? '') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" name="address" placeholder="Address" value="{{ old('address', $user->address ?? '') }}">
                @error('address')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" name="phone_number" placeholder="Phone" value="{{ old('phone_number', $user->phone_number ?? '') }}">
                @error('phone_number')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <input type="file" name="avatar" id="avatarInput" onchange="previewAvatar(event)">
                @error('avatar')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                @if(isset($user) && $user->avatar)
                    <div style="margin-top: 10px;">
                        <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Avatar" style="max-width: 100px;">
                    </div>
                @endif
                <img id="avatarPreview" style="max-width: 100px; margin-top: 10px; display: none; border-radius: 8px;">

            </div>

            <div class="form-group">
                <select name="role">
                    <option value="customer" {{ (old('role', $user->role ?? '') == 'customer') ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($user) ? 'Update' : 'Create' }}
            </button>
        </form>
    </div>
</div>
<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('avatarPreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script>

@endsection
