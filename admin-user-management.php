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
    <title>Admin - SJBPS Users Management</title>
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
                            <i class="fas fa-book-open me-2"></i>Curriculum
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
                        <a class="nav-link active" href="admin-user-management.php">
                            <i class="fas fa-user-cog me-2"></i>Users
                        </a>
                    </li>
                </ul>
            </div>
    
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">User Management</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Advanced Filters
                    </button>
                </div>

                <div class="d-flex justify-content-end align-items-center mb-4 gap-3">
                    <!-- Add User Button -->  
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUsersModal">
                        Add User
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
                <div class="modal fade" id="addUsersModal" tabindex="-1" aria-labelledby="addUsersModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addUsersForm" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addUsersModalLabel">Add New User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
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

                                    <!-- User Type Dropdown -->
                                    <div class="mb-3">
                                        <label for="user_type" class="form-label">User Type</label>
                                        <select class="form-control" id="user_type" name="user_type" required>
                                            <!-- Options will be added via JavaScript -->
                                        </select>
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

                <!-- Edit User Modal -->
                <div class="modal fade" id="editUsersModal" tabindex="-1" aria-labelledby="editUsersModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editUserForm" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUsersModalLabel">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Hidden input for user ID -->
                                    <input type="hidden" id="edit_user_id" name="user_id">

                                    <div class="mb-3">
                                        <label for="edit_username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="edit_username" name="username" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                                    </div>
                                    
                                    <!-- Password Fields -->
                                    <div class="mb-3">
                                        <label for="edit_password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="edit_password" name="password">
                                    </div>

                                    <!-- User Type Dropdown -->
                                    <div class="mb-3">
                                        <label for="edit_user_type" class="form-label">User Type</label>
                                        <select class="form-control" id="edit_user_type" name="user_type" required>
                                            <!-- Options will be filled dynamically via JS -->
                                        </select>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Delete User Modal -->
                <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="deleteUserForm" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" id="delete_user_id">
                                    <p>Are you sure you want to delete user: <strong id="delete_username_display"></strong>?</p>
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
                                <th>Username</th>
                                <th>User Fullname</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersContainer">
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

                                <!-- User Type Filter -->
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select id="userFilter" class="form-select">
                                        <option value="">Select User Type</option>
                                        <option value="admin">Admin</option>
                                        <option value="sub-admin">Sub-Admin</option>
                                        <option value="cashier">Cashier</option>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
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
            fetchUsers();
            populateUserTypes();

            
            attachFormHandlers();
            attachDeleteHandler();

            // Fetch and display subjects
            function fetchUsers() {
                fetch(`databases/fetch_users_for_display.php`)
                    .then(res => res.json())
                    .then(data => {
                        usersContainer.innerHTML = "";

                        if (data.length === 0) {
                            usersContainer.innerHTML = `
                                <tr>
                                    <td colspan="8" class="text-center py-5 empty-table-message">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No subjects found.</p>
                                    </td>
                                </tr>
                            `;
                        } else {
                            data.forEach((user, index) => {
                                const row = document.createElement("tr");

                                let actionButtons = "";
                                if (user.user_type !== 'admin') {
                                    actionButtons = `
                                        <button class="btn btn-warning btn-sm" onclick="editUser(${user.user_id})">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.user_id}, '${encodeURIComponent(user.username)}')">Delete</button>
                                    `;
                                }

                                row.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${user.username}</td>
                                    <td>${user.full_name}</td>
                                    <td>${user.email}</td>
                                    <td>${user.user_type}</td>
                                    <td>${actionButtons}</td>
                                `;

                                usersContainer.appendChild(row);
                            });
                        }
                    })
                    .catch(error => console.error("Error fetching subjects:", error));
            }


            // Populate User Type dropdown and checks if admin alrady exist
            function populateUserTypes() {
                const userTypeSelect = document.getElementById("user_type");

                // All user types
                const userTypes = [
                    { value: "admin", label: "Admin" },
                    { value: "sub-admin", label: "Sub-Admin" },
                    { value: "cashier", label: "Cashier" }
                ];

                fetch("databases/check_admin_exists.php")
                    .then(res => res.json())
                    .then(data => {
                        userTypes.forEach(type => {
                            const option = document.createElement("option");
                            option.value = type.value;
                            option.textContent = type.label;

                            // Disable admin if already exists
                            if (type.value === "admin" && data.admin_exists) {
                                option.disabled = true;
                                option.textContent += " (already assigned)";
                            }

                            userTypeSelect.appendChild(option);
                        });
                    })
                    .catch(err => {
                        console.error("Error checking admin:", err);
                        // Fallback: populate options anyway
                        userTypes.forEach(type => {
                            const option = document.createElement("option");
                            option.value = type.value;
                            option.textContent = type.label;
                            userTypeSelect.appendChild(option);
                        });
                    });
            }


            // Add and Edit form submission
            function attachFormHandlers() {
                const addForm = document.getElementById("addUsersForm");
                const editForm = document.getElementById("editUserForm");

                if (addForm) {
                    addForm.addEventListener("submit", function (event) {
                        event.preventDefault();
                        const formData = new FormData(this);

                        fetch("databases/insert_user.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.status === "success" ? "User added successfully!" : "Error: " + data.message);
                            if (data.status === "success") {
                                const modalElement = document.getElementById("addUsersModal");
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) modalInstance.hide();
                                setTimeout(() => location.reload(), 250);
                            }
                        })
                        .catch(err => {
                            console.error("Add user error:", err);
                            alert("An error occurred while adding a user.");
                        });
                    });
                }


                if (editForm) {
                    editForm.addEventListener("submit", function (event) {
                        event.preventDefault();
                        const formData = new FormData(this); // Get form data

                        fetch("databases/edit_user.php", {
                            method: "POST",
                            body: formData // Send form data to PHP file
                        })
                        .then(res => res.json())  // Assume the response is in JSON format
                        .then(data => {
                            alert(data.status === "success" ? "User updated successfully!" : "Error: " + data.message);
                            if (data.status === "success") {
                                const modalElement = document.getElementById("editUsersModal");
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) modalInstance.hide();  // Close the modal on success
                                setTimeout(() => location.reload(), 250); // Refresh to show changes
                            }
                        })
                        .catch(err => {
                            console.error("Edit user error:", err);
                            alert("An error occurred while updating the user.");
                        });
                    });
                }

            }

            window.editUser = (userId) => {
                fetch('databases/fetch_users_by_id.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${encodeURIComponent(userId)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) throw new Error(data.error);

                    // Clear existing options in the dropdown
                    const userTypeSelect = document.getElementById("edit_user_type");
                    userTypeSelect.innerHTML = "";  // Clear previous options

                    // Populate User Type dropdown (same logic as before)
                    const userTypes = [
                        { value: "admin", label: "Admin" },
                        { value: "sub-admin", label: "Sub-Admin" },
                        { value: "cashier", label: "Cashier" }
                    ];

                    fetch("databases/check_admin_exists.php")
                        .then(res => res.json())
                        .then(checkData => {
                            userTypes.forEach(type => {
                                const option = document.createElement("option");
                                option.value = type.value;
                                option.textContent = type.label;

                                // Disable admin if already exists
                                if (type.value === "admin" && checkData.admin_exists && data.user_type !== "admin") {
                                    option.disabled = true;
                                    option.textContent += " (already assigned)";
                                }


                                userTypeSelect.appendChild(option);
                            });

                            // Set the current user type in the dropdown
                            userTypeSelect.value = data.user_type;  // Assuming 'user_type' holds the current value for the user
                        })
                        .catch(err => {
                            console.error("Error checking admin:", err);
                            // Fallback: populate options anyway
                            userTypes.forEach(type => {
                                const option = document.createElement("option");
                                option.value = type.value;
                                option.textContent = type.label;
                                userTypeSelect.appendChild(option);
                            });
                        });

                    // Populate other fields
                    document.getElementById("edit_user_id").value = data.user_id;
                    document.getElementById("edit_username").value = data.username;
                    document.getElementById("edit_email").value = data.email;
                    document.getElementById("edit_first_name").value = data.first_name;
                    document.getElementById("edit_last_name").value = data.last_name;

                    // Set the password fields to empty (because they are optional and for editing)
                    document.getElementById("edit_password").value = "";

                    // Show the modal
                    new bootstrap.Modal(document.getElementById("editUsersModal")).show();
                })
                .catch(err => {
                    console.error("Fetch user error:", err);
                    alert("An error occurred while fetching user details.");
                });
            };


            // Delete handler
            function attachDeleteHandler() {
                const deleteForm = document.getElementById("deleteUserForm");

                if (deleteForm) {
                    deleteForm.addEventListener("submit", function (event) {
                        event.preventDefault();
                        const formData = new FormData(this);

                        fetch("databases/delete_user.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.status === "success" ? "User deleted successfully!" : "Error: " + data.message);
                            if (data.status === "success") {
                                const modalElement = document.getElementById("deleteUserModal");
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) modalInstance.hide();
                                setTimeout(() => location.reload(), 250);
                            }
                        })
                        .catch(err => {
                            console.error("Delete user error:", err);
                            alert("An error occurred while deleting the user.");
                        });
                    });
                }
            }

            // Define globally so it's accessible from buttons
            window.deleteUser = (userId, username) => {
                document.getElementById("delete_user_id").value = userId;
                document.getElementById("delete_username_display").textContent = username;
                new bootstrap.Modal(document.getElementById("deleteUserModal")).show();
            };

            // Apply Filters on Click
            document.getElementById("applyFiltersBtn").addEventListener("click", function () {
                filterTable();  // Call the filterTable function when the "Apply Filters" button is clicked
                $('#filterModal').modal('hide');  // Close the modal after applying filters
            });

            function filterTable() {
                let userFilter = document.getElementById("userFilter").value.toLowerCase();

                document.querySelectorAll("tbody tr").forEach(row => {

                    // User filter (assumed to be in column index 4)
                    let userMatch = userFilter === "" || row.cells[4].textContent.toLowerCase() === userFilter;

                    if (userMatch) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }


        });

    </script>


    


</body>
</html>