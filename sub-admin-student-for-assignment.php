<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'sub-admin') {
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
    <title>Sub-Admin - SJBPS Enrollees for Assignment</title>
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

    
    <!-- For loadingSpinner -->
    <style>
        #loadingSpinner {
            position: fixed;
            top: 50%;
            left: 100%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                        <a class="nav-link" href="sub-admin-dashboard.php">
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
                        <a class="nav-link active" href="sub-admin-student-for-assignment.php">
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
                            <i class="fas fa-scroll me-2"></i>Curriculum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-subjects.php">
                            <i class="fas fa-book-open me-2"></i>Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-homepage-editor.php">
                            <i class="fas fa-edit me-2"></i>Home Page Editor
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Loading Spinner Area -->
            <div id="loadingSpinner" style="display: none;">
                <div class="spinner"></div> <!-- You can use CSS or an external library like Font Awesome for the spinner -->
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">For Assignment</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Advanced Filters
                    </button>
                </div>
                
                <!-- Search Bar -->
                <div class="search-container d-flex justify-content-end">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search interviews" aria-label="Search">
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
                                <th>School Year</th>
                                <th>Enrollment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="forAssignmentTable">
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
                    <!-- Enrollment Type Filter -->
                    <div class="mb-3">
                        <label class="form-label">Student Type</label>
                        <select id="studentTypeFilter" class="form-select">
                            <option value="">All Types</option>
                            <option value="old">Old Student</option>
                            <option value="new/transferee">New/Transferee Student</option>
                        </select>
                    </div>

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


<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Assign Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="assignStudentId">

                    <div class="mb-3">
                        <label for="studentName" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="studentName" name="student_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="gradeApplying" class="form-label">Grade Applying For</label>
                        <input type="text" class="form-control" id="gradeApplying" name="grade_applying" readonly>
                    </div>

                    <div class="mb-3" id="academicInfo" style="display: none;">
                        <label for="academicTrack" class="form-label">Academic Track</label>
                        <input type="text" class="form-control" id="academicTrack" name="academic_track" readonly>
                    </div>

                    <div class="mb-3" id="semesterInfo" style="display: none;">
                        <label for="academicSemester" class="form-label">Academic Semester</label>
                        <input type="text" class="form-control" id="academicSemester" name="academic_semester" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="sectionDropdown" class="form-label">Assign to Section</label>
                        <select class="form-select" id="sectionDropdown" name="section_id" required>
                            <option value="" disabled selected>Select Section</option>

                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="finalConfirmBtn">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {            
            
            // Fetch interviews when the page loads
            fetchInterviews();
            // Fetch school years for the filter dropdown
            fetchSchoolYears();

            // Filter subjects based on search input
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

        // Fetch interviews for review from the database
        function fetchInterviews() {
            fetch("databases/fetch_for_assignment_data.php")  // Your PHP script
                .then(response => response.json())  // Parse the JSON response
                .then(data => {
                    let forAssignmentTable = document.querySelector("#forAssignmentTable");  // Select the tbody element
                    forAssignmentTable.innerHTML = "";  // Clear existing rows

                    // If no data is returned (empty result)
                    if (data.length === 0) {
                        forAssignmentTable.innerHTML = `
                            <tr>
                                <td colspan="8" class="text-center py-5 empty-table-message">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No interviews for review at this time</p>
                                </td>
                            </tr>
                        `;
                    } else {
                        // If there are interviews, populate the table
                        data.forEach((assignment, index) => {
                            let row = document.createElement("tr");
                            row.classList.add("interview-row");
                            row.setAttribute("data-id", assignment.student_id);

                            row.innerHTML += `
                                <td>${index + 1}</td> 
                                <td>${assignment.student_name}</td>
                                <td>${assignment.type_of_student}</td>
                                <td>${assignment.grade_applying_for}</td>
                                <td>${assignment.school_year}</td> 
                                <td>${assignment.enrollment_status}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="assignStudent(${assignment.student_id})" data-bs-toggle="modal" data-bs-target="#assignModal">
                                        Assign
                                    </button>

                                </td>
                            `;
                            forAssignmentTable.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error("Error fetching data:", error));  // Error handling
        }

        // Function to open the modal and fetch the student data
        function assignStudent(studentId) {
            // Reset all fields and hide the academic information sections
            document.getElementById('assignStudentId').value = '';
            document.getElementById('studentName').value = '';
            document.getElementById('gradeApplying').value = '';
            document.getElementById('academicTrack').value = '';
            document.getElementById('academicSemester').value = '';
            document.getElementById('academicInfo').style.display = 'none';
            document.getElementById('semesterInfo').style.display = 'none';

            // Set the student ID in the hidden input field
            document.getElementById('assignStudentId').value = studentId;

            console.log(studentId);

            // Use fetch to get the student data for assignment modal
            fetch("databases/fetch_for_assignment_modal_data.php", {
                method: "POST",
                body: JSON.stringify({ student_id: studentId }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json()) // Expecting JSON response
            .then(data => {
                // Populate the modal with the received data
                console.log(data.full_name);
                console.log(data.grade_applying_for);
                console.log(data.grade_name);
                console.log(data.academic_track);
                console.log(data.academic_semester);
                console.log(data.school_year_id);

                document.getElementById('studentName').value = data.full_name;
                document.getElementById('gradeApplying').value = data.grade_name;

                // Show academic info if applicable
                if (data.grade_applying_for == 14 || data.grade_applying_for == 15) {
                    document.getElementById('academicTrack').value = data.academic_track;
                    document.getElementById('academicInfo').style.display = 'block';
                    document.getElementById('academicSemester').value = data.academic_semester;
                    document.getElementById('semesterInfo').style.display = 'block';
                }
                
                // Fetch sections for the dropdown based on the grade level
                const gradeLevel = data.grade_applying_for;
                const schoolYear = data.school_year_id;

                fetch("databases/fetch_sections_for_dropdown.php", {
                    method: "POST",
                    body: JSON.stringify({ grade_level_id: gradeLevel, school_year_id: schoolYear }),
                    headers: { "Content-Type": "application/json" }
                })
                .then(response => response.json())
                .then(responseData => {
                    const sectionDropdown = document.getElementById("sectionDropdown");
                    sectionDropdown.innerHTML = '<option value="" disabled selected>Select Section</option>';

                    if (responseData.status === "success" && Array.isArray(responseData.sections)) {
                        responseData.sections.forEach(section => {
                            const option = document.createElement("option");
                            option.value = section.section_id;
                            option.textContent = section.section_name;
                            sectionDropdown.appendChild(option);
                        });
                    } else {
                        console.error("Failed to fetch sections:", responseData.message || "No sections available");
                        const option = document.createElement("option");
                        option.value = "";
                        option.textContent = "No sections available";
                        option.disabled = true;
                        option.selected = true;
                        sectionDropdown.appendChild(option);
                    }
                })
                .catch(error => console.error("Error fetching sections:", error));

            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
        }

        // Function to handle the form submission
        document.querySelector("#assignForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent page reload

            const studentId = document.getElementById("assignStudentId").value;
            const sectionId = document.getElementById("sectionDropdown").value;
            const adminUserId = "<?php echo isset($adminUserId) ? htmlspecialchars($adminUserId) : 0; ?>";
            
            document.getElementById("loadingSpinner").style.display = "block";
            document.getElementById("finalConfirmBtn").disabled = true;

            fetch("databases/approve_assignment_email.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    student_id: studentId,
                    section_id: sectionId,
                    admin_user_id: adminUserId
                })
            })
            .then(response => response.json())
            .then(result => {
                console.log("Assignment result:", result);
                if (result.success) {
                    // Success case: Show message and redirect
                    alert("Enrollee assigned and email sent successfully!");

                    setTimeout(function() {
                        window.location.href = "sub-admin-student-for-assignment.php";  // Redirect after success
                    }, 250);
                } else {
                    alert("Error: " + result.message);
                }
            })
            .catch(error => {
                console.error("Assignment error:", error);
                alert("Something went wrong while assigning the student.");
            })
            .finally(() => {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("finalConfirmBtn").disabled = false;
            });
        });

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
                            option.value = year.school_year;
                            option.textContent = year.school_year;
                            schoolYearDropdown.appendChild(option);
                        });
                    } else {
                        console.error("Failed to fetch school years.");
                    }
                })
                .catch(error => console.error("Error fetching school years:", error));
        }

        // Apply Filters on Click
        document.getElementById("applyFiltersBtn").addEventListener("click", function () {
            filterTable();  // Call the filterTable function when the "Apply Filters" button is clicked
            $('#filterModal').modal('hide');  // Close the modal after applying filters
        });

        // Filter Method
        function filterTable() {
            let selectedType = document.getElementById("studentTypeFilter").value.toLowerCase();
            let gradeApplying = document.getElementById("gradeApplyingFilter").value.toLowerCase();
            let schoolYear = document.getElementById("schoolYearFilter").value.toLowerCase();

            document.querySelectorAll("tbody tr").forEach(row => {
                // Compare row values with selected filters
                let typeMatch = selectedType === "" || row.children[2].textContent.toLowerCase() === selectedType;
                let gradeMatch = gradeApplying === "" || row.cells[3].textContent.toLowerCase() === gradeApplying;
                let yearMatch = schoolYear === "" || row.cells[4].textContent.toLowerCase() === schoolYear;

                // Show or hide row based on matches
                if (typeMatch && gradeMatch && yearMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }


    </script>


</body>
</html>