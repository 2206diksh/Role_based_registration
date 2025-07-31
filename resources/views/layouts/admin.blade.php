<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px 16px;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #495057;
        }

        .sidebar .submenu a {
            padding-left: 32px;
            font-size: 0.95rem;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="wrapper">

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
            <a href="#">⚙️ User Management</a>
        </div>

        <a href="{{ route('admin.upload.list') }}">📁 Uploaded Files</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            @yield('content')
        </div>
    </div>

</div>

<!-- Bootstrap JS (for collapse submenu) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
