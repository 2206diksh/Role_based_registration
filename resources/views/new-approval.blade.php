@extends('layouts.admin')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New User Login Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            color: #333;
            padding: 20px;
        }
        .container {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }
        h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔔 New User Login Notification</h2>

        <div class="info">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Login Time:</strong> {{ \Carbon\Carbon::now()->toDayDateTimeString() }}</p>
        </div>

        <div class="footer">
            This is an automated alert from your Laravel application.
        </div>
    </div>
</body>
</html>
