@extends('layouts.dashboard')
@section('content')
<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
<div class="main-content">
    <div class="container">
        <h1>Edit Category</h1>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Category Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            @if ($category->image)
                <div class="form-group">
                    <img src="{{ asset($category->image) }}" alt="Category Image" width="150">
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</div>
@endsection
