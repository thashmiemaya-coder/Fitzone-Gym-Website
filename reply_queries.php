<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

$staff_id = $_SESSION['user_id'];

// Handle reply
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query_id = $_POST['query_id'];
    $reply = trim($_POST['reply']);

    if (!empty($reply)) {
        $stmt = $conn->prepare("INSERT INTO query_replies (query_id, staff_id, reply) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $query_id, $staff_id, $reply);
        $stmt->execute();
        $stmt->close();

        // Set success message for alert box
        $success_message = "Your reply has been successfully sent!";
    }
}

// Fetch all queries
$queries = $conn->query("SELECT mq.id, u.fullname, mq.subject, mq.message
                         FROM membership_queries mq
                         JOIN users u ON mq.user_id = u.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('k.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
        }

        .container {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            padding: 40px;
            max-width: 800px;
            margin: 50px auto;
        }

        h2 {
            text-align: center;
            font-size: 36px;
            color: #ffcc00;
            margin-bottom: 30px;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 0.7);
        }

        .card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .card h5 {
            color: #00aaff;
            font-size: 24px;
        }

        .card p {
            font-size: 16px;
        }

        .form-control {
            border-radius: 10px;
            background-color: #f4f4f4;
            color: #333;
        }

        .btn-primary {
            background: #00aaff;
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #0077cc;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .footer {
            text-align: center;
            color: #bbb;
            margin-top: 40px;
        }

        .footer p {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reply to Queries</h2>

    <!-- Display Success Message as Alert -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <?php while ($row = $queries->fetch_assoc()): ?>
        <div class="card">
            <div class="card-body">
                <h5><?= htmlspecialchars($row['subject']) ?> 
                    <small class="text-muted">from <?= htmlspecialchars($row['fullname']) ?></small>
                </h5>
                <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>

                <form method="post">
                    <input type="hidden" name="query_id" value="<?= $row['id'] ?>">
                    <textarea name="reply" class="form-control mb-2" rows="3" placeholder="Type your reply here" required></textarea>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>

    <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<div class="footer">
    <p>&copy; 2025 Staff Dashboard. All rights reserved.</p>
</div>

<!-- Alert Box for Successful Reply -->
<script>
    // Check if there's a success message and show an alert box
    <?php if (isset($success_message)): ?>
        alert('<?= htmlspecialchars($success_message) ?>');
    <?php endif; ?>
</script>

</body>
</html>
