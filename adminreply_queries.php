<?php
session_start();
include('config1.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query_id = $_POST['query_id'];
    $staff_id = $_SESSION['user_id'];
    $reply = mysqli_real_escape_string($conn, $_POST['reply']);

    $sql = "INSERT INTO replies (query_id, staff_id, reply, replied_at) 
            VALUES ('$query_id', '$staff_id', '$reply', NOW())";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Your reply was sent successfully!";
    } else {
        $success_message = "Error sending reply: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Reply to Query</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            
        }

        /* Background Image */
        .bg-image {
            background-image: url('k.png'); /* Replace with your own background image URL */
            background-size: cover;
            background-position: center;
            position: absolute;
            height: 100%;
            width: 100%;
            z-index: -1;
            filter: brightness(0.6);
        }

        .container-content {
            padding-top: 100px;
            z-index: 1;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.75) !important;
        }

        .dashboard-card {
           
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            transition: 0.3s;
            backdrop-filter: blur(5px);
            padding: 30px;
        }

        .dashboard-card:hover {
            transform: scale(1.05);
        }

        .welcome {
            color: #fff;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
        }

        .btn-glow {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-glow:hover {
            background-color: #0056b3;
            box-shadow: 0 0 10px #007bff;
        }

        .alert {
            font-size: 1.1em;
        }
    </style>
</head>
<body>

<div class="bg-image"></div>

<nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ms-auto">
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container container-content">
    <h2 class="text-center welcome mb-5">Add Reply to Query</h2>

    <div class="row g-4 justify-content-center">
        <div class="col-md-6">
            <div class="card dashboard-card">
                <h5 class="card-title text-danger mb-3">Add Reply</h5>

                <!-- Display Success Message in Alert Box -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-info"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="query_id" class="form-label">Query ID</label>
                        <input type="number" class="form-control" id="query_id" name="query_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="reply" class="form-label">Your Reply</label>
                        <textarea class="form-control" id="reply" name="reply" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-glow">Submit Reply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Optional Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
