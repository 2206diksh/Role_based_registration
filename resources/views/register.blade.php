@extends('layouts.admin')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-6 mx-auto">

        <h3 class="text-center mb-4">Register</h3>

        {{-- Success Message --}}
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Create Password" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>

            {{-- 🔽 Role Selection --}}
            <div class="mb-3">
                <select name="role" class="form-select" required>
                    <option value="">-- Select Role --</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>

            <div class="text-center mt-3">
                <small>Already have an account? <a href="{{ route('login') }}">Login</a></small>
            </div>
        </form>
    </div>
</div>

</body>
</html>
