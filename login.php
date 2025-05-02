<?php
session_start();

include 'databases/db_connection.php'; // Include database connection


$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password']; 

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['user_type'] = $user['user_type'];  // This should set the 'user_type' as 'sub-admin', 'admin', or 'cashier'


            // Redirect based on role
            if ($user['user_type'] == 'admin') {
                header("Location: admin-dashboard.php");
                exit();
            } elseif ($user['user_type'] == 'sub-admin') {
                header("Location: sub-admin-dashboard.php");
                exit();
            } elseif ($user['user_type'] == 'cashier') {
                header("Location: cashier-dashboard.php");
                exit();
            }
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SJBPS - LogIn </title>
    <link rel="icon" type="image/png" href="assets/main/logo/st-johns-logo.png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #d9d9d9;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        
        .login-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }
        
        .title {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            width: 100%;
            border-radius: 25px;
            padding: 10px;
            font-size: 18px;
            margin-top: 20px;
        }
        
        .form-control {
            padding: 10px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            text-align: center;
        }
        .logo-image {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
    </style>

        <!-- Fetch the logo from the database and display it in the navbar and form -->
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("databases/fetch_logo.php")
                .then(response => response.json())
                .then(data => {
                    let loginLogo = document.getElementById("loginLogo");

                    if (data.status === "success" && data.image) {
                        loginLogo.src = data.image; // Load logo from database
                    } else {
                        console.error("Error:", data.message);
                        loginLogo.src = "assets/homepage_images/logo/placeholder.png"; // Default placeholder
                    }
                })
                .catch(error => console.error("Error fetching logo:", error));
        });
    </script>
</head>
<body>
    <div class="login-container">
        <div class="p-3" style="text-align: center;">
            <img id="loginLogo" src="assets/homepage_images/logo/placeholder.png" alt="School Logo" class="logo-image me-2" style="max-width: 150px;">

        </div>
        <h2 class="title">Online Enrollment System</h2>
        

        
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Log In</button>

            <div class="text-center mt-4">
                <a href="homepage.php" class="text-primary" style="text-decoration: none; font-size: 14px; font-weight: normal;">Go to Homepage</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
