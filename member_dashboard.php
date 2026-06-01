<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.html");
    exit();
}

// Include your DB connection file
include 'db_connection.php';

$user_id = $_SESSION['user_id'];

// Fetch member details
$query = $conn->prepare("SELECT fullname, email FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($name, $email);
$query->fetch();
$query->close();

// Handle query submission
// Handle query submission
$submitted = false;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($subject) || empty($message)) {
        $errors[] = "Subject and message cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO membership_queries (user_id, subject, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $subject, $message);

        if ($stmt->execute()) {
            echo "<script>
                alert('Your query has been submitted successfully!');
                window.location.href = 'member_dashboard.php';
            </script>";
            exit();
        } else {
            $errors[] = 'Something went wrong while submitting. Please try again.';
        }
        
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: url('k.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }

        .overlay {
    background-color: rgba(0, 0, 0, 0.5);
    min-height: 100vh;
    padding: 60px 20px;
}

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .profile-card {
            padding: 30px;
            text-align: center;
        }

        .profile-card img {
            width: 150px; /* Set the width of the profile picture */
            height: 150px; /* Set the height of the profile picture */
            border-radius: 50%; /* Make the image circular */
            object-fit: cover; /* Ensure the image covers the container without distortion */
            border: 5px solid #fff; /* Add a border around the profile picture */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); /* Add a shadow for depth */
        }

        .profile-card h4 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .profile-card p {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header {
    text-align: center;
    margin-bottom: 50px;
}

.header h1 {
    font-size: 40px;
    font-weight: 600;
    text-transform: uppercase;
}

.submit-query-header {
    font-size: 32px;
    font-weight: 700;
    color: #ffcc00;
    text-shadow: 2px 2px 8px rgba(255, 255, 255, 0.8);
    letter-spacing: 1px;
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

        .form-control, .btn {
            border-radius: 10px;
        }

        .alert-success {
            text-align: center;
            font-size: 18px;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border-radius: 8px;
            margin-bottom: 20px;
        }

       
        .footer {
            text-align: center;
            color: #bbb;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="overlay">
    <div class="container">

        <!-- Header Section -->
        <div class="header">
            <h1>Welcome To The Member Dashboard</h1>
        </div>

        <!-- Profile Section -->
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card profile-card">
                    <img src="d.jpg" alt="Profile Picture">
                    <h4><?= htmlspecialchars($name) ?></h4>
                    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                    <p><strong>Role:</strong> Member</p>
                </div>
            </div>

            <!-- Query Form Section -->
            <div class="col-md-7">
       <div class="card p-4">
        <h4 class="text-center mb-4"><b>Submit a Membership Query</b></h4>
        <form method="post" action="">
            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Query</button>

            </div>

<!-- Add Button Below the Form -->
<div class="text-center mt-4">
    <a href="view_my_replies.php" class="btn btn-warning btn-lg">View Query Replies</a>
</div>
        </form>
    </div>
</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2025 Member Dashboard. All rights reserved.</p>
        </div>
    </div>
</div>

</body>
</html> 