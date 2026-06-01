<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "fitzone_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch contact queries from the database
$sql = "SELECT * FROM contact_us ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Contact Queries - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Background Image */
        .bg-image {
            background-image: url('k.png');
            background-size: cover;
            background-position: center;
            position: absolute;
            height: 100%;
            width: 100%;
            z-index: -1;
            filter: brightness(0.4);
        }

        /* Navbar Styling */
        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.75) !important;
        }

        .container-content {
            padding-top: 80px;
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

<div class="bg-image"></div>

<nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ms-auto">
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container container-content">
    <h2 class="text-center text-white mb-5">View Contact Queries</h2>

    <!-- Table for Contact Us Queries -->
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["name"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>" . $row["message"] . "</td>
                            <td>" . $row["created_at"] . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No contact queries found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="footer">
    <p>&copy; 2025 Admin Dashboard. All rights reserved.</p>
</div>

<?php
// Close the database connection
$conn->close();
?>

</body>
</html>
