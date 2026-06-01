<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Include database connection
include('db_connection.php'); // replace with your actual DB connection file

// Fetch only members (exclude staff and admin) from the database
$sql = "SELECT id, fullname, email, membership_type, created_at FROM users WHERE role = 'member'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('k.png'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 100px;
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.6);
        }

        .table-container {
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            margin-top: 50px;
        }

        .table {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
            font-size: 16px;
            padding: 12px;
        }

        .table td {
            text-align: center;
            padding: 12px;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-btn {
            margin-top: 20px;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.6);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Registered Members</h2>
    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Membership Type</th>
                    <th>Registered Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['membership_type']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No members found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
