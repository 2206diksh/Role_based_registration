@extends('layouts.admin')
{{-- resources/views/show-user.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>User Details</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>
    </div>

    <a href="{{ route('users.list') }}" class="btn btn-secondary mt-3">← Back to User List</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
