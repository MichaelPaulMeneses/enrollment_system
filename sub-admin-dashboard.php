<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'sub-admin') {
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
    <title>Sub-Admin - SJBPS Dashboard</title>
    <link rel="icon" type="image/png" href="assets/main/logo/st-johns-logo.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-blue: #3498db;
            --bg-light-gray: #f0f2f5;
            --sidebar-bg: #f8f9fa;
            --sidebar-active-bg: #f0f0f0;
            --sidebar-hover-bg: #e9ecef;
        }
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .logo-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        .navbar {
            background-color: var(--primary-blue);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-weight: bold;
        }
        .navbar-brand img {
            margin-right: 10px;
            border: 2px solid white;
        }
        .sidebar {
            background-color: var(--sidebar-bg);
            min-height: calc(100vh - 56px);
            border-right: 1px solid #dee2e6;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .sidebar .nav-link {
            color: #333;
            border-radius: 4px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link.active {
            background-color: var(--sidebar-active-bg);
            font-weight: bold;
            color: var(--primary-blue);
        }
        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover-bg);
            transform: translateX(5px);
        }
        .card-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .card-container:hover {
            transform: scale(1.03);
        }
        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }
        a.text-decoration-none {
            color: inherit;
            text-decoration: none;
        }
        .metric-card {
            color: white;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 6px;
        }
        .metric-card.red { background-color: #e74c3c; }       /* Red */
        .metric-card.orange { background-color: #f39c12; }    /* Orange */
        .metric-card.green { background-color: #2ecc71; }     /* Green */
        .metric-card.blue { background-color: #3498db; }      /* Blue */
        .metric-card.navy { background-color: #34495e; }      /* Navy */
        .metric-card.yellow { background-color: #f1c40f; }    /* Yellow */
        .metric-card.purple { background-color: #9b59b6; }    /* Purple */
        .metric-card.cyan { background-color: #1abc9c; }      /* Cyan */
        .metric-card.pink { background-color: #e91e63; }      /* Pink */
        .metric-card.brown { background-color:  #904125; }     /* Brown */
        .metric-card.grey { background-color: #7f8c8d; }      /* Grey */

        
        .metric-value {
            font-size: 24px;
            font-weight: bold;
        }
        .chart-container {
            width: 100%;
            max-width: 1200px; /* Adjust the max width as per your design */
            margin: 0 auto; /* Center align */

            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        @media (max-width: 767px) {
            .sidebar {
                display: none;
            }
            .chart-container h5 {
                font-size: 1.2rem; /* Adjust font size for smaller screens */
            }

            .chart-container canvas {
                height: 250px; /* Adjust the height for small screens */
            }

        }
    </style>
    
    <!-- Fetch the name of the User -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const adminFirstName = "<?php echo htmlspecialchars($adminFirstName); ?>";
            const adminLastName = "<?php echo htmlspecialchars($adminLastName); ?>";
            const welcomeMessage = `WELCOME! Sub-Admin ${adminFirstName} ${adminLastName}`;
            document.getElementById('adminWelcomeMessage').textContent = welcomeMessage;
        });
    </script>

    <!-- Fetch the logo from the database and display it in the navbar -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("databases/fetch_logo.php")
                .then(response => response.json())
                .then(data => {
                    let navLogo = document.getElementById("navLogo");
                    if (data.status === "success" && data.image) {
                        navLogo.src = data.image; // Load logo from database
                    } else {
                        console.error("Error:", data.message);
                        navLogo.src = "assets/homepage_images/logo/placeholder.png"; // Default placeholder
                    }
                })
                .catch(error => console.error("Error fetching logo:", error));
        });
    </script>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img id="navLogo" src="assets/homepage_images/logo/placeholder.png" alt="Profile" class="logo-image me-2">
                <a class="navbar-brand" href="sub-admin-dashboard.php" id="adminWelcomeMessage">WELCOME! Sub-Admin</a>
            </div>
            <div class="ms-auto">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt me-2"></i>Log Out
                    </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-danger">Log Out</a>
                </div>
            </div>
        </div>
    </div>
    

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="sub-admin-dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-application-for-review.php">
                            <i class="fas fa-file-alt me-2"></i>Applications for Review
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-approved-application.php">
                            <i class="fas fa-check-circle me-2"></i>Approved Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-declined-application.php">
                            <i class="fas fa-times-circle me-2"></i>Declined Applications
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-interviews.php">
                            <i class="fas fa-calendar-check me-2"></i>Interviews
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-declined-interviews.php">
                            <i class="fas fa-times-circle me-2"></i>Declined Interviews
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-student-for-assignment.php">
                            <i class="fas fa-tasks me-2"></i>For Assignment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-all-enrollees.php">
                            <i class="fas fa-users me-2"></i>All Enrollees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-grade-section.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Grade-Section
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-curriculum.php">
                            <i class="fas fa-book-open me-2"></i>Curriculum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-homepage-editor.php">
                            <i class="fas fa-edit me-2"></i>Home Page Editor
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-md-4 pt-3">
                <div class="row">
                    <!-- First Row - 4 Cards -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="sub-admin-application-for-review.php" class="text-decoration-none">
                            <div class="card-container">
                                <div class="card-title">Applications For Review</div>
                                <div class="metric-card red">
                                    <div class="metric-value" id="applicationReviewCount">...</div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="sub-admin-interviews.php" class="text-decoration-none">
                            <div class="card-container">
                                <div class="card-title">Enrollees For Interview</div>
                                <div class="metric-card orange">
                                    <div class="metric-value" id="applicationInterviewCount">...</div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <a class="text-decoration-none">
                            <div class="card-container">
                                <div class="card-title">Applications For Payment</div>
                                <div class="metric-card blue">
                                    <div class="metric-value" id="applicationPaymentCount">...</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="sub-admin-interviews.php" class="text-decoration-none">
                            <div class="card-container">
                                <div class="card-title">Applications For Assignment</div>
                                <div class="metric-card navy">
                                    <div class="metric-value" id="applicationAssignmentCount">...</div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Second Row - 4 Cards -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="sub-admin-all-enrollees.php" class="text-decoration-none">
                            <div class="card-container">
                                <div class="card-title">Total Enrollees</div>
                                <div class="metric-card green">
                                    <div class="metric-value" id="totalEnrolleesCount">...</div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <a class="text-decoration-none">
                            <div class="card-container">
                                <div class="card-title">Active School Year</div>
                                <div class="metric-card purple">
                                    <div class="metric-value" id="activeSchoolYear">...</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Chart: Number of Enrolled Students per Section -->
                    <div class="col-12">
                        <div class="chart-container">
                            <h5 class="mb-3">Number of Students per Grade Level</h5>
                            <div style="position: relative; width: 100%; height: 300px;">
                                <canvas id="gradeLevelChart" style="width: 100%; height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart: Number of Applicants by Enrollment Status -->
                    <div class="col-12 mt-4">
                        <div class="chart-container">
                            <h5 class="mb-3">Number of Applicants by Enrollment Status</h5>
                            <div style="position: relative; width: 100%; height: 300px;">
                                <canvas id="enrollmentStatusChart" style="width: 100%; height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    

    <!-- Dashbaord Cards -->
    <script>
        function fetchActiveSchoolYear(callback) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "databases/active_school_years.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Needed for POST requests

            xhr.onload = function () {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);

                    if (data.length > 0) {
                        console.log("Active School Year:", data[0]);

                        if (callback) callback(data[0]);
                    } else {
                        console.warn("No active school year found.");
                    }
                } else {
                    console.error("Failed to fetch school year.");
                }
            };

            xhr.send("request=active"); // Send some dummy or identifying data if needed
        }

        // Example usage:
        fetchActiveSchoolYear(function(activeSY) {

            console.log("Now you can use this ID:", activeSY.school_year_id);

            document.getElementById('activeSchoolYear').textContent = activeSY.school_year;

            fetch('databases/count_for_review.php', {
                    method: 'POST', // Use POST request
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Set content type for POST
                    },
                    body: 'school_year_id=' + encodeURIComponent(activeSY.school_year_id) // Send the activeSY.id in the body
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('applicationReviewCount').textContent = data;
                    console.log("For Review:", data);
                })
                .catch(error => {
                    console.error('Error fetching application for review count:', error);
                });

            // Count applications for intereview
            fetch('databases/count_for_interview.php', {
                    method: 'POST', // Use POST request
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Set content type for POST
                    },
                    body: 'school_year_id=' + encodeURIComponent(activeSY.school_year_id) // Send the activeSY.id in the body
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('applicationInterviewCount').textContent = data;
                    console.log("For Interview:", data);
                })
                .catch(error => {
                    console.error('Error fetching application for interview count:', error);
                });
            
            // Count applications for payment
            fetch('databases/count_for_payment.php', {
                    method: 'POST', // Use POST request
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Set content type for POST
                    },
                    body: 'school_year_id=' + encodeURIComponent(activeSY.school_year_id) // Send the activeSY.id in the body
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('applicationPaymentCount').textContent = data;
                    console.log("For Payment:", data);
                })
                .catch(error => {
                    console.error('Error fetching application for payment count:', error);
                });

            // Count applications for assignment
            fetch('databases/count_for_assignment.php', {
                    method: 'POST', // Use POST request
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Set content type for POST
                    },
                    body: 'school_year_id=' + encodeURIComponent(activeSY.school_year_id) // Send the activeSY.id in the body
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('applicationAssignmentCount').textContent = data;
                    console.log("For Assignment:", data);
                })
                .catch(error => {
                    console.error('Error fetching application for assignment count:', error);
                });

            // Count applications for all enrollees
            fetch('databases/count_for_all_enrollees.php', {
                    method: 'POST', // Use POST request
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Set content type for POST
                    },
                    body: 'school_year_id=' + encodeURIComponent(activeSY.school_year_id) // Send the activeSY.id in the body
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('totalEnrolleesCount').textContent = data;
                    console.log("All Enrollees:", data);
                })
                .catch(error => {
                    console.error('Error fetching all enrollees count:', error);
                });
            

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('gradeLevelChart').getContext('2d');
            const ctxStatus = document.getElementById('enrollmentStatusChart').getContext('2d');

            // Fetch the active school year ID first
            fetchActiveSchoolYear(function(activeSY) {
                const schoolYearId = activeSY.school_year_id; // Get the school year ID
                console.log("nandito", activeSY.school_year_id);

                // Fetch the data for the chart, using POST and sending the school_year_id
                fetch('databases/fetch_students_per_grade_level.php', {
                    method: 'POST', // Use POST request
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Set content type for POST
                    },
                    body: 'school_year_id=' + encodeURIComponent(schoolYearId) // Send the school_year_id in the body
                })
                .then(response => response.json())
                .then(data => {
                    const chartData = {
                        labels: data.grades, // Labels (e.g., Grade levels) from the fetched data
                        datasets: [{
                            label: 'Number of Students',
                            data: data.studentCounts, // Student counts per grade level from the fetched data
                            backgroundColor: [
                                'rgba(52, 152, 219, 0.7)', 'rgba(46, 204, 113, 0.7)', 
                                'rgba(241, 196, 15, 0.7)', 'rgba(231, 76, 60, 0.7)', 
                                'rgba(155, 89, 182, 0.7)', 'rgba(52, 73, 94, 0.7)', 
                                'rgba(26, 188, 156, 0.7)', 'rgba(22, 160, 133, 0.7)', 
                                'rgba(39, 174, 96, 0.7)', 'rgba(41, 128, 185, 0.7)', 
                                'rgba(142, 68, 173, 0.7)', 'rgba(44, 62, 80, 0.7)', 
                                'rgba(243, 156, 18, 0.7)', 'rgba(192, 57, 43, 0.7)'
                            ],
                            borderColor: [
                                'rgba(52, 152, 219, 1)', 'rgba(46, 204, 113, 1)', 
                                'rgba(241, 196, 15, 1)', 'rgba(231, 76, 60, 1)', 
                                'rgba(155, 89, 182, 1)', 'rgba(52, 73, 94, 1)', 
                                'rgba(26, 188, 156, 1)', 'rgba(22, 160, 133, 1)', 
                                'rgba(39, 174, 96, 1)', 'rgba(41, 128, 185, 1)', 
                                'rgba(142, 68, 173, 1)', 'rgba(44, 62, 80, 1)', 
                                'rgba(243, 156, 18, 1)', 'rgba(192, 57, 43, 1)'
                            ],
                            borderWidth: 1
                        }]
                    };

                    const options = {
                        responsive: true, // Make chart responsive
                        maintainAspectRatio: false, // Allow the chart to adapt its size
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Grade Level Distribution'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true, // Ensure the y-axis starts from 0
                                ticks: {
                                    stepSize: 1, // Adjust the step size for better readability
                                }
                            }
                        }
                    };

                    // Create the chart with Chart.js
                    new Chart(ctx, {
                        type: 'bar', // Type of chart
                        data: chartData, // Data for the chart
                        options: options // Options for the chart
                    });
                })
                .catch(error => console.error('Error fetching chart data:', error)); // Handle any errors


                fetch('databases/fetch_enrollment_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'school_year_id=' + encodeURIComponent(activeSY.school_year_id)
                })
                .then(response => response.json())
                .then(data => {
                    const chartData = {
                        labels: data.statuses,
                        datasets: [{
                            label: 'Applicants',
                            data: data.counts,
                            backgroundColor: [
                                'rgba(52, 152, 219, 0.7)',  // Reviewing Application
                                'rgba(231, 76, 60, 0.7)',   // Application Declined
                                'rgba(241, 196, 15, 0.7)',  // For Interview
                                'rgba(155, 89, 182, 0.7)',  // Interview Failed
                                'rgba(230, 126, 34, 0.7)',  // For Payment
                                'rgba(26, 188, 156, 0.7)',  // For Assignment
                                'rgba(39, 174, 96, 0.7)'    // Fully Enrolled
                            ],
                            borderColor: [
                                'rgba(52, 152, 219, 1)',
                                'rgba(231, 76, 60, 1)',
                                'rgba(241, 196, 15, 1)',
                                'rgba(155, 89, 182, 1)',
                                'rgba(230, 126, 34, 1)',
                                'rgba(26, 188, 156, 1)',
                                'rgba(39, 174, 96, 1)'
                            ],
                            borderWidth: 1
                        }]
                    };

                    const options = {
                        responsive: true, // Make chart responsive
                        maintainAspectRatio: false, // Allow the chart to adapt its size
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Enrollment Status Breakdown'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true, // Ensure the y-axis starts from 0
                                ticks: {
                                    stepSize: 1, // Adjust the step size for better readability
                                }
                            }
                        }
                    };

                    new Chart(ctxStatus, {
                        type: 'bar',
                        data: chartData,
                        options: options
                    });
                })
                .catch(error => console.error('Error fetching enrollment status data:', error));

            });
        });
    </script>


</body>
</html>
