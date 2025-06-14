<!DOCTYPE html>
<html>
<head>
    <title>QLBLOGS - {{ $post->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
@extends('layouts.dashboard')
@section('content')
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
    <style>
        .table th.id-col, .table td.id-col {
            width: 50px;
            text-align: center;
            vertical-align: middle;
        }
        .table th.email-col, .table td.email-col {
            width: 200px;
            text-align: center;
            vertical-align: middle;
        }
        .table th.phone-col, .table td.phone-col {
            width: 150px;
            text-align: center;
            vertical-align: middle;
        }
        .table th.message-col, .table td.message-col {
            width: 300px;
            text-align: left;
            vertical-align: middle;
        }
        .table th.created-col, .table td.created-col {
            width: 150px;
            text-align: center;
            vertical-align: middle;
        }
        .table th.action-col, .table td.action-col {
            width: 150px;
            text-align: center;
            vertical-align: middle;
        }
        .action-links .btn {
            margin: 0 5px;
        }
    </style>
    <div class="main-content">
        <div class="container">
            <h1>Quản lý bình luận - {{ $post->title }}</h1>
            <a href="{{ route('comments.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách bài viết</a>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form thêm bình luận -->
            <h3>Thêm bình luận</h3>
            <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-5">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="message">Nội dung</label>
                            <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @error('_')
                        <div class="col-md-12">
                            <span class="text-danger">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Thêm bình luận</button>
            </form>

            <!-- Danh sách bình luận -->
            <h3>Danh sách bình luận</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="id-col">ID</th>
                        <th class="email-col">Email</th>
                        <th class="phone-col">Số điện thoại</th>
                        <th class="message-col">Nội dung</th>
                        <th class="created-col">Thời gian</th>
                        <th class="action-col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments as $comment)
                    <tr>
                        <td class="id-col">{{ $comment->id }}</td>
                        <td class="email-col">{{ $comment->email ?? '-' }}</td>
                        <td class="phone-col">{{ $comment->phone ?? '-' }}</td>
                        <td class="message-col">{{ Str::limit($comment->message, 100) }}</td>
                        <td class="created-col">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="action-col">
                            <a href="{{ route('comments.edit', $comment) }}" class="btn btn-sm btn-warning">
                                Sửa
                            </a>
                            <a href="#" class="btn btn-sm btn-danger"
                                onclick="event.preventDefault(); if(confirm('Bạn có chắc muốn xóa bình luận này không?')) document.getElementById('delete-comment-{{ $comment->id }}').submit();">
                                Xóa
                            </a>
                            <form id="delete-comment-{{ $comment->id }}" action="{{ route('comments.destroy', $comment) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
</body>
</html>
