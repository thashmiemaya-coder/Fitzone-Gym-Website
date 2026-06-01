<?php
session_start();

// Include database connection
require 'config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Server-side validation
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username and password are required!'); window.location.href='login.html';</script>";
        exit();
    }

    // Query to check for the user in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and password matches
    if ($user && password_verify($password, $user['password'])) {

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
            exit();
        } elseif ($user['role'] == 'staff') {
            header("Location: staff_dashboard.php");
            exit();
        } elseif ($user['role'] == 'member') {
            header("Location: member_dashboard.php");
            exit();
        }

    } else {
        echo "<script>alert('Invalid username or password!'); window.location.href='login.html';</script>";
        exit();
    }
}
?>
