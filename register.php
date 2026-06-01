<?php
// Include the database configuration file
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize the input values
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $membership = $_POST['membership'];

    // Validate input
    $errors = [];

    if (empty($fullname)) $errors[] = "Full name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (strlen($username) < 4) $errors[] = "Username must be at least 4 characters";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";
    if (!preg_match('/[0-9]/', $password)) $errors[] = "Password must contain at least one number";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";
    if (empty($membership)) $errors[] = "Membership type is required";
    if (!isset($_POST['terms'])) $errors[] = "You must agree to the terms";

    // Check if the username or email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username or email already exists";
        }
    }

    // Register user if no errors
    if (empty($errors)) {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // Insert the new user into the database
            $stmt = $pdo->prepare("INSERT INTO users 
                (username, password, email, fullname, role, membership_type, status) 
                VALUES (?, ?, ?, ?, 'member', ?, 'active')");
            $stmt->execute([
                $username, 
                $hashed_password, 
                $email, 
                $fullname, 
                $membership
            ]);

            // Redirect to a thank you page after successful registration
            header("Location: thankyou.html");
            exit();
        } catch (PDOException $e) {
            // Handle any errors that occur during the insert
            die("Registration failed: " . $e->getMessage());
        }
    }

    // If there are validation errors, return them to the form
    if (!empty($errors)) {
        $error_string = implode('|', $errors);
        header("Location: register.html?errors=" . urlencode($error_string));
        exit();
    }
}
?>
