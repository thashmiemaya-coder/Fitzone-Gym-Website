<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Include DB connection
include('db_connection.php');

// Fetch all member queries
$sql = "SELECT * FROM membership_queries ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Member Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('k.png');
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }

        .container {
            margin-top: 100px;
        }

        .table-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
        }

        table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-back {
            margin-top: 20px;
            background-color: #ff7e5f;
            border: none;
            padding: 10px 20px;
            color: #fff;
            border-radius: 25px;
            transition: 0.3s ease;
        }

        .btn-back:hover {
            background-color: #feb47b;
            color: #000;
        }

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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top px-4">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ms-auto">
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="table-container">
        <h2>Member Queries</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>id</th>
                            <th>user_id</th>
                            <th>subject</th>
                            <th>message</th>
                            <th> created_at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['user_id']) ?></td>
                               
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-light">No queries found.</p>
        <?php endif; ?>

        <div class="text-center">
            <a href="admin_dashboard.php" class="btn btn-back">Back to Dashboard</a>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Admin Dashboard. All rights reserved.</p>
</div>

</body>
</html>
