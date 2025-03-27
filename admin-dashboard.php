<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Retrieve admin details from session
$adminFirstName = $_SESSION['first_name'];
$adminLastName = $_SESSION['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #d9d9d9;
            color: #333;
        }
        .header {
            background-color: #0099ff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo {
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo img {
            width: 30px;
            height: 30px;
        }
        .nav-links a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <div class="logo">
                <img src="images/logo/st-johns-logo.png" alt="School Logo">
            </div>
            <h4 class="m-0">WELCOME! ADMIN <?php echo htmlspecialchars($adminFirstName . ' ' . $adminLastName); ?></h4>
        </div>
        <div class="nav-links">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a> <!-- Fixed Logout Link -->
        </div>
    </div>
</body>
</html>
