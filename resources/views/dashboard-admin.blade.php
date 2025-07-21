<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="mb-4">Admin Dashboard</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h4>Total Registered Users: {{ $totalUsers }}</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Users Pending Approval</h5>
        </div>
        <div class="card-body">
            @if($pendingApprovals->isEmpty())
                <p class="text-muted">No users waiting for approval.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Approve</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingApprovals as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.approve', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

</body>
</html>
