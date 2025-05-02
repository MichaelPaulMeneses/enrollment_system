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
    <title>Admin - SJBPS Subjects</title>
    <link rel="icon" type="image/png" href="assets/main/logo/st-johns-logo.png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #3498db;
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
        .grade-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .grade-card:hover {
            background-color: #3498db;
            color: #f0f0f0;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .school-year-select {
            max-width: 200px;
        }
        .warning-alert {
            background-color: #fff3cd;
            border-color: #ffecb5;
            color: #664d03;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
        }
        .warning-alert i {
            margin-right: 0.5rem;
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
                        <a class="nav-link" href="admin-declined-interviews.php">
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
                            <i class="fas fa-scroll me-2"></i>Curriculum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-subjects.php">
                            <i class="fas fa-book-open me-2"></i>Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-school-years.php">
                        <i class="fas fa-graduation-cap me-2"></i>School Years
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-homepage-editor.php">
                            <i class="fas fa-edit me-2"></i>Home Page Editor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-user-management.php">
                            <i class="fas fa-user-cog me-2"></i>Users
                        </a>
                    </li>
                </ul>
            </div>
    
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Subjects</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Advanced Filters
                    </button>
                </div>

                <div class="d-flex justify-content-end align-items-center mb-4 gap-3">
                    <!-- Add Subjects Button -->  
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectsModal">
                        Add Subjects
                    </button>
                    <!-- Search Bar -->
                    <div class="search-container">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" id="searchSubject" class="form-control" placeholder="Search subjects" aria-label="Search">
                            <button class="btn btn-outline-secondary" type="button" id="clearBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Add Subjects Modal -->
                <div class="modal fade" id="addSubjectsModal" tabindex="-1" aria-labelledby="addSubjectsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addSubjectForm" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSubjectsModalLabel">Add New Subject</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="subjectCode" class="form-label">Subject Code</label>
                                        <input type="text" class="form-control" id="subjectCode" name="subjectCode" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subjectName" class="form-label">Subject Name</label>
                                        <input type="text" class="form-control" id="subjectName" name="subjectName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gradeLevelId" class="form-label">Grade Level</label>
                                        <select class="form-select" id="gradeLevelId" name="gradeLevelId" required>
                                            <option value="" disabled selected>Select Grade Level</option>
                                            <!-- Options dynamically populated -->
                                        </select>
                                    </div>

                                    <!-- Shown only when grade level is 14 or 15 -->
                                    <div id="semesterField" style="display: none;">
                                        <div class="mb-3">
                                            <label for="academicTrackId" class="form-label">Academic Track</label>
                                            <select class="form-select" id="academicTrackId" name="academicTrackId" required>
                                                <option value="" disabled selected>Select Academic Track</option>
                                                <option value="STEM">STEM - Science, Technology, Engineering, and Mathematics</option>
                                                <option value="ABM">ABM - Accountancy, Business, and Management</option>
                                                <option value="HUMSS">HUMSS - Humanities and Social Sciences</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="academicSemesterId" class="form-label">Semester</label>
                                            <select class="form-select" id="academicSemesterId" name="academicSemesterId" required>
                                                <option value="" disabled selected>Select Semester</option>
                                                <option value="1">1st Semester</option>
                                                <option value="2">2nd Semester</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Subject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Subjects Modal -->
                <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editSubjectForm" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSubjectModalLabel">Edit Subject</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editSubjectId" name="editSubjectId">
                                    <div class="mb-3">
                                        <label for="editSubjectCode" class="form-label">Subject Code</label>
                                        <input type="text" class="form-control" id="editSubjectCode" name="editSubjectCode" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editSubjectName" class="form-label">Subject Name</label>
                                        <input type="text" class="form-control" id="editSubjectName" name="editSubjectName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editGradeLevelId" class="form-label">Grade Level</label>
                                        <select class="form-select" id="editGradeLevelId" name="editGradeLevelId" required>
                                            <option value="" disabled selected>Select Grade Level</option>
                                            <!-- Dynamically populate options here -->
                                        </select>
                                    </div>

                                    <!-- Shown only when grade level is 14 or 15 -->
                                    <div id="editSemesterField" style="display: none;">
                                        <div class="mb-3">
                                            <label for="editAcademicTrackId" class="form-label">Academic Track</label>
                                            <select class="form-select" id="editAcademicTrackId" name="editAcademicTrackId" required>
                                                <option value="" disabled selected>Select Academic Track</option>
                                                <option value="STEM">STEM - Science, Technology, Engineering, and Mathematics</option>
                                                <option value="ABM">ABM - Accountancy, Business, and Management</option>
                                                <option value="HUMSS">HUMSS - Humanities and Social Sciences</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editAcademicSemesterId" class="form-label">Semester</label>
                                            <select class="form-select" id="editAcademicSemesterId" name="editAcademicSemesterId" required>
                                                <option value="" disabled selected>Select Semester</option>
                                                <option value="1">1st Semester</option>
                                                <option value="2">2nd Semester</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Subject Modal -->
                <div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="deleteSubjectModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="deleteSubjectForm" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteSubjectModalLabel">Delete Subject</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this subject?</p>
                                    <input type="hidden" id="deleteSubjectId" name="subject_id">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                <!-- Subjects Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Grade Level</th>
                                <th>Academic Track</th>
                                <th>Academic Semester</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="subjectsContainer">
                            <!-- Dynamic Content will be inserted here -->
                        </tbody>
                    </table>
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
                                        <option value="">All Grade Levels</option>
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

                                <!-- Academic Track Filter -->
                                <div class="mb-3">
                                    <label class="form-label">Academic Track</label>
                                    <select id="academicTrackFilter" class="form-select">
                                        <option value="" >Select Academic Track</option>
                                        <option value="STEM">STEM - Science, Technology, Engineering, and Mathematics</option>
                                        <option value="ABM">ABM - Accountancy, Business, and Management</option>
                                        <option value="HUMSS">HUMSS - Humanities and Social Sciences</option>
                                    </select>
                                </div>

                                <!-- Academic Semester Filter -->
                                <div class="mb-3">
                                    <label class="form-label">Academic Semester</label>
                                    <select id="academicSemesterFilter" class="form-select">
                                        <option value="">Select Semester</option>
                                        <option value="1">1st Semester</option>
                                        <option value="2">2nd Semester</option>
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

                
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const gradeLevelSelect = document.getElementById("gradeLevelId");
            const editGradeLevelSelect = document.getElementById("editGradeLevelId");

            // Search Method
            // Filter subjects based on search input
            const searchInput = document.getElementById("searchSubject");
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

            // INIT
            fetchGradeLevels();
            fetchSubjects();
            attachFormHandlers();
            attachDeleteHandler();

            // Fetch and populate grade levels
            function fetchGradeLevels() {
                fetch("databases/grade_levels.php")
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) throw new Error(data.error);

                        data.forEach(grade => {
                            if (grade.grade_level_id !== '1') {
                                const option = document.createElement("option");
                                option.value = grade.grade_level_id;
                                option.textContent = grade.grade_name;

                                gradeLevelSelect.appendChild(option);
                                const clonedOption = option.cloneNode(true);
                                editGradeLevelSelect.appendChild(clonedOption);
                            }
                        });

                        gradeLevelSelect.addEventListener('change', toggleFields);
                        editGradeLevelSelect.addEventListener('change', toggleFields);
                    })
                    .catch(error => console.error("Error fetching grade levels:", error));
            }

            function toggleFields() {
                const semesterField = document.getElementById("semesterField");
                const editSemesterField = document.getElementById("editSemesterField");
                const gradeLevelId = this.value;

                console.log(gradeLevelId);

                if (gradeLevelId === "14" || gradeLevelId === "15") {
                    semesterField.style.display = "block";
                    document.getElementById("academicTrackId").required = true;
                    document.getElementById("academicSemesterId").required = true;

                    editSemesterField.style.display = "block";
                    document.getElementById("editAcademicTrackId").required = true;
                    document.getElementById("editAcademicSemesterId").required = true;
                } else {
                    semesterField.style.display = "none";
                    document.getElementById("academicTrackId").value = '';
                    document.getElementById("academicSemesterId").value = '';
                    document.getElementById("academicTrackId").required = false;
                    document.getElementById("academicSemesterId").required = false;

                    editSemesterField.style.display = "none";
                    document.getElementById("editAcademicTrackId").value = '';
                    document.getElementById("editAcademicSemesterId").value = '';
                    document.getElementById("editAcademicTrackId").required = false;
                    document.getElementById("editAcademicSemesterId").required = false;
                }
            }

            // Fetch and display subjects
            function fetchSubjects() {
                fetch(`databases/fetch_subjects_for_display.php`)
                    .then(res => res.json())
                    .then(data => {
                        subjectsContainer.innerHTML = "";

                        if (data.length === 0) {
                            subjectsContainer.innerHTML = `
                                <tr>
                                    <td colspan="8" class="text-center py-5 empty-table-message">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No subjects found.</p>
                                    </td>
                                </tr>
                            `;
                        } else {
                            data.forEach((subject, index) => {
                                const row = document.createElement("tr");
                                row.innerHTML += `
                                    <td>${index + 1}</td>
                                    <td>${subject.subject_code}</td>
                                    <td>${subject.subject_name}</td>
                                    <td>${subject.grade_name}</td>
                                    <td>${subject.academic_track}</td>
                                    <td>${subject.academic_semester}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editSubject(${subject.subject_id})">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSubject(${subject.subject_id})">Delete</button>
                                    </td>
                                `;
                                subjectsContainer.appendChild(row);
                            });
                        }
                    })
                    .catch(error => console.error("Error fetching subjects:", error));
            }

            // Add and Edit form submission
            function attachFormHandlers() {
                const addForm = document.getElementById("addSubjectForm");
                const editForm = document.getElementById("editSubjectForm");

                if (addForm) {
                    addForm.addEventListener("submit", function (event) {
                        event.preventDefault();
                        const formData = new FormData(this);

                        const gradeLevelId = parseInt(formData.get('gradeLevelId'), 10);
                        console.log("Grade Level ID:", gradeLevelId);

                        // If NOT Senior High School (Grade 11 = 14, Grade 12 = 15), clear academic fields
                        if (gradeLevelId !== 14 && gradeLevelId !== 15) {
                            formData.set('editAcademicTrackId', '');
                            formData.set('editAcademicSemesterId', '');
                        }

                        fetch("databases/insert_subject.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log(data); // Log the response from the server for debugging
                            alert(data.status === "success" ? "Subject added successfully!" : "Error: " + data.message);
                            if (data.status === "success") {
                                const modalElement = document.getElementById("addSubjectsModal");
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) modalInstance.hide();
                                setTimeout(() => location.reload(), 250);
                            }
                        })
                        .catch(err => {
                            console.error("Add subject error:", err);
                            alert("An error occurred while adding a subject.");
                        });

                    });
                }


                if (editForm) {
                    editForm.addEventListener("submit", function (e) {
                        e.preventDefault();
                        const formData = new FormData(this);

                        const editGradeLevelId = parseInt(formData.get('editGradeLevelId'), 10);
                        console.log("Grade Level ID:", editGradeLevelId);

                        // If NOT Senior High School (Grade 11 = 14, Grade 12 = 15), clear academic fields
                        if (editGradeLevelId !== 14 && editGradeLevelId !== 15) {
                            formData.set('editAcademicTrackId', '');
                            formData.set('editAcademicSemesterId', '');
                        }

                        fetch("databases/edit_subject.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.status === "success" ? "Subject updated successfully!" : "Error: " + data.message);
                            if (data.status === "success") {
                                const modalElement = document.getElementById("editSubjectModal");
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) modalInstance.hide();
                                setTimeout(() => location.reload(), 250); // Refresh to show changes
                            }
                        })
                        .catch(err => {
                            console.error("Edit subject error:", err);
                            alert("An error occurred while updating the subject.");
                        });
                    });
                }
            }

            // Populate the modal with subject data for editing
            window.editSubject = (subjectId) => {
            fetch(`databases/fetch_subjects.php?subject_id=${subjectId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) throw new Error(data.error);

                    document.getElementById("editSubjectId").value = data.subject_id;
                    document.getElementById("editSubjectCode").value = data.subject_code;
                    document.getElementById("editSubjectName").value = data.subject_name;
                    //document.getElementById("editGradeLevelId").value = data.grade_level_id;

                    console.log(data.grade_level_id);
                    // Trigger the function to toggle fields based on the grade level
                    toggleEditFields(data.grade_level_id);  

                    // Show the modal after all values are set
                    new bootstrap.Modal(document.getElementById("editSubjectModal")).show();
                })
                .catch(err => {
                    console.error("Fetch subject error:", err);
                    alert("An error occurred while fetching subject details.");
                });
            };

            // Toggle fields for editing based on grade level
            function toggleEditFields(gradeLevelId) {
                const editSemesterField = document.getElementById("editSemesterField");
                const editAcademicTrack = document.getElementById("editAcademicTrackId");
                const editAcademicSemester = document.getElementById("editAcademicSemesterId");

                if (gradeLevelId === "14" || gradeLevelId === "15") { // Senior High School (Grade 11 or 12)
                    editSemesterField.style.display = "block";
                    
                    editAcademicTrack.value = editAcademicTrackId.value;
                    editAcademicSemester.value = editAcademicSemesterId.value;
                    
                    editAcademicTrack.required = true;
                    editAcademicSemester.required = true;
                } else {
                    editSemesterField.style.display = "none";
                    editAcademicTrack.value = '';
                    editAcademicSemester.value = '';
                    editAcademicTrack.required = false;
                    editAcademicSemester.required = false;
                }
            }

            // Delete handler
            function attachDeleteHandler() {
                const deleteForm = document.getElementById("deleteSubjectForm");

                if (deleteForm) {
                    deleteForm.addEventListener("submit", function (e) {
                        e.preventDefault();
                        const formData = new FormData(this);

                        fetch("databases/delete_subject.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.status === "success" ? "Subject deleted successfully!" : "Error: " + data.message);
                            if (data.status === "success") location.reload();
                        })
                        .catch(err => console.error("Delete subject error:", err));
                    });
                }
            }

            window.deleteSubject = (subjectId) => {
                document.getElementById("deleteSubjectId").value = subjectId;
                new bootstrap.Modal(document.getElementById("deleteSubjectModal")).show();
            };
        });

        // Advane Filter Method
        document.getElementById("applyFiltersBtn").addEventListener("click", function() {
            filterTable();  // Call the filterTable function when the "Apply Filters" button is clicked
            $('#filterModal').modal('hide');  // Close the modal after applying filters
        });

        // Filter Method
        function filterTable() {
            let gradeApplying = document.getElementById("gradeApplyingFilter").value.toLowerCase();
            let academicTrack = document.getElementById("academicTrackFilter").value.toLowerCase();
            let academicSemester = document.getElementById("academicSemesterFilter").value.toLowerCase();
            
            console.log(gradeApplying, academicTrack, academicSemester);

            document.querySelectorAll("tbody tr").forEach(row => {
                let gradeMatch = gradeApplying === "" || row.cells[4].textContent.toLowerCase() === gradeApplying;
                let trackMatch = academicTrack === "" || row.cells[5].textContent.toLowerCase() === academicTrack;
                let semesterMatch = academicSemester === "" || row.cells[6].textContent.toLowerCase() === academicSemester;

                if (gradeMatch && trackMatch && semesterMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

    </script>


    


</body>
</html>