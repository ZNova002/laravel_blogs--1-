@extends('layouts.dashboard')

    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">

@section('content')
<div class="main-content">
    <div class="container container-small">
        <h1>Edit User</h1>

        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <input type="text" name="name" placeholder="Name" value="{{ old('name', $user->name) }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="New Password (leave blank to keep)">
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <input type="text" name="address" placeholder="Address" value="{{ old('address', $user->address) }}">
                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <input type="text" name="phone_number" placeholder="Phone" value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <input type="file" name="avatar">
                @if(isset($user) && $user->avatar)
                    <div style="margin-top: 10px;">
                        <img src="{{ asset($user->avatar) }}" alt="Avatar" style="max-width: 100px; border-radius: 8px;">
                    </div>
                @endif
            </div>

            <div class="form-group">
                <select name="role">
                    <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</div>
@endsection
