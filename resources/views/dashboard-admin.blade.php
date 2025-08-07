<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
            width: 250px;
            overflow-y: auto;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px 16px;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: #495057;
        }
        .sidebar .submenu a {
            padding-left: 32px;
            font-size: 0.95rem;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="text-center mb-4">Admin Menu</h5>

        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>

        <!-- Users submenu -->
        <a data-bs-toggle="collapse" href="#userMenu" role="button" aria-expanded="false" aria-controls="userMenu">
            👥 Users
        </a>
        <div class="collapse submenu" id="userMenu">
            <a href="{{ route('users.list') }}">📋 User List</a>
            <a href="#">⚙️ User Management</a> <!-- Replace '#' with actual route when ready -->
        </div>

        <!-- Uploaded Files -->
        <a href="{{ route('admin.upload.list') }}">📁 Uploaded Files</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h1 class="mb-4">Admin Dashboard</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <h4>Total Registered Users: {{ $totalUsers }}</h4>
                    <h4>Active Users: {{ $activeUsersCount }}</h4>
                    <h4>Inactive Users: {{ $inactiveUsersCount }}</h4>
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
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingApprovals as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                </form>

                                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (for collapse) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
