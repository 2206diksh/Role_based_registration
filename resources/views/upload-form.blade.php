@extends('layouts.admin')

@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="card col-md-12">
        <div class="card-body">
            <h2 class="text-center mb-4">Upload a New File</h2>

            {{-- Display errors if any --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Upload Form --}}
            <form action="{{ route('file.upload.store') }}" method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Choose a file:</label>
                    <input type="file" name="file" class="form-control" id="file" required multiple>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Upload</button>
                    <a href="{{ route('admin.upload.list') }}" class="btn btn-secondary ms-2">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
