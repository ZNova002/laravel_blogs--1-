@extends('layouts.dashboard')

<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">

@section('content')
    <div class="main-content">
        <div class="container">
            <h1>Add New Tag</h1>
            <form action="{{ route('tags.store') }}" method="POST" class="form">
                @csrf
                <div class="form-group">
                    <label for="name">Tag Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Tag</button>
            </form>
        </div>
    </div>
@endsection
