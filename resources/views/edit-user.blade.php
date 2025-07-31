@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="container mt-4">
        <h2>Edit Username</h2>

        {{-- ✅ Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ❌ Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✏️ Edit Form --}}
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('users.list') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
