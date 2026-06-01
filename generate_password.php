<?php
session_start();

// Specify the password you want to hash
$password = "admin123"; // Replace with the password you want

// Generate the hashed password using bcrypt (default in password_hash)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Output the hashed password so you can use it
echo "Hashed Password: " . $hashed_password;
?>
