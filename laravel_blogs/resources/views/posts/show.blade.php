<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $post->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">{{ $post->title }}</h1>

    {{-- Hiển thị thumbnail nếu có --}}
    @if ($post->thumbnail)
        <div class="text-center mb-4">
            <img src="{{ asset($post->thumbnail) }}" class="img-fluid rounded shadow"
                 style="max-height: 400px; object-fit: cover;">
        </div>
    @endif

    {{-- Nội dung bài viết --}}
    <div class="mb-4">
        {!! $post->content !!}
    </div>

    {{-- Tags --}}
    @if ($post->tags->isNotEmpty())
        <div class="mb-3">
            <strong>Tags:</strong>
            @foreach ($post->tags as $tag)
                <span class="badge bg-primary">{{ $tag->name }}</span>
            @endforeach
        </div>
    @endif

    {{-- Ảnh phụ --}}
    @if (!empty($post->images) && is_array($post->images))
        <h4 class="mt-4">Ảnh bổ sung</h4>
        <div class="row">
            @foreach ($post->images as $image)
                <div class="col-md-4 mb-3">
                    <img src="{{ asset($image) }}" class="img-fluid rounded border"
                         alt="Post image" style="max-height: 250px; object-fit: cover;">
                </div>
            @endforeach
        </div>
    @endif

    {{-- Quay lại danh sách --}}
    <div class="mt-4">
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">← Quay lại danh sách bài viết</a>
    </div>
</div>
</body>
</html>
