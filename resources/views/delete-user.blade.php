
{{-- resources/views/delete-user.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Confirm Delete</h2>

    <div class="alert alert-warning">
        Are you sure you want to delete the following user?
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Yes, Delete</button>
        <a href="{{ route('users.list') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
