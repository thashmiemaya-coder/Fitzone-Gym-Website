<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

$result = $conn->query("SELECT id, fullname, email FROM users WHERE role = 'member'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('k.png');
            background-size: cover;
            background-position: center;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .bg-overlay {
            
            backdrop-filter: blur(4px);
            min-height: 100vh;
            padding-top: 80px;
            padding-bottom: 40px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .table {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
            text-align: center;
        }

        h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 30px;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.6);
        }
    </style>
</head>
<body>

<div class="bg-overlay">
    <div class="container">
        <h2>Registered Members</h2>
        <div class="card p-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="text-center mt-4">
                <a href="staff_dashboard.php" class="btn btn-custom">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
