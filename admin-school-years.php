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
    <title>Admin - SJBPS School Years</title>
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
                        <a class="nav-link" href="admin-subjects.php">
                            <i class="fas fa-book-open me-2"></i>Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-school-years.php">
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
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0">School Years</h4>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="ms-auto">
                            <div class="col">
                                <!--<label for="schoolYearSelect" class="form-label mb-0 me-2">School Year:</label>
                                <select id="schoolYearSelect" class="form-select school-year-select"> -->
                                    <!-- Options will be populated dynamically
                                </select> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center mb-4 gap-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSchoolYearModal">
                        Add School Year
                    </button>
                    <!-- Search Bar -->
                    <div class="search-container">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" id="searchSchoolYear" class="form-control" placeholder="Search school years" aria-label="Search">
                            <button class="btn btn-outline-secondary" type="button" id="clearBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- School Year Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>School Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="schoolYearContainer">
                            <!-- Data will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Add School Year Modal -->
                <div class="modal fade" id="addSchoolYearModal" tabindex="-1" aria-labelledby="addSchoolYearModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addSchoolYearModalLabel">Add School Year</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addShoolYearForm">
                                    <div class="mb-3">
                                        <label for="schoolYearName" class="form-label">School Year Name</label>
                                        <input type="text" class="form-control" id="schoolYearName" name="schoolYearName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="schoolYearIsActive" class="form-label">Is Active</label>
                                        <select class="form-select" id="schoolYearIsActive" name="school_year_is_active" required>
                                        
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-2">Add</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit School Year Modal -->
                <div class="modal fade" id="editSchoolYearModal" tabindex="-1" aria-labelledby="editSchoolYearModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSchoolYearModalLabel">Edit School Year</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editSchoolYearForm">
                                    <input type="hidden" id="editSchoolYearId" name="editSchoolYearId">
                                    <div class="mb-3">
                                        <label for="editSchoolYearName" class="form-label">School Year Name</label>
                                        <input type="text" class="form-control" id="editSchoolYearName" name="editSchoolYearName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editSchoolYearIsActive" class="form-label">Is Active</label>
                                        <select class="form-select" id="editSchoolYearIsActive" name="editSchoolYearIsActive" required>
                                        
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-2">Update</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete School Year Modal -->
                <div class="modal fade" id="deleteSchoolYearModal" tabindex="-1" aria-labelledby="deleteSchoolYearModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteSchoolYearModalLabel">Delete School Year</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this school year?</p>
                                <input type="hidden" id="deleteSchoolYearId">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
            const schoolYearContainer = document.getElementById("schoolYearContainer");
            const searchInput = document.getElementById("searchSchoolYear");
            const clearBtn = document.getElementById("clearBtn");

            // Search Functionality
            searchInput.addEventListener("input", function () {
                const searchValue = this.value.toLowerCase().trim();
                document.querySelectorAll("tbody tr").forEach(row => {
                    const rowText = row.innerText.toLowerCase();
                    row.style.display = rowText.includes(searchValue) ? "" : "none";
                });
            });

            clearBtn.addEventListener("click", () => {
                searchInput.value = "";
                document.querySelectorAll("tbody tr").forEach(row => row.style.display = "");
            });

            // INIT
            fetchSchoolYears();
            fetchSchoolYearStatus();
            attachFormHandlers();
            attachDeleteHandler();

            // Fetch and display school years
            function fetchSchoolYears() {
                fetch("databases/fetch_all_school_years.php")
                    .then(res => res.json())
                    .then(data => {
                        schoolYearContainer.innerHTML = "";

                        if (data.length === 0) {
                            schoolYearContainer.innerHTML = `
                                <tr>
                                    <td colspan="8" class="text-center py-5 empty-table-message">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No school year found.</p>
                                    </td>
                                </tr>
                            `;
                        } else {
                            data.forEach((year, index) => {
                                const row = document.createElement("tr");
                                row.classList.add("year-row");
                                row.setAttribute("data-id", year.school_year_id);

                                row.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${year.school_year}</td>
                                    <td>${year.status}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="${year.school_year_id}"
                                            data-name="${year.school_year}"
                                            data-status="${year.is_active}">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSchoolYear(${year.school_year_id})">
                                            Delete
                                        </button>
                                    </td>
                                `;
                                schoolYearContainer.appendChild(row);
                            });

                            attachEditListeners();
                        }
                    })
                    .catch(error => console.error("Error fetching school years:", error));
            }

            // Fetch school year status (whether there's already an active school year)
            function fetchSchoolYearStatus() {
                fetch('databases/check_active_school_year.php')
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById('schoolYearIsActive');
                        const editSelect = document.getElementById('editSchoolYearIsActive');

                        console.log("this is the:", editSchoolYearIsActive.value);

                        // Clear existing options
                        select.innerHTML = '';
                        editSelect.innerHTML = ''; // Clear options in editSelect as well, if needed

                        // Always allow "Inactive"
                        const optionInactive = document.createElement('option');
                        optionInactive.value = "0";
                        optionInactive.textContent = "Inactive";
                        select.appendChild(optionInactive);

                        // Clone the "Inactive" option and append to editSelect
                        let clonedInactiveOption = optionInactive.cloneNode(true); 
                        editSelect.appendChild(clonedInactiveOption);

                        // Conditionally add "Active"
                        const optionActive = document.createElement('option');
                        optionActive.value = "1";
                        optionActive.textContent = "Active";
                        select.appendChild(optionActive);

                        // Clone the "Active" option and append to editSelect
                        let clonedActiveOption = optionActive.cloneNode(true);
                        editSelect.appendChild(clonedActiveOption);
                    })
                    .catch(error => {
                        console.error('Error checking active school year:', error);
                        const select = document.getElementById('schoolYearIsActive');
                    });
            }

            // Edit modal handler (Allowing admin to select active status again)
            function attachEditListeners() {
                document.querySelectorAll(".edit-btn").forEach(button => {
                    button.addEventListener("click", () => {
                        // Pre-fill the edit form fields
                        const schoolYearId = button.dataset.id;
                        const schoolYearName = button.dataset.name;
                        const schoolYearStatus = button.dataset.status;

                        document.getElementById("editSchoolYearId").value = schoolYearId;
                        document.getElementById("editSchoolYearName").value = schoolYearName;
                        document.getElementById("editSchoolYearIsActive").value = schoolYearStatus;

                        const editModal = new bootstrap.Modal(document.getElementById("editSchoolYearModal"));
                        editModal.show();
                    });
                });
            }


            // Add & Edit form handlers
            function attachFormHandlers() {
                document.getElementById("addShoolYearForm").addEventListener("submit", function (event) {
                    event.preventDefault();
                    const formData = new FormData(this);

                    fetch("databases/insert_school_year.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.status === "success" ? "School year added successfully!" : "Error: " + data.message);
                        if (data.status === "success") location.reload();
                    })
                    .catch(err => console.error("Add school year error:", error));
                });

                document.getElementById("editSchoolYearForm").addEventListener("submit", function (event) {
                    event.preventDefault();
                    const formData = new FormData(this);

                    fetch("databases/edit_school_year.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.status === "success" ? "School Year updated successfully!" : "Error: " + data.message);
                        if (data.status === "success") location.reload();
                    })
                    .catch(err => console.error("Edit school year error:", error));
                });
            }

            // Delete handler
            function attachDeleteHandler() {
                document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
                    const schoolYearId = document.getElementById("deleteSchoolYearId").value;
                    const formData = new FormData();
                    formData.append("school_year_id", schoolYearId);

                    fetch("databases/delete_school_year.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.status === "success" ? "School Year deleted successfully!" : "Error: " + data.message);
                        if (data.status === "success") location.reload();
                    })
                    .catch(err => console.error("Delete school year error:", err));
                });
            }

            // Open delete modal
            window.deleteSchoolYear = (id) => {
                document.getElementById("deleteSchoolYearId").value = id;
                new bootstrap.Modal(document.getElementById("deleteSchoolYearModal")).show();
            };
        });
    </script>






</body>
</html>