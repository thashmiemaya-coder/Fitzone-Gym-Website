<?php
// config.php - database connection
$host = 'localhost';   // Database host
$db = 'fitzone_db';    // Database name
$user = 'root';        // Database username
$pass = '';            // Database password (empty for XAMPP default)
$charset = 'utf8mb4';  // Charset

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Creating a PDO instance for database connection
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle connection failure
    die("Connection failed: " . $e->getMessage());
}
?>
