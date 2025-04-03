<?php

session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if grade level is sent via POST and store it in a session variable
include "databases/db_connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["grade_level_id"])) {
    $grade_level_id = $_POST["grade_level_id"];

    $query = "SELECT * FROM grade_levels WHERE grade_level_id = ?";
        
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $grade_level_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();

    } else {
        die("Grade Level not found.");
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
    <title>SJBPS Admin - Grade and Sections</title>
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
                        <a class="nav-link" href="admin-appointments.php">
                            <i class="fas fa-calendar-check me-2"></i>Appointments
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
                        <a class="nav-link" href="#">
                            <i class="fas fa-money-check-alt me-2"></i>Payment Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-all-enrollees.php">
                            <i class="fas fa-users me-2"></i>All Enrollees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-grade-section.php">
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
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0">Sections</h4>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="ms-auto">
                            <div class="col">
                                <!--<label for="schoolYearSelect" class="form-label mb-0 me-2">School Year:</label> -->
                                <select id="schoolYearSelect" class="form-select school-year-select"> 
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center mb-4 gap-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                        Add Section
                    </button>
                    <!-- Search Bar -->
                    <div class="search-container">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" id="searchSection" class="form-control" placeholder="Search applications" aria-label="Search">
                            <button class="btn btn-outline-secondary" type="button" id="clearBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sections Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Section Name</th>
                                <th>Grade Level</th>
                                <th>School Year</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sectionsContainer">
                            <!-- Dynamic Content will be inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Add Section Modal -->
                <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSectionModalLabel">Add Section</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addSectionForm">
                                        <div class="mb-3">
                                            <label for="sectionName" class="form-label">Section Name</label>
                                            <input type="text" class="form-control" id="sectionName" name="sectionName" required>
                                        </div>
                                        <input type="hidden" name="gradeLevelId" value="<?php echo $grade_level_id; ?>">
                                        <div class="mb-3">
                                            <label for="addSchoolYearId" class="form-label">School Year</label>
                                            <select class="form-select" id="addSchoolYearId" name="addSchoolYearId" required>
                                                <!-- Options will be populated dynamically -->
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
                </div>

                <div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSectionModalLabel">Edit Section</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editSectionForm">
                                    <input type="hidden" id="editSectionId" name="sectionId">
                                    <div class="mb-3">
                                        <label for="editSectionName" class="form-label">Section Name</label>
                                        <input type="text" class="form-control" id="editSectionName" name="editSectionName" required>
                                    </div>
                                    <input type="hidden" name="editGradeLevel" value="<?php echo $grade_level_id; ?>"> <!-- If you want it to be set here -->
                                    <div class="mb-3">
                                        <label for="editschoolYearId" class="form-label">School Year</label>
                                        <select class="form-select" id="editschoolYearId" name="editschoolYearId" required>
                                            <!-- Options will be populated dynamically -->
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Section Modal -->
                <div class="modal fade" id="deleteSectionModal" tabindex="-1" aria-labelledby="deleteSectionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteSectionModalLabel">Delete Section</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this section?</p>
                                <input type="hidden" id="deleteSectionId">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteSection">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const gradeLevelId = <?php echo $grade_level_id; ?>;
            const sectionsContainer = document.getElementById('sectionsContainer');
            const schoolYearSelect = document.getElementById("schoolYearSelect");

            const curriculumContainer = document.getElementById("curriculumContainer"); 

            // Search Bar Method
            const searchInput = document.getElementById("searchSection");

            searchInput.addEventListener("input", function () {
                const searchTerm = searchInput.value.toLowerCase();

                Array.from(sectionsContainer.children).forEach(row => {
                    const sectionName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                    if (sectionName.includes(searchTerm)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });

            // Fetch and populate school years
            fetch("databases/school_years.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success" && Array.isArray(data.schoolYears)) {
                        data.schoolYears.forEach(year => {
                            const option = document.createElement("option");
                            option.value = year.school_year_id;
                            option.textContent = year.school_year;
                            schoolYearSelect.appendChild(option);

                            if (schoolYearSelect.value) {
                                fetchSections(gradeLevelId, schoolYearSelect.value);
                            }
                        });
                    } else {
                        console.error("Error fetching school years:", data.message);
                    }
                })
                .catch(error => console.error("Error fetching school years:", error));

            // Fetch sections when school year is changed
            schoolYearSelect.addEventListener("change", () => {
                fetchSections(gradeLevelId, schoolYearSelect.value);
            });

            // Function to fetch and display sections
            function fetchSections(gradeLevelId, schoolYearId) {
                if (!schoolYearId) return;

                fetch(`databases/fetch_sections_for_display.php?grade_level_id=${gradeLevelId}&school_year_id=${schoolYearId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error("Error fetching sections:", data.error);
                            return;
                        }

                        sectionsContainer.innerHTML = '';
                        data.forEach((section, index) => {
                            console.log(section.section_id);
                            console.log(section.section_name);
                            console.log(section.grade_name);
                            console.log(section.school_year);
                            sectionsContainer.innerHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${section.section_name}</td>
                                    <td>${section.grade_name}</td>
                                    <td>${section.school_year}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" 
                                            data-id="${section.section_id}" 
                                            data-name="${section.section_name}" 
                                            data-level="${section.grade_name}"
                                            data-year="${section.school_year}">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSection(${section.section_id})">Delete</button>
                                    </td>
                                </tr>`;
                        });

                        // Reattach event listener to dynamically created edit buttons
                        const editButtons = sectionsContainer.querySelectorAll(".edit-btn");
                        editButtons.forEach(button => {
                            button.addEventListener("click", function () {
                                const sectionId = this.getAttribute("data-id");
                                const sectionName = this.getAttribute("data-name");
                                const editGradeLevelId = this.getAttribute("data-level");
                                const editSchoolYearId = this.getAttribute("data-year");

                                // Debugging output to confirm values are being passed correctly
                                console.log("Section ID:", sectionId);
                                console.log("Section Name:", sectionName);
                                console.log("Edit Grade Level ID:", editGradeLevelId);
                                console.log("Edit School Year ID:", editSchoolYearId);

                                // Populate the edit modal
                                document.getElementById("editSectionId").value = sectionId;
                                document.getElementById("editSectionName").value = sectionName;
                                // Show the modal
                                const editModal = new bootstrap.Modal(document.getElementById("editSectionModal"));
                                editModal.show();
                            });
                        });
                    })
                    .catch(error => console.error("Error fetching sections:", error));
            }

            
            // Fetch school years
            const addSchoolYearId = document.getElementById("addSchoolYearId");
            const editSchoolYearId = document.getElementById("editschoolYearId"); // Fixed incorrect ID

            // Clear existing options before populating
            addSchoolYearId.innerHTML = "";
            editSchoolYearId.innerHTML = "";

            // Fetch school years
            fetch("databases/school_years.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success" && Array.isArray(data.schoolYears)) {
                        data.schoolYears.forEach(year => {
                            // Create separate option elements for each dropdown
                            const addOption = document.createElement("option");
                            addOption.value = year.school_year_id;
                            addOption.textContent = year.school_year;

                            const editOption = addOption.cloneNode(true); // Clone for edit dropdown

                            addSchoolYearId.appendChild(addOption);
                            editSchoolYearId.appendChild(editOption);
                        });
                    } else {
                        console.error("Error fetching school years:", data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        });

        document.getElementById("addSectionForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);

            fetch("databases/insert_sections.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Section added successfully!");
                    location.reload(); // Reload page to update data
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });

        // Handle curriculum editing
        document.getElementById("editSectionForm").addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            console.log("FormData contents:");
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            fetch("databases/edit_sections.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Curriculum updated successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        });

        function deleteSection(sectionId) {
            document.getElementById("deleteSectionId").value = sectionId;
            const deleteModal = new bootstrap.Modal(document.getElementById("deleteSectionModal"));
            deleteModal.show();
        }

        document.getElementById("confirmDeleteSection").addEventListener("click", function () {
            const sectionId = document.getElementById("deleteSectionId").value;

            const formData = new FormData();
            formData.append("section_id", sectionId); // Corrected data format

            fetch("databases/delete_section.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Section deleted successfully!");
                    location.reload(); // Reload page to update data
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });

    </script>




</body>
</html>