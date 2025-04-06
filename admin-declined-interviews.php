<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Retrieve admin details from session
$adminUserId = $_SESSION['user_id'];
$adminFirstName = $_SESSION['first_name'];
$adminLastName = $_SESSION['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SJBPS Declined Interviews</title>
    <link rel="icon" type="image/png" href="assets/main/logo/st-johns-logo.png">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        .main-content {
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .table {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background-color: #f8f9fa;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        .review-btn {
            background-color: #2ecc71;
            border-color: #2ecc71;
            transition: all 0.3s ease;
        }
        .review-btn:hover {
            transform: scale(1.05);
            background-color: #27ae60;
        }
        .empty-table-message {
            color: #6c757d;
        }
        .input-group .form-control:focus,
        .input-group .btn:focus {
            box-shadow: none;
            border-color: var(--primary-blue);
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
        }
    </style>
    <!-- Fetch the name of the User -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const adminFirstName = "<?php echo htmlspecialchars($adminFirstName); ?>";
            const adminLastName = "<?php echo htmlspecialchars($adminLastName); ?>";
            const welcomeMessage = `WELCOME! Admin ${adminFirstName} ${adminLastName}`;
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
                <a class="navbar-brand" href="admin-dashboard.php" id="adminWelcomeMessage">WELCOME! Admin</a>
            </div>
            <div class="ms-auto">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin-dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="admin-dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-application-for-review.php">
                            <i class="fas fa-file-alt me-2"></i>Applications for Review
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-approved-application.php">
                            <i class="fas fa-check-circle me-2"></i>Approved Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-declined-application.php">
                            <i class="fas fa-times-circle me-2"></i>Declined Applications
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="admin-interviews.php">
                            <i class="fas fa-calendar-check me-2"></i>Interviews
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-declined-interviews.php">
                            <i class="fas fa-times-circle me-2"></i>Declined Interviews
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-payment-transaction.php">
                            <i class="fas fa-money-check-alt me-2"></i>Payment Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-transaction-history.php">
                            <i class="fas fa-history me-2"></i>Transactions History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-student-for-assignment.php">
                            <i class="fas fa-tasks me-2"></i>For Assignment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-all-enrollees.php">
                            <i class="fas fa-users me-2"></i>All Enrollees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-grade-section.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Grade-Section
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-curriculum.php">
                            <i class="fas fa-book-open me-2"></i>Curriculum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-homepage-editor.php">
                            <i class="fas fa-edit me-2"></i>Home Page Editor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user-cog me-2"></i>Users
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Declined Interviews</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Advanced Filters
                    </button>
                </div>
                
                <!-- Search Bar -->
                <div class="search-container d-flex justify-content-end">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search Interviews" aria-label="Search">
                        <button class="btn btn-outline-secondary" type="button" id="clearBtn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>


                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="interviewsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Type of Student</th>
                                <th>Grade Applying For</th>
                                <th>Interview Date</th>
                                <th>Interview Time</th>
                                <th>School Year</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="declinedInterviewsTable">
                            <!-- Data will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    
    <!-- Advanced Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Advanced Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Grade Applying For Filter -->
                    <div class="mb-3">
                        <label class="form-label">Grade Applying For</label>
                        <select id="gradeApplyingFilter" class="form-select">
                            <option value="">Select Grade Levels</option>
                            <option value="Prekindergarten">Prekindergarten</option>
                            <option value="Kindergarten">Kindergarten</option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                            <option value="Grade 4">Grade 4</option>
                            <option value="Grade 5">Grade 5</option>
                            <option value="Grade 6">Grade 6</option>
                            <option value="Grade 7">Grade 7</option>
                            <option value="Grade 8">Grade 8</option>
                            <option value="Grade 9">Grade 9</option>
                            <option value="Grade 10">Grade 10</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>
                    
                    <!-- Interview Date Range Filter -->
                    <div class="mb-3">
                        <label class="form-label">Interview Date Range</label>
                        <select id="interviewDateRange" class="form-select">
                            <option value="">Select Date Range</option>
                            <option value="today">Today</option>
                            <option value="1_week">1 Week</option>
                            <option value="2_weeks">2 Weeks</option>
                            <option value="3_weeks">3 Weeks</option>
                            <option value="1_month">1 Month</option>
                        </select>
                    </div>

                    <!-- School Year Filter -->
                    <div class="mb-3">
                        <label class="form-label">School Year</label>
                        <select id="schoolYearFilter" class="form-select">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="applyFiltersBtn" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
             // Fetch interviews when the page loads
            fetchInterviews();
            // Fetch school years for the filter dropdown
            fetchSchoolYears();
            
            // search input and clear button
            const searchInput = document.getElementById("searchInput");
            const clearBtn = document.getElementById("clearBtn");
            const tableBody = document.querySelector("tbody");
            
            searchInput.addEventListener("input", function () {
                const searchValue = this.value.toLowerCase().trim();
                let visibleRows = 0;

                document.querySelectorAll("tbody tr").forEach(row => {
                    const rowText = row.innerText.toLowerCase();
                    if (rowText.includes(searchValue)) {
                        row.style.display = "";
                        visibleRows++;
                    } else {
                        row.style.display = "none";
                    }
                });

            });

            clearBtn.addEventListener("click", function () {
                searchInput.value = "";
                document.querySelectorAll("tbody tr").forEach(row => row.style.display = "");
            });
        });

        // Fetch Interviews for review from the database
        function fetchInterviews() {
            fetch("databases/fetch_declined_interview_data.php")  // Your PHP script
                .then(response => response.json())  // Parse the JSON response
                .then(data => {
                    let declinedInterviewsTable = document.querySelector("#declinedInterviewsTable");  // Select the tbody element
                    declinedInterviewsTable.innerHTML = "";  // Clear existing rows

                    // If no data is returned (empty result)
                    if (data.length === 0) {
                        declinedInterviewsTable.innerHTML = `
                            <tr>
                                <td colspan="8" class="text-center py-5 empty-table-message">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No interviews at this time</p>
                                </td>
                            </tr>
                        `;
                    } else {
                        // If there are Interviews, populate the table
                        data.forEach((interview, index) => {
                            let row = document.createElement("tr");
                            row.classList.add("interview-row");
                            row.setAttribute("data-id", interview.student_id);

                            row.innerHTML += `
                                <td>${index + 1}</td>
                                <td>${interview.student_name}</td>
                                <td>${interview.type_of_student}</td>
                                <td>${interview.grade_applying_for}</td>
                                <td>${interview.appointment_date}</td>
                                <td>${interview.appointment_time}</td>
                                <td>${interview.school_year}</td>
                                <td>${interview.enrollment_status}</td>
                            `;
                            declinedInterviewsTable.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error("Error fetching data:", error));  // Error handling
        }

        // Fetch school years for the filter dropdown Modal
        function fetchSchoolYears() {
            fetch("databases/school_years.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        const schoolYearDropdown = document.getElementById("schoolYearFilter");
                        schoolYearDropdown.innerHTML = '<option value="">All School Years</option>';

                        data.schoolYears.forEach(year => {
                            const option = document.createElement("option");
                            option.value = year.school_year; // Use the readable school_year for matching
                            option.textContent = year.school_year;
                            schoolYearDropdown.appendChild(option);
                        });
                    } else {
                        console.error("Failed to fetch school years.");
                    }
                })
                .catch(error => console.error("Error fetching school years:", error));
        }

        // Date range calculation function
        function getDateRange(range) {
            const today = new Date();

            let startDate = new Date(today);
            let endDate = new Date(today);

            switch (range) {
                case "today":
                    endDate.setDate(today.getDate());   // Set endDate to today
                    console.log("Today:", today.getDate());
                    break;
                case "1_week":
                    endDate.setDate(today.getDate() + 7); // Add 7 days to today for a 1-week range
                    console.log("1_week:", today.getDate() + 7);
                    break;
                case "2_weeks":
                    endDate.setDate(today.getDate() + 14); // Add 14 days to today for a 2-week range
                    console.log("2_weeks:", today.getDate() + 14);
                    break;
                case "3_weeks":
                    endDate.setDate(today.getDate() + 21); // Add 21 days to today for a 3-week range
                    console.log("3_weeks:", today.getDate() + 21);
                    break;
                case "1_month":
                    endDate.setMonth(today.getMonth() + 1); // Add 1 month to today for a 1-month range
                    console.log("1_month:", today.getMonth() + 1);
                    break;
                default:
                    return { startDate: null, endDate: null };
            }
            return { startDate, endDate };
        }


        // Apply Filters on Click
        document.getElementById("applyFiltersBtn").addEventListener("click", function () {
            filterTable();  // Call the filterTable function when the "Apply Filters" button is clicked
            $('#filterModal').modal('hide');  // Close the modal after applying filters
        });

        // Filter Method
        function filterTable() {
            let gradeApplying = document.getElementById("gradeApplyingFilter").value.toLowerCase();
            let schoolYear = document.getElementById("schoolYearFilter").value.toLowerCase();
            let dateRange = document.getElementById("interviewDateRange").value;
            let { startDate, endDate } = getDateRange(dateRange);

            document.querySelectorAll("tbody tr").forEach(row => {
                let gradeMatch = gradeApplying === "" || row.cells[3].textContent.toLowerCase() === gradeApplying;
                let yearMatch = schoolYear === "" || row.cells[6].textContent.toLowerCase() === schoolYear;

                // Parse the interview date from the table cell
                let interviewDateText = row.cells[4].textContent.trim(); // assuming interview date is in cell 5
                let interviewDate = new Date(interviewDateText);

                // Check if interviewDate is within the selected range
                let dateMatch = true;
                if (startDate && endDate) {
                    // Remove time part for a fair comparison
                    interviewDate.setHours(0, 0, 0, 0);
                    startDate.setHours(0, 0, 0, 0);
                    endDate.setHours(23, 59, 59, 999);

                    dateMatch = interviewDate >= startDate && interviewDate <= endDate;
                }

                if (gradeMatch && yearMatch && dateMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

    </script>


    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>