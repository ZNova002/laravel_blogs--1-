@extends('layouts.dashboard')
@section('content')
<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
<div class="main-content">
    <div class="container">
        <h1>Users List</h1>
        <a class="btn btn-primary float-right" href="{{ route('users.create') }}">Create New</a>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Avatar</th>
                <th>Actions</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                    @if ($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    @else
                        <span>No avatar</span>
                    @endif
                    </td>
                    <td>
                        <div class="action-links">
                            <a href="{{ route('users.edit', $user) }}"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
