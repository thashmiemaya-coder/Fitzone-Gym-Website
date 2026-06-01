<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

include('db_connection.php');

$error = '';
$success = '';
$showDashboardBtn = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = 'staff';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Auto-generate a username from the email (before the @ symbol)
    $username = explode('@', $email)[0];

    // Ensure username is unique by appending a number if needed
    $base_username = $username;
    $i = 1;
    while (true) {
        $check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_username->bind_param('s', $username);
        $check_username->execute();
        $check_username->store_result();
        if ($check_username->num_rows == 0) break;
        $username = $base_username . $i;
        $i++;
        $check_username->close();
    }

    // Check if the email is already in the database
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param('s', $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $error = 'Email is already in use.';
    } else {
        $sql = "INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $fullname, $email, $username, $password, $role);

        if ($stmt->execute()) {
            $success = 'Staff member added successfully.';
            $showDashboardBtn = true;
        } else {
            $error = 'Error occurred while adding staff.';
        }

        $stmt->close();
    }

    $check_email->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Staff Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('k.png');
            background-size: cover;
            background-position: center;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
        }

        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            max-width: 600px;
            margin: 50px auto;
        }

        .form-container input,
        .form-container button {
            margin-bottom: 20px;
        }

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

<nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ms-auto">
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Add Staff Member</h2>

    <div class="form-container">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?><br>
                <a href="admin_dashboard.php" class="btn btn-light mt-3">Go to Dashboard</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="adminadd_staff.php">
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            

            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-glow w-100">Add Staff</button>
        </form>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Admin Dashboard. All rights reserved.</p>
</div>

</body>
</html>
