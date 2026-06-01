<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch staff data
$sql = "SELECT * FROM staff WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["staff_id"]);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="staffregistration.css">

    <header>
    <h1>FitZone Fitness Center</h1>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="Packages.html">Packages</a></li>
            <li><a href="ContactUs.html">Contact Us</a></li>
            <li><a href="blog.html">Blog</a></li>
            <li><a href="register.html" class = "btn">Register</a></li>
</header>

    <style>
        .dashboard {
            max-width: 1000px;
            margin: 20px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-section, .actions-section {
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .actions-section a {
            display: block;
            padding: 10px;
            margin: 10px 0;
            background: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Staff Portal</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard">
        <div class="welcome-message">
            <h2>Welcome, <?php echo htmlspecialchars($staff["fullname"]); ?></h2>
            <p>Position: <?php echo htmlspecialchars($staff["position"]); ?></p>
        </div>

        <div class="profile-section">
            <h3>Your Profile</h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($staff["email"]); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($staff["phone"]); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($staff["status"]); ?></p>
        </div>

        <div class="actions-section">
            <h3>Quick Actions</h3>
            <a href="update_profile.php">Update Profile</a>
            <a href="request_leave.php">Request Leave</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 Your Company. All rights reserved.</p>
    </footer>
</body>
</html>