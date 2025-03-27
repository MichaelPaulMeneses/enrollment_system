<?php
session_start();

include 'databases/db_connection.php'; // Include database connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_type = $_POST['user_type'];

    if ($password != $password_repeat) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error_message = "Username or email already exists.";
        } else {
            // Insert into the users table
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email, first_name, last_name, user_type)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $username, $hashed_password, $email, $first_name, $last_name, $user_type);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Registration successful!";
                header("Location: login.php");
                exit();
            } else {
                $error_message = "There was an error with the registration.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Enrollment System - Registration</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .register-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
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
    </style>
</head>
<body>
    <div class="register-container">
        <h2 class="title">Registration Form</h2>

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
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="password_repeat" class="form-label">Repeat Password</label>
                <input type="password" class="form-control" id="password_repeat" name="password_repeat" required>
            </div>

            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>

            <div class="mb-3">
                <label for="user_type" class="form-label">User Type</label>
                <select class="form-control" id="user_type" name="user_type" required>
                    <option value="admin">Admin</option>
                    <option value="sub-admin">Sub-Admin</option>
                    <option value="cashier">Cashier</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
