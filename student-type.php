<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment Type</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .enrollment-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            width: auto;
        }
        .enrollment-option {
            width: 300px;
            height: 60px;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="enrollment-container">
        <h2 class="mb-4">Select Your Enrollment Type</h2>
        
        <div class="btn-container">
            <button class="btn btn-primary enrollment-option" onclick="redirect('admission-form.php?type=new_transferee')">
                New / Transferee Student
            </button>
            <button class="btn btn-secondary enrollment-option" onclick="redirect('admission-form.php?type=old')">
                Old Student
            </button>
        </div>
    </div>

    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
