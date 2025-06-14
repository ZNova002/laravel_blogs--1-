@extends('layouts.dashboard')
@section('content')
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
    <div class="main-content">
        <div class="container">
            <h1>Tags</h1>
            <a href="{{ route('tags.create') }}" class="btn btn-primary float-right">Add Tag</a>

            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->id}}</td>
                            <td>{{ $tag->name }}</td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('tags.edit', $tag) }}"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
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
