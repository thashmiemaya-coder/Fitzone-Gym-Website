<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("
    SELECT mq.id, mq.subject, mq.message, mq.created_at, qr.reply, qr.replied_at 
    FROM membership_queries mq 
    LEFT JOIN query_replies qr ON mq.id = qr.query_id 
    WHERE mq.user_id = ?
    ORDER BY mq.created_at DESC
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Query Replies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
        .reply {
            background: #e9f7ef;
            padding: 10px;
            border-left: 5px solid #28a745;
        }
        .no-reply {
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Your Queries and Replies</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
    <?php
        $query_id = $row['id'];

        // Fetch staff reply
        $staff_stmt = $conn->prepare("SELECT reply, replied_at FROM query_replies WHERE query_id = ?");
        $staff_stmt->bind_param("i", $query_id);
        $staff_stmt->execute();
        $staff_result = $staff_stmt->get_result();
        $staff_reply = $staff_result->fetch_assoc();

        // Fetch admin reply
        $admin_stmt = $conn->prepare("SELECT reply, replied_at FROM replies WHERE query_id = ?");
        $admin_stmt->bind_param("i", $query_id);
        $admin_stmt->execute();
        $admin_result = $admin_stmt->get_result();
        $admin_reply = $admin_result->fetch_assoc();
    ?>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['subject']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
            <p><small><strong>Submitted on:</strong> <?= $row['created_at'] ?></small></p>

            <!-- Staff Reply -->
            <?php if ($staff_reply): ?>
                <div class="reply mt-3" style="border-left: 5px solid #28a745;">
                    <strong>Staff Reply:</strong><br>
                    <?= nl2br(htmlspecialchars($staff_reply['reply'])) ?>
                    <br><small><strong>Replied on:</strong> <?= $staff_reply['replied_at'] ?></small>
                </div>
            <?php else: ?>
                <p class="no-reply mt-3">No staff reply yet.</p>
            <?php endif; ?>

            <!-- Admin Reply -->
            <?php if ($admin_reply): ?>
                <div class="reply mt-3" style="border-left: 5px solid #007bff;">
                    <strong>Admin Reply:</strong><br>
                    <?= nl2br(htmlspecialchars($admin_reply['reply'])) ?>
                    <br><small><strong>Replied on:</strong> <?= $admin_reply['replied_at'] ?></small>
                </div>
            <?php else: ?>
                <p class="no-reply mt-3">No admin reply yet.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endwhile; ?>


</div>
</body>
</html>