<?php
// config.php - database connection
$host = 'localhost';
$db = 'fitzone_db'; // replace with your database name
$user = 'root';
$pass = ''; // default password for XAMPP is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Server-side validation
    $errors = [];

    if (empty($fullname)) $errors[] = "Full name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (strlen($username) < 4) $errors[] = "Username must be at least 4 characters";
    if (strlen($password) < 8 || !preg_match('/\d/', $password)) $errors[] = "Password must be at least 8 characters and contain a number";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";

    if (!isset($_POST['terms'])) $errors[] = "You must agree to the terms";

    if (count($errors) > 0) {
        // Redirect back with errors
        $errorStr = implode("|", $errors);
        header("Location: staffregistrationform.html?errors=" . urlencode($errorStr));
        exit();
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->execute([':username' => $username, ':email' => $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "Username or email already exists";
        $errorStr = implode("|", $errors);
        header("Location: staffregistrationform.html?errors=" . urlencode($errorStr));
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the 'users' table
    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, phone, username, password, role) 
                           VALUES (:fullname, :email, :phone, :username, :password, :role)");

    try {
        $stmt->execute([
            ':fullname' => $fullname,
            ':email' => $email,
            ':phone' => $phone,
            ':username' => $username,
            ':password' => $hashed_password,
            ':role' => 'staff'  // Specify the role as 'staff'
        ]);

        echo "<script>alert('Staff registration successful! Wait for admin approval.'); window.location.href='login.html';</script>";
    } catch (Exception $e) {
        die("Registration failed: " . $e->getMessage());
    }
}
?>
