@extends('layouts.admin')

@section('content')
<div class="container mt-5 d-flex flex-column justify-content-between" style="min-height: 80vh;">
    <div>
        <h2 class="mb-4">Uploaded Files</h2>

        {{-- 🔍 Search Bar --}}
        <form method="GET" action="{{ route('admin.upload.list') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by file name..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </div>
        </form>

        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- 📁 File Table --}}
        @if($files->count())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Uploaded</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
                        <tr>
                            <td>{{ $file->original_name }}</td>
                            <td>{{ $file->created_at->diffForHumans() }}</td>
                            <td class="text-end">
                                <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="btn btn-sm btn-secondary">View</a>
                                <form action="{{ route('admin.upload.delete', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No files uploaded yet.</p>
        @endif
    </div>

    {{-- ✅ Upload Button at Bottom --}}
    <div class="text-center mt-4">
        <a href="{{ route('file.upload.form') }}" class="btn btn-success">Upload New File</a>
    </div>
</div>
@endsection
