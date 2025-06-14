<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/post.css') }}">
</head>
@extends('layouts.dashboard')
@section('content')
    <div class="main-content">
        <div class="container">
            <h1 class="text-center mb-3">Danh sách bài viết</h1>

            <div class="table-search-wrapper">
                <form class="table-search-form" action="{{ route('posts.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Tìm kiếm bài viết" value="{{ $search ?? '' }}" />
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <a href="{{ route('posts.create') }}" class="add-link">Thêm bài viết</a>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th>Ảnh</th>
                        <th>Tag</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->category->name ?? '-' }}</td>
                        <td>
                            @if($post->thumbnail)
                                <img src="{{ asset($post->thumbnail) }}" width="80" height="60"
                                    loading="lazy" alt="Thumbnail {{ $post->title }}"
                                    style="object-fit: cover; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            @endif
                        </td>
                        <td>
                            @foreach($post->tags as $tag)
                                <span class="badge bg-info">{{ $tag->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-primary btn-sm">Xem</a>
                            <a href="#" class="btn btn-danger btn-sm"
                            onclick="event.preventDefault(); if(confirm('Bạn có chắc muốn xóa bài viết này không?')) document.getElementById('delete-post-{{ $post->id }}').submit();">
                                Xóa
                            </a>
                            <form id="delete-post-{{ $post->id }}" action="{{ route('posts.destroy', $post) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $posts->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- jQuery (chỉ cần nếu bạn dùng plugin khác, không cần cho Bootstrap 5 tooltips) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS Bundle (gồm Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tooltip Bootstrap 5
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Tìm và gán sự kiện click nếu phần tử tồn tại
            const searchTrigger = document.querySelector('.search-trigger');
            const searchDropdown = document.querySelector('.table-search-dropdown');

            if (searchTrigger && searchDropdown) {
                searchTrigger.addEventListener('click', function () {
                    searchDropdown.classList.toggle('active');
                });

                document.addEventListener('click', function (event) {
                    if (!searchTrigger.contains(event.target) && !searchDropdown.contains(event.target)) {
                        searchDropdown.classList.remove('active');
                    }
                });
            }
        });
    </script>

@endsection

