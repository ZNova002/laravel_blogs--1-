@extends('layouts.dashboard')
<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
@section('content')
<div class="main-content">
    <div class="container">
        <h1>Chi tiết liên hệ</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tên: {{ $contact->name }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ $contact->email }}</p>
                <p class="card-text"><strong>Nội dung:</strong> {{ $contact->message }}</p>
            </div>
        </div>

        <a href="{{ url('/contacts') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
    </div>
</div>

@endsection
