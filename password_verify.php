<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'fitzone_db';
$username = 'root';
$password = ''; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user record from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Check if the user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
            exit();
        } elseif ($user['role'] === 'staff') {
            header("Location: staff_dashboard.php");
            exit();
        } else {
            header("Location: member_dashboard.php");
            exit();
        }
    } else {
        // If login fails, redirect with an error message
        header("Location: login.php?error=Invalid username or password!");
        exit();
    }
}
?>
