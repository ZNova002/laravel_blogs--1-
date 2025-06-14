@extends('layouts.dashboard')

<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">

@section('content')
<div class="main-content">
    <div class="container container-small">
        <h1>Edit Tag</h1>

        <form action="{{ route('tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Tag Name:</label>
                <input type="text" name="name" placeholder="Name" value="{{ old('name', $tag->name) }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Tag</button>
        </form>
    </div>
</div>
@endsection
