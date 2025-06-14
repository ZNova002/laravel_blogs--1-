@extends('layouts.dashboard')
@section('content')
<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
<div class="main-content">
    <div class="container">
        <h1>Categories List</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary float-right">Create New</a>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}" alt="Image" style="width: 100px;">
                            @endif
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('categories.edit', $category->id) }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
