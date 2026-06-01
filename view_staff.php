<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, fullname, email, username, phone, created_at FROM users WHERE role = 'staff'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Staff Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Background Image */
        body {
            background: url('k.png') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            
            backdrop-filter: blur(5px);
            padding: 60px 30px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        }

        .container {
            margin-top: 80px;
        }

        .card-title {
            font-weight: bold;
            color: #333;
        }

        .table thead {
            background-color: #495057;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-back {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 25px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-back:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            box-shadow: 0 0 15px rgba(106, 17, 203, 0.5);
        }

        h3 {
            color: #212529;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

    </style>
</head>
<body>

<div class="container">
    <div class="overlay">
        <h3 class="text-center mb-4">Staff Members List</h3>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['fullname']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No staff members found.</p>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
