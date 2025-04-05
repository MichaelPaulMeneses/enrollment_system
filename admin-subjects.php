<?php

session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include "databases/db_connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["curriculum_id"])) {
    $curriculum_id = $_POST["curriculum_id"];

    $query = "SELECT curriculum_name FROM curriculums WHERE curriculum_id = ?";
        
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $curriculum_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();
    } else {
        die("Curriculum not found.");
    }
    $stmt->close();
} else {
    die("Access denied.");
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
    <title>SJBPS Admin - Subjects</title>
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
                        <a class="nav-link active" href="admin-curriculum.php">
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
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0">Subjects for <?php echo htmlspecialchars($subject['curriculum_name']); ?></h4>
                    </div>
                    <div class="d-flex justify-content-between align-items-center  gap-3">
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
                </div>

                <!-- Add Subjects Modal -->
                <div class="modal fade" id="addSubjectsModal" tabindex="-1" aria-labelledby="addSubjectsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addSubjectForm" method="POST" action="databases/add_subject.php">
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
                                    <input type="hidden" name="curriculumId" value="<?php echo $curriculum_id; ?>">
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
                                <th>Curriculum</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="subjectsContainer">
                            <!-- Dynamic Content will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fetch and display subjects dynamically based on curriculum_id
        document.addEventListener('DOMContentLoaded', function() {
            const curriculumId = <?php echo $curriculum_id; ?>; // Pass the curriculum_id from PHP
            const subjectsContainer = document.getElementById('subjectsContainer');
            const subjectId = document.getElementById('editSubjectId');
            
            // Fetch subjects based on curriculum_id
            fetch(`databases/fetch_subjects_for_display.php?curriculum_id=${curriculumId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    // Clear existing table rows
                    subjectsContainer.innerHTML = '';

                    // Populate the table with subjects data
                    data.forEach((subject, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index += 1}</td>
                            <td>${subject.subject_code}</td>
                            <td>${subject.subject_name}</td>
                            <td>${subject.curriculum_name}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editSubject(${subject.subject_id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteSubject(${subject.subject_id})">Delete</button>
                            </td>
                        `;
                        subjectsContainer.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching subjects:', error));
        });
        
        // Handle Subject Addition
        document.getElementById("addSubjectForm").addEventListener("submit", function(event) {
            event.preventDefault(); 

            const formData = new FormData(this); 

            fetch("databases/insert_subject.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.status === "success") {
                    alert("Subject added successfully!");
                    location.reload();
                } else {
                    alert("Error: " + data.message); 
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while adding the subject.");
            });
        });


        // Handle Subject Editing
        function editSubject(subjectId) {
            console.log("Editing subject with ID:", subjectId);

            fetch(`databases/fetch_subjects.php?subject_id=${subjectId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert("Error: " + data.error);
                        return;
                    }
                    
                    console.log(data.subject_id);
                    console.log(data.subject_code);
                    console.log(data.subject_name);

                    // Populate modal fields
                    document.getElementById("editSubjectId").value = data.subject_id;
                    document.getElementById("editSubjectCode").value = data.subject_code;
                    document.getElementById("editSubjectName").value = data.subject_name;

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById("editSubjectModal"));
                    editModal.show();
                })
                .catch(error => {
                    console.error("Error fetching subject:", error);
                    alert("An error occurred while fetching subject details.");
                });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const editSubjectForm = document.getElementById("editSubjectForm");

            if (editSubjectForm) {
                editSubjectForm.addEventListener("submit", async function (event) {
                    event.preventDefault(); // Prevent form submission
                    
                    const formData = new FormData(this);

                    try {
                        const response = await fetch("databases/edit_subject.php", {
                            method: "POST",
                            body: formData
                        });

                        const data = await response.json();

                        if (data.status === "success") {
                            alert("Subject added successfully!");
                                location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        alert("An error occurred while updating the subject.");
                    }
                });
            }
        });


        // Handle Subject Deletion
        function deleteSubject(subjectId) {
            // Ensure modal exists before accessing it
            const deleteModal = new bootstrap.Modal(document.getElementById("deleteSubjectModal"));
            const deleteInput = document.getElementById("deleteSubjectId");

            if (deleteInput) {
                deleteInput.value = subjectId;
                deleteModal.show();
            } else {
                console.error("Delete subject input field not found.");
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const deleteSubjectForm = document.getElementById("deleteSubjectForm");

            if (deleteSubjectForm) {
                deleteSubjectForm.addEventListener("submit", function (event) {
                    event.preventDefault();

                    const formData = new FormData(this);

                    fetch("databases/delete_subject.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert("Subject deleted successfully!");
                            location.reload(); // Optionally reload the page to see updated data
                        } else {
                            alert("Error: " + (data.message || "Unknown error occurred"));
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("An error occurred while deleting the subject.");
                    });
                });
            } else {
                console.error("Delete subject form not found.");
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchSubject');
            const clearBtn = document.getElementById('clearBtn');
            const subjectsContainer = document.getElementById('subjectsContainer');

            // Filter subjects based on search input
            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                Array.from(subjectsContainer.children).forEach(row => {
                    const subjectName = row.children[2].textContent.toLowerCase();
                    const subjectCode = row.children[1].textContent.toLowerCase();

                    if (subjectName.includes(searchTerm) || subjectCode.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Clear search input and reset table
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                Array.from(subjectsContainer.children).forEach(row => {
                    row.style.display = '';
                });
            });
        });




    </script>
    


</body>
</html>