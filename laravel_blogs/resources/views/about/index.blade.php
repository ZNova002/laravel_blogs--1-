@extends('layouts.dashboard')
@section('content')
<link rel="stylesheet" href="{{ asset('admin/css/about.css') }}">
    <main class="about-page">
        <div class="main-content">
            <h1 class="about-title">{{ $about->title }}</h1>

            @if($about->thumbnail)
                <div class="about-thumbnail">
                    <img src="{{ asset($about->thumbnail) }}" alt="Thumbnail" class="thumbnail-img">
                </div>
            @endif

            <div class="about-content">
                {!! $about->content !!}
            </div>

            <div class="edit-button" style="margin-bottom: 20px;">
                <a href="{{ route('about.edit', $about->id) }}" class="btn btn-warning">Chỉnh sửa</a>
            </div>

        </div>
    </main>
    <style>

    </style>
@endsection
