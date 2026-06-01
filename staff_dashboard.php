<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Background Image */
        .bg-image {
            background-image: url('k.png');
            background-size: cover;
            background-position: center;
            position: absolute;
            height: 100%;
            width: 100%;
            z-index: -1;
            filter: brightness(0.4);
        }

        /* Navbar Styling */
        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.75) !important;
        }

        /* Welcome Text */
        .welcome {
            color: #fff;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
            font-weight: bold;
        }

        /* Card Styling */
        .dashboard-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            transition: 0.3s;
            backdrop-filter: blur(5px);
            border: 2px solid #fff;
        }

        .dashboard-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        /* Button Styling */
        .btn-glow {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 18px;
            transition: 0.3s ease;
        }

        .btn-glow:hover {
            background: linear-gradient(to right, #feb47b, #ff7e5f);
            box-shadow: 0 0 20px rgba(255, 105, 180, 0.7);
        }

        .btn-glow:active {
            transform: scale(0.98);
        }

        /* Container Styling */
        .container-content {
            padding-top: 80px;
        }

        /* Row Spacing */
        .row.g-4 {
            margin-top: 50px;
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

    </style>
</head>
<body>

<div class="bg-image"></div>

<nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
    <a class="navbar-brand" href="#">Staff Dashboard</a>
    <div class="ms-auto">
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container container-content">
    <h2 class="text-center welcome mb-5">Welcome, Staff!</h2>

    <div class="row g-4 justify-content-center">
        <!-- View Member List Card -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5 class="card-title text-primary">View Member List</h5>
                <p class="card-text">Access all registered members' details.</p>
                <a href="member_list.php" class="btn btn-glow">View Members</a>
            </div>
        </div>

        <!-- View Membership Queries Card -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5 class="card-title text-info">View Membership Queries</h5>
                <p class="card-text">Check out all the queries from members.</p>
                <a href="view_queries.php" class="btn btn-glow">View Queries</a>
            </div>
        </div>

        <!-- Reply to Queries Card -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5 class="card-title text-warning">Reply to Queries</h5>
                <p class="card-text">Send responses to member inquiries.</p>
                <a href="reply_queries.php" class="btn btn-glow">Reply Now</a>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Staff Dashboard. All rights reserved.</p>
</div>

</body>
</html>
