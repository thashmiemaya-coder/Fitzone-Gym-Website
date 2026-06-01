<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

$query = "SELECT mq.id, u.fullname, mq.subject, mq.message
          FROM membership_queries mq
          JOIN users u ON mq.user_id = u.id
          ORDER BY mq.id DESC";  // Sorting by query ID or any other available column

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('k.png'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            color: #333;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent white background */
            border-radius: 10px;
            padding: 40px;
            margin-top: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #000; /* Black heading */
            margin-bottom: 30px;
            font-weight: 600;
        }

        .table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            text-align: center;
            padding: 15px;
            vertical-align: middle;
        }

        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .table td {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e6f7ff;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .back-btn {
            margin-top: 20px;
            display: block;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Membership Queries</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Member</th>
                <th>Subject</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="back-btn">
        <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
