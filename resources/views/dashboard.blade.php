<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="col-md-8 mx-auto text-center">
            <div class="alert alert-success">
                {{ session('message') ?? 'You have logged in successfully!' }}
            </div>

            <h3>Welcome to the Dashboard</h3>
        </div>
    </div>
</body>
</html>
