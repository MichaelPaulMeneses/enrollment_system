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
    <title>Sub-Admin - SJBPS Homepage Editor</title>
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
        .text-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .card-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            min-height: 150px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .card-container:hover {
            transform: scale(1.03);
        }
        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 22px;
            color: #555;
        }
        .metric-card {
            color: white;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 6px;
        }
        .metric-card.red { background-color: #e74c3c; }
        .metric-card.orange { background-color: #f39c12; }
        .metric-card.green { background-color: #2ecc71; }
        .metric-card.blue { background-color: #3498db; }
        .metric-card.navy { background-color: #34495e; }
        .metric-card.cyan { background-color: #00bcd4; }
        .metric-card.yellow { background-color: #f1c40f; }
        .metric-card.purple { background-color: #9b59b6; }
        .metric-card.teal { background-color: #1abc9c; }
        .metric-card.gray { background-color: #95a5a6; }
        .metric-card.pink { background-color: #e91e63; }
        .metric-card.brown { background-color: #8d6e63; }
        
        .card-action {
            font-size: 24px;
            font-weight: bold;
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
                            <i class="fas fa-scroll me-2"></i>Curriculum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub-admin-subjects.php">
                            <i class="fas fa-book-open me-2"></i>Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="sub-admin-homepage-editor.php">
                            <i class="fas fa-edit me-2"></i>Home Page Editor
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-md-4 pt-3">
                <div class="row">
                    <!-- First Row - 2 Cards -->
                    <div class="col-12 mb-4">
                        <h4 class="text-title text-align">
                            Main Page
                        </h4>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card-container">
                            <div class="card-title">Logo</div>
                            <div class="metric-card red" data-bs-toggle="modal" data-bs-target="#editLogoModal">
                                <div class="metric-value">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card-container">
                            <div class="card-title">School Name</div>
                            <div class="metric-card gray" data-bs-toggle="modal" data-bs-target="#editSchoolNameModal">
                                <div class="metric-value">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card-container">
                            <div class="card-title">Carousel</div>
                            <div class="metric-card orange" data-bs-toggle="modal" data-bs-target="#editCarouselModal">
                                <div class="metric-value">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Second Row - 2 Cards -->
                    <div class="col-12 mb-4">
                        <h4 class="text-title text-align">
                            Mission & Vission Page
                        </h4>
                    </div>

                    <div class="col-md-6 col-lg-6 mb-4">
                        <div class="card-container">
                            <div class="card-title">Mission</div>
                            <div class="metric-card green" data-bs-toggle="modal" data-bs-target="#editMissionModal">
                                <div class="metric-value">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-6 mb-4">
                        <div class="card-container">
                            <div class="card-title">Vision</div>
                            <div class="metric-card blue" data-bs-toggle="modal" data-bs-target="#editVisionModal">
                                <div class="metric-value">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Third Row - 2 Cards -->
                    <div class="col-12 mb-4">
                        <h4 class="text-title text-align">
                            School Gallery
                        </h4>
                    </div>

                    <div class="col-md-6 col-lg-6 mb-4">
                        <div class="card-container">
                            <div class="card-title">Folders</div>
                            <div class="metric-card navy" data-bs-toggle="modal" data-bs-target="#editFolderModal">
                                <div class="card-action">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mb-4">
                        <div class="card-container">
                            <div class="card-title">Pictures</div>
                            <div class="metric-card cyan" data-bs-toggle="modal" data-bs-target="#editGalleryModal">
                                <div class="card-action">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fourth Row - 3 Cards -->
                    <div class="col-12 mb-4">
                        <h4 class="text-title text-align">
                            Enrollment Procedure
                        </h4>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card-container">
                            <div class="card-title">Enrollment Important Information</div>
                            <div class="metric-card yellow" data-bs-toggle="modal" data-bs-target="#editEnrollmentInfoModal">
                                <div class="card-action">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card-container">
                        <div class="card-title">For Transferee/New Students</div>
                            <div class="metric-card purple" data-bs-toggle="modal" data-bs-target="#editTransfereeModal">
                                <div class="card-action">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6  col-lg-4 mb-4">
                        <div class="card-container">
                            <div class="card-title">For Old Students</div>
                            <div class="metric-card teal" data-bs-toggle="modal" data-bs-target="#editOldStudentsModal">
                                <div class="card-action">Edit</div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>

    <!-- Modal for Editing Logo -->
    <div class="modal fade" id="editLogoModal" tabindex="-1" aria-labelledby="editLogoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLogoModalLabel">Edit Logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="logoForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Display the current logo -->
                        <h6 >Current Logo:</h6>
                        <div class="text-center mb-3">
                            <img id="currentLogo" src="assets/homepage_images/logo/placeholder.png" alt="Current Logo" class="img-fluid rounded-circle" style="max-width: 150px; height: auto;">
                        </div>
                        <!-- File input -->
                        <div class="mb-3">
                            <label for="logoFile" class="form-label">Upload New Logo</label>
                            <input type="file" class="form-control" id="logoFile" name="logoFile" accept="image/*" required>
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

    <!-- Modal for Editing Carousel -->
    <div class="modal fade" id="editCarouselModal" tabindex="-1" aria-labelledby="editCarouselModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCarouselModalLabel">Edit Carousel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="carouselForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <h6>Current Carousel Images:</h6>
                        <div id="carouselImagesContainer" class="mb-3 text-center">
                            <!-- Existing images will be shown here -->
                        </div>

                        <h6>Add New Carousel Images:</h6>
                        <div id="carouselInputsContainer" class="mb-3">
                            <!-- Dynamic input fields will appear here -->
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let inputCount = 0;

            const inputContainer = document.getElementById("carouselInputsContainer");
            const imageContainer = document.getElementById("carouselImagesContainer");

            function createFileInput(index) {
                const wrapper = document.createElement("div");
                wrapper.className = "mb-3";

                const label = document.createElement("label");
                label.className = "form-label";
                label.setAttribute("for", `carouselFile${index}`);
                label.textContent = `Upload Image`;

                const input = document.createElement("input");
                input.type = "file";
                input.className = "form-control";
                input.id = `carouselFile${index}`;
                input.name = `carouselFile${index}`;
                input.accept = "image/*";

                input.addEventListener("change", () => {
                    if (input.value) {
                        inputCount++;
                        createFileInput(inputCount);
                    }
                });

                const small = document.createElement("small");
                small.className = "text-muted";
                small.textContent = "Select an image file to add to the carousel.";

                wrapper.appendChild(label);
                wrapper.appendChild(input);
                wrapper.appendChild(small);

                inputContainer.appendChild(wrapper);
            }

            function initializeInputs() {
                inputContainer.innerHTML = "";
                inputCount = 0;
                createFileInput(inputCount);
                inputCount++;
            }

            function loadCarouselImages() {
                fetch("databases/fetch_carousel.php")
                    .then(response => response.json())
                    .then(images => {
                        imageContainer.innerHTML = "";

                        if (Array.isArray(images) && images.length > 0) {
                            images.forEach(image => {
                                const imgWrapper = document.createElement("div");
                                imgWrapper.className = "d-inline-block position-relative me-2";

                                const img = document.createElement("img");
                                img.src = image.image_path;
                                img.className = "img-thumbnail";
                                img.style.width = "150px";

                                const deleteButton = document.createElement("button");
                                deleteButton.className = "btn btn-danger btn-sm position-absolute top-0 end-0";
                                deleteButton.innerHTML = "&times;";
                                deleteButton.addEventListener("click", function () {
                                    deleteImage(image.id, imgWrapper);
                                });

                                imgWrapper.appendChild(img);
                                imgWrapper.appendChild(deleteButton);
                                imageContainer.appendChild(imgWrapper);
                            });
                        }

                        initializeInputs();
                    })
                    .catch(error => {
                        console.error("Error loading images:", error);
                        imageContainer.innerHTML = "<p class='text-danger'>Failed to load images.</p>";
                        initializeInputs();
                    });
            }

            function deleteImage(imageId, imgWrapper) {
                if (confirm("Are you sure you want to delete this image?")) {
                    const formData = new FormData();
                    formData.append("image_id", imageId);

                    fetch("databases/delete_carousel_image.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            imgWrapper.remove();
                        } else {
                            alert("Failed to delete image.");
                        }
                    })
                    .catch(error => {
                        console.error("Error deleting image:", error);
                        alert("An error occurred while deleting the image.");
                    });
                }
            }


            loadCarouselImages();

            document.getElementById("carouselForm").addEventListener("submit", function (event) {
                event.preventDefault();

                const formData = new FormData(this);

                fetch("databases/edit_carousel.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        loadCarouselImages();

                        const modalEl = document.getElementById("editCarouselModal");
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        this.reset();
                        initializeInputs();

                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error("Error submitting form:", error);
                    alert("An error occurred while uploading images.");
                });
            });
        });
    </script>

    <!-- Modal for Editing School Name -->
    <div class="modal fade" id="editSchoolNameModal" tabindex="-1" aria-labelledby="editSchoolNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSchoolNameModalLabel">Edit School Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="schoolNameForm">
                    <div class="modal-body">
                        <!-- Input field for editing school name -->
                        <div class="mb-3">
                            <label for="schoolNameInput" class="form-label">School Name</label>
                            <input type="text" class="form-control" id="schoolNameInput" name="schoolName" required>
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

    <!-- Modal for Editing Mission -->
    <div class="modal fade" id="editMissionModal" tabindex="-1" aria-labelledby="editMissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMissionModalLabel">Edit Mission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="missionForm">
                    <div class="modal-body">
                        <!-- Textarea for editing mission -->
                        <div class="mb-3">
                            <label for="missionText" class="form-label">Mission Statement</label>
                            <textarea class="form-control" id="missionText" name="missionText" rows="10" required></textarea>
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

    <!-- Modal for Editing Vision -->
    <div class="modal fade" id="editVisionModal" tabindex="-1" aria-labelledby="editVisionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVisionModalLabel">Edit Vision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="visionForm">
                    <div class="modal-body">
                        <!-- Textarea for editing vision -->
                        <div class="mb-3">
                            <label for="visionText" class="form-label">Vision Statement</label>
                            <textarea class="form-control" id="visionText" name="visionText" rows="10" required></textarea>
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
    

<!-- Modal for Managing Folders (Galleries) -->
<div class="modal fade" id="editFolderModal" tabindex="-1" aria-labelledby="editFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFolderModalLabel">Manage Folders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="folderForm">
                <div class="modal-body">
                    <!-- Existing folders section with table -->
                    <h6>Existing Folders:</h6>
                    <table class="table table-bordered" id="existingFoldersTable">
                        <thead>
                            <tr>
                                <th>Folder Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="existingFoldersContainer">
                            <!-- Existing folders will be populated here -->
                        </tbody>
                    </table>

                    <hr>

                    <!-- Add new folders -->
                    <h6>Add New Folders:</h6>
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="folderName" name="folderNames[]" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Folders</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const inputContainer = document.getElementById("newFoldersContainer");
        const existingFoldersContainer = document.getElementById("existingFoldersContainer");
        const folderForm = document.getElementById("folderForm");
        const modal = document.getElementById("editFolderModal");

        // Load existing folders from the database
        const loadExistingFolders = () => {
            fetch("databases/fetch_existing_folders.php")
                .then(res => res.json())
                .then(folders => {
                    existingFoldersContainer.innerHTML = "";

                    if (Array.isArray(folders) && folders.length > 0) {
                        folders.forEach(folder => {
                            const row = document.createElement("tr");

                            row.innerHTML = `
                                <td>${folder.folder_name}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm">Edit</button>
                                    <button class="btn btn-danger btn-sm ms-2">Delete</button>
                                </td>
                            `;

                            const [editBtn, deleteBtn] = row.querySelectorAll("button");

                            editBtn.addEventListener("click", () => editFolder(folder.folder_id, folder.folder_name));
                            deleteBtn.addEventListener("click", () => deleteFolderById(folder.folder_id, row));

                            existingFoldersContainer.appendChild(row);
                        });
                    } else {
                        existingFoldersContainer.innerHTML = "<tr><td colspan='2'>No folders found.</td></tr>";
                    }
                })
                .catch(err => {
                    console.error("Error loading folders:", err);
                    existingFoldersContainer.innerHTML = "<tr><td colspan='2' class='text-danger'>Failed to load folders.</td></tr>";
                });
        };

        // Handle form submission for adding new folders
        folderForm.addEventListener("submit", e => {
            e.preventDefault();
            const formData = new FormData(folderForm);

            fetch("databases/add_folder.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    loadExistingFolders();
                    folderForm.reset();
                }
            })
            .catch(err => {
                console.error("Error submitting form:", err);
                alert("An error occurred while adding folders.");
            });
        });

        // Edit folder
        const editFolder = (id, currentName) => {
            const newName = prompt("Edit Folder Name:", currentName);
            if (newName && newName !== currentName) {
                const formData = new FormData();
                formData.append("folder_id", id);
                formData.append("folder_name", newName);

                fetch("databases/edit_folder.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        loadExistingFolders();
                    } else {
                        alert("Failed to edit folder.");
                    }
                })
                .catch(err => {
                    console.error("Error editing folder:", err);
                    alert("An error occurred while editing the folder.");
                });
            }
        };

        const deleteFolderById = (folderId, rowElement, callback = null) => {
            if (!confirm("Are you sure you want to delete this folder?")) return;

            const formData = new FormData();
            formData.append("folder_id", folderId);

            console.log("Deleting folder with ID:", folderId);

            fetch("databases/delete_folder.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    rowElement.remove();
                    if (typeof callback === "function") callback(true, data);
                } else {
                    if (typeof callback === "function") callback(false, data);
                }
            })
            .catch(error => {
                console.error("Delete folder error:", error);
                alert("An error occurred while deleting the folder.");
                if (typeof callback === "function") callback(false, error);
            });
        };

        // Initialize Bootstrap modal event if needed
        if (modal) {
            modal.addEventListener("shown.bs.modal", () => {
                console.log("Modal is shown");
            });
        }

        // Load folders on initial page load
        loadExistingFolders();
    });

</script>

<!-- Gallery Management Modal -->
<div class="modal fade" id="editGalleryModal" tabindex="-1" aria-labelledby="editGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGalleryModalLabel">Manage Pictures</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="galleryForm" enctype="multipart/form-data">
                <div class="modal-body">
                    
                    <!-- Existing Pictures Display -->
                    <div id="existingPicturesContainer" class="mb-3 text-center"></div>

                    <hr>

                    <!-- Add New Pictures Section -->
                    <h6>Add New Pictures:</h6>
                    <!-- Folder Select (for uploading) -->
                    <div class="mb-3">
                        <label for="folderSelect" class="form-label">Select Folder</label>
                        <select class="form-select" id="folderSelect" name="folderSelect" required></select>
                    </div>

                    <div id="newPicturesContainer" class="mb-3"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const existingPicturesContainer = document.getElementById("existingPicturesContainer");
    const newPicturesContainer = document.getElementById("newPicturesContainer");
    const galleryForm = document.getElementById("galleryForm");

    let pictureInputCount = 0;
    
    // Fetch available folders for selection
    fetch('databases/fetch_all_folders.php')  // Replace with the actual path
        .then(response => response.json())
        .then(folders => {
            const folderSelect = document.getElementById('folderSelect');

            folderSelect.innerHTML = ''; // Clear existing options
            // Check if folders are found
            if (folders.length > 0) {
                folders.forEach(folder => {
                    const option1 = document.createElement('option');
                    option1.value = folder.folder_id;
                    option1.textContent = folder.folder_name;
                    folderSelect.appendChild(option1);
                });

                // Add event listener to load pictures when folderSelect is changed
                folderSelect.addEventListener("change", function () {
                    const selectedFolder = folderSelect.value;
                    loadExistingPictures(selectedFolder);  // Pass the selected folder
                });

                // Load pictures for the default selected folder on page load for both selects
                const defaultFolder = folderSelect.value;
                if (defaultFolder) {
                    loadExistingPictures(defaultFolder);
                }
            } else {
                const option1 = document.createElement('option1');
                option1.value = '';
                option1.textContent = 'No folders available';
                folderSelect.appendChild(option1);
            }
        })
        .catch(error => {
            console.error('Error fetching folders:', error);
        });

    // Load existing pictures for the selected folder
    const loadExistingPictures = (folderId) => {
        if (!folderId) return;

        fetch(`databases/fetch_gallery_picture.php?folder_id=${folderId}`)
            .then(res => res.json())
            .then(pictures => {
                existingPicturesContainer.innerHTML = "";

                if (Array.isArray(pictures) && pictures.length > 0) {
                    pictures.forEach(picture => {
                        const wrapper = document.createElement("div");
                        wrapper.className = "d-inline-block position-relative me-2 mb-2";

                        const img = document.createElement("img");
                        img.src = picture.image_path;
                        img.className = "img-thumbnail";
                        img.style.width = "150px";

                        const deleteBtn = document.createElement("button");
                        deleteBtn.type = "button";
                        deleteBtn.className = "btn btn-danger btn-sm position-absolute top-0 end-0";
                        deleteBtn.innerHTML = "&times;";
                        deleteBtn.onclick = () => {
                            console.log("Image in folder:",picture.image_id);
                            deleteImageFolder(picture.image_id, wrapper);
                        };

                        wrapper.appendChild(img);
                        wrapper.appendChild(deleteBtn);
                        existingPicturesContainer.appendChild(wrapper);
                    });
                } else {
                    existingPicturesContainer.innerHTML = "<p class='text-muted'>No pictures found.</p>";
                }

                initializePictureInputs();
            })
            .catch(err => {
                console.error("Error loading pictures:", err);
                existingPicturesContainer.innerHTML = "<p class='text-danger'>Failed to load pictures.</p>";
                initializePictureInputs();
            });
    };

    function deleteImageFolder(imageId, imgWrapper) {
        if (!confirm("Are you sure you want to delete this picture?")) return;

        const formData = new FormData();
        formData.append("image_id", imageId);

        fetch("databases/delete_gallery_picture.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                imgWrapper.remove();
            } else {
                alert("Failed to delete image.");
            }
        })
        .catch(error => {
            console.error("Error deleting image:", error);
            alert("An error occurred while deleting the image.");
        });
    }

    // Initialize picture input fields
    const initializePictureInputs = () => {
        newPicturesContainer.innerHTML = "";
        pictureInputCount = 0;
        createPictureInput(pictureInputCount);
        pictureInputCount++;
    };

    // Function to create picture input fields
    const createPictureInput = (index) => {
        const wrapper = document.createElement("div");
        wrapper.className = "mb-3";

        const label = document.createElement("label");
        label.className = "form-label";
        label.setAttribute("for", `pictureFile${index}`);
        label.textContent = `Upload Picture`;

        const input = document.createElement("input");
        input.type = "file";
        input.className = "form-control";
        input.id = `pictureFile${index}`;
        input.name = `pictureFile${index}`;
        input.accept = "image/*";

        console.log(index);

        if (index === 0) {
            input.required = true; // Make the first input required
        }

        input.addEventListener("change", () => {
            if (input.value) {
                pictureInputCount++;
                createPictureInput(pictureInputCount);
            }
        });

        wrapper.appendChild(label);
        wrapper.appendChild(input);
        newPicturesContainer.appendChild(wrapper);
    };

    document.getElementById("galleryForm").addEventListener("submit", function(event) {
        event.preventDefault();

        console.log("This is the folderId:",  document.getElementById("folderSelect").value);

        const form = event.target;
        const formData = new FormData();

        const folderId = document.getElementById("folderSelect").value;
        if (!folderId) {
            alert("Please select a folder.");
            return;
        }

        formData.append("folder_id", folderId);

        const fileInputs = form.querySelectorAll('input[type="file"]');
        let hasFile = false;

        fileInputs.forEach((input, index) => {
            if (input.files.length > 0) {
                formData.append("file" + index, input.files[0]);
                hasFile = true;
            }
        });

        fetch("databases/add_gallery_picture.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(result => {
            alert(result);
            form.querySelectorAll('input[type="file"]').forEach(input => input.value = '');
            loadExistingPictures(folderId); 
        })
        .catch(err => {
            alert("Error uploading: " + err);
        });
    });
});
</script>





    <!-- Modal for Editing Enrollment Important Information -->
    <div class="modal fade" id="editEnrollmentInfoModal" tabindex="-1" aria-labelledby="editEnrollmentInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEnrollmentInfoModalLabel">Edit Enrollment Important Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="enrollmentInfoForm">
                    <div class="modal-body">
                        <!-- Textarea for editing enrollment information -->
                        <div class="mb-3">
                            <label for="enrollmentInfoText" class="form-label">Enrollment Important Information</label>
                            <textarea class="form-control" id="enrollmentInfoText" name="enrollmentInfoText" rows="10" required></textarea>
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

    <!-- Modal for Editing For Transferee/New Students -->
    <div class="modal fade" id="editTransfereeModal" tabindex="-1" aria-labelledby="editTransfereeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTransfereeModalLabel">Edit Information for Transferee/New Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="transfereeForm">
                    <div class="modal-body">
                        <!-- Textarea for editing transferee/new students information -->
                        <div class="mb-3">
                            <label for="transfereeText" class="form-label">Information for Transferee/New Students</label>
                            <textarea class="form-control" id="transfereeText" name="transfereeText" rows="10" required></textarea>
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

    <!-- Modal for Editing For Old Students -->
    <div class="modal fade" id="editOldStudentsModal" tabindex="-1" aria-labelledby="editOldStudentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOldStudentsModalLabel">Edit Information for Old Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="oldStudentsForm">
                    <div class="modal-body">
                        <!-- Textarea for editing old students information -->
                        <div class="mb-3">
                            <label for="oldStudentsText" class="form-label">Information for Old Students</label>
                            <textarea class="form-control" id="oldStudentsText" name="oldStudentsText" rows="10" required></textarea>
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

    


    
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <!-- script to handle logo upload and display -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var editLogoModal = document.getElementById("editLogoModal");

            // Fetch and display current logo when modal opens
            editLogoModal.addEventListener("show.bs.modal", function () {
                fetch("databases/fetch_logo.php")
                    .then(response => response.json())
                    .then(data => {
                        let logoImg = document.getElementById("currentLogo");
                        if (data.status === "success" && data.image) {
                            logoImg.src = data.image; // Load logo from database
                        } else {
                            console.error("Error:", data.message);
                            logoImg.src = "assets/homepage_images/logo/placeholder.png"; // Default placeholder
                        }
                    })
                    .catch(error => console.error("Error fetching logo:", error));
            });

            // AJAX Upload Form Submission
            document.getElementById("logoForm").addEventListener("submit", function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch("databases/edit_logo.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        document.getElementById("currentLogo").src = data.image; // Update the logo preview
                        
                        // Close the modal
                        var modalInstance = bootstrap.Modal.getInstance(editLogoModal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }

                        // Clear the file input fields after submission
                        document.querySelectorAll("#logoForm input[type='file']").forEach(input => {
                            input.value = "";

                        alert("Logo uploaded successfully!");

                        // Refresh the sub-admin-homepage-editor.php page
                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                        });
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Upload error:", error));
            });
        });
    </script>


    <!-- Script to handle School Name edit -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const schoolNameModal = document.getElementById("editSchoolNameModal");
            const schoolNameInput = document.getElementById("schoolNameInput");
            const schoolNameForm = document.getElementById("schoolNameForm");

            // Fetch and display current school name when the modal opens
            schoolNameModal.addEventListener("show.bs.modal", function () {
                fetch("databases/fetch_school_name.php")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            schoolNameInput.value = data.school_name;
                        } else {
                            console.error("Error:", data.message);
                            alert("Error fetching school name: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching school name:", error);
                        alert("Failed to load school name. Check your connection.");
                    });
            });

            // Handle form submission for updating school name
            schoolNameForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(schoolNameForm);

                fetch("databases/edit_school_name.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        // Close the modal after successful update
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(schoolNameModal);
                        modalInstance.hide();

                        // Refresh the sub-admin-homepage-editor.php page
                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error("Error updating school name:", error);
                    alert("Failed to update school name.");
                });
            });
        });
    </script>   

    

    <!-- Script to handle mission editing -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const missionModal = document.getElementById("editMissionModal");
            const missionText = document.getElementById("missionText");
            const missionForm = document.getElementById("missionForm");

            // Fetch and display current mission when the modal opens
            missionModal.addEventListener("show.bs.modal", function () {
                fetch("databases/fetch_mission.php")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            missionText.value = data.mission;
                        } else {
                            console.error("Error:", data.message);
                            alert("Error fetching mission: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching mission:", error);
                        alert("Failed to load mission. Check your connection.");
                    });
            });

            // Handle form submission for updating mission
            missionForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(missionForm);

                fetch("databases/edit_mission.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        // Close the modal after successful update
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(missionModal);
                        modalInstance.hide();

                        // Refresh the sub-admin-homepage-editor.php page
                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error("Error updating mission:", error);
                    alert("Failed to update mission.");
                });
            });
        });
    </script>

    <!-- Script to handle vision editing -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const visionModal = document.getElementById("editVisionModal");
            const visionText = document.getElementById("visionText");
            const visionForm = document.getElementById("visionForm");

            // Fetch and display current vision when the modal opens
            visionModal.addEventListener("show.bs.modal", function () {
                fetch("databases/fetch_vision.php")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            visionText.value = data.vision;
                        } else {
                            console.error("Error:", data.message);
                            alert("Error fetching vision: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching vision:", error);
                        alert("Failed to load vision. Check your connection.");
                    });
            });

            // Handle form submission for updating vision
            visionForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(visionForm);

                fetch("databases/edit_vision.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        // Close the modal after successful update
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(visionModal);
                        modalInstance.hide();

                        // Refresh the sub-admin-homepage-editor.php page
                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error("Error updating vision:", error);
                    alert("Failed to update vision.");
                });
            });
        });
    </script>

    

    <!-- Script to handle enrollment information editing -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const enrollmentInfoModal = document.getElementById("editEnrollmentInfoModal");
            const enrollmentInfoText = document.getElementById("enrollmentInfoText");
            const enrollmentInfoForm = document.getElementById("enrollmentInfoForm");

            // Fetch and display current enrollment information when the modal opens
            enrollmentInfoModal.addEventListener("show.bs.modal", function () {
                fetch("databases/fetch_enrollment_info.php")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            enrollmentInfoText.value = data.info;
                        } else {
                            console.error("Error:", data.message);
                            alert("Error fetching enrollment information: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching enrollment information:", error);
                        alert("Failed to load enrollment information. Check your connection.");
                    });
            });

            // Handle form submission for updating enrollment information
            enrollmentInfoForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(enrollmentInfoForm);

                fetch("databases/edit_enrollment_info.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        // Close the modal after successful update
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(enrollmentInfoModal);
                        modalInstance.hide();

                        // Refresh the sub-admin-homepage-editor.php page
                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error("Error updating enrollment information:", error);
                    alert("Failed to update enrollment information.");
                });
            });
        });
    </script>
    
    <!-- Script to handle transferee/new students information editing -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const transfereeModal = document.getElementById("editTransfereeModal");
            const transfereeText = document.getElementById("transfereeText");
            const transfereeForm = document.getElementById("transfereeForm");

            // Fetch and display current information when the modal opens
            transfereeModal.addEventListener("show.bs.modal", function () {
                fetch("databases/fetch_transferee_req_info.php")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            transfereeText.value = data.info;
                        } else {
                            console.error("Error:", data.message);
                            alert("Error fetching information: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching information:", error);
                        alert("Failed to load information. Check your connection.");
                    });
            });

            // Handle form submission for updating information
            transfereeForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(transfereeForm);

                fetch("databases/edit_transferee_req_info.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        // Close the modal after successful update
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(transfereeModal);
                        modalInstance.hide();

                        // Refresh the sub-admin-homepage-editor.php page
                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error("Error updating information:", error);
                    alert("Failed to update information.");
                });
            });
        });
    </script>

    <!-- Script to handle old students information editing -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const oldStudentsModal = document.getElementById("editOldStudentsModal");
            const oldStudentsText = document.getElementById("oldStudentsText");
            const oldStudentsForm = document.getElementById("oldStudentsForm");

            // Fetch and display current information when the modal opens
            oldStudentsModal.addEventListener("show.bs.modal", function () {
                fetch("databases/fetch_old_students_req_info.php")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            oldStudentsText.value = data.info;
                        } else {
                            console.error("Error:", data.message);
                            alert("Error fetching information: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching information:", error);
                        alert("Failed to load information. Check your connection.");
                    });
            });

            // Handle form submission for updating information
            oldStudentsForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(oldStudentsForm);

                fetch("databases/edit_old_students_req_info.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        // Close the modal after successful update
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(oldStudentsModal);
                        modalInstance.hide();

                        // Refresh the sub-admin-homepage-editor.php page
                        setTimeout(() => {
                            window.location.href = "sub-admin-homepage-editor.php";
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error("Error updating information:", error);
                    alert("Failed to update information.");
                });
            });
        });
    </script>


</body>
</html>
