<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SJBPS - Homepage</title>
    <link rel="icon" type="image/png" href="assets/main/logo/st-johns-logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background-color: #4a90e2;
            color: white;
            padding: 10px 0;
            position: relative;
        }
        .logo-text {
            font-weight: bold;
            font-size: 1rem;
        }
        
        /* Responsive Logo and School Name */
        .logo-container {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            .logo-text {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .logo-text {
                display: inline !important;
                font-size: 0.7rem;
            }
        }
        
        /* Navigation Styles */
        .top-links {
            font-size: 0.9rem;
        }
        .top-links a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .top-links a:hover {
            opacity: 0.8;
        }

        /* Responsive Navigation */
        .navbar-toggler {
            color: white;
            border: none;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Social Media Icons */
        .social-media-container {
            background-color: #4a90e2;
            padding: 30px;
            color: white;
        }

        .social-media-icons {
            display: flex;
            justify-content: flex-end;
            gap: 30px;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: white;
            color: #4a90e2;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .social-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 8px rgba(0,0,0,0.2);
        }

        .social-icon i {
            font-size: 30px;
        }

        .social-icon.facebook:hover {
            color: #3b5998;
        }

        .social-icon.instagram:hover {
            color: #e1306c;
        }

        /* Mobile Styles */
        @media (max-width: 991px) {
            .top-links {
                display: none;
            }
            .navbar-toggler {
                display: block !important;
            }
            .mobile-menu {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: #4a90e2;
                z-index: 1000;
                display: none;
            }
            .mobile-menu.show {
                display: block;
            }
            .mobile-menu a {
                color: white;
                display: block;
                padding: 15px;
                text-decoration: none;
                border-top: 1px solid rgba(255,255,255,0.1);
            }
            .mobile-menu a:first-child {
                border-top: none;
            }
            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }
        }

        /* Desktop Styles */
        @media (min-width: 992px) {
            .navbar-toggler {
                display: none !important;
            }
            .mobile-menu {
                display: none !important;
            }
        }

        /* Existing styles */
        .banner {
            position: relative;
            background-color: white;
            padding: 0;
            border-radius: 0;
            box-shadow: 0 10px 10px rgba(0,0,0,0.1);
        }
        .call-to-action {
            text-align: center;
            margin: 40px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .enroll-btn {
            background-color: #25478a;
            color: white;
            padding: 8px 25px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .enroll-btn:hover {
            background-color: #1a365d;
            color: white;
        }
        .mission-vision {
            background-color: #4a90e2;
            color: white;
            border-radius: 10px;
            margin-top: 20px;
            padding: 20px;
        }
        .mission-vision-box {
            background-color: white;
            color: black;
            border-radius: 10px;
            padding: 15px;
            height: 100%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .mission-vision-box:hover {
            transform: translateY(-5px);
        }

        /* Footer Styles */
        .footer {
            background-color: #4a90e2;
            color: white;
            padding: 20px 0;
            margin-top: 20px;
        }
        @media (max-width: 767px) {
            .footer .row {
                text-align: center;
            }
        }
        .enrollment-highlight {
            background-color: #f8f9fa;
            border-left: 4px solid #4a90e2;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 5px 5px 0;
        }
        .procedure-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
            transition: box-shadow 0.3s;
        }
        .procedure-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .call-to-action {
            text-align: center;
            margin: 40px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .gallery-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            max-width: 500px;
            max-height: 500px;
            width: 100%;
            height: 100%;
            aspect-ratio: 1 / 1;
        }
        .gallery-card:hover {
            transform: scale(1.05);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>

    <!--for the mobile menu toggle-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.navbar-toggler');
            const mobileMenu = document.querySelector('.mobile-menu');

            hamburger.addEventListener('click', function() {
                mobileMenu.classList.toggle('show');
                this.classList.toggle('active');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!hamburger.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.remove('show');
                    hamburger.classList.remove('active');
                }
            });

            // Prevent mobile menu from closing when clicking inside it
            mobileMenu.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>

    <!-- Fetch the logo from the database and display it in the navbar -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("databases/fetch_logo.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success" && data.image) {
                        document.getElementById("navLogo").src = data.image;
                        document.getElementById("footerLogo").src = data.image; // Ensure footer updates too
                    } else {
                        console.error("Error:", data.message);
                    }
                })
                .catch(error => console.error("Error fetching logo:", error));

            //  Fetch the School Name from the database and display it
            fetch("databases/fetch_school_name.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        document.getElementById("schoolName").textContent = data.school_name;
                        document.getElementById("schoolNameAction").textContent = data.school_name;
                        document.getElementById("schoolNameFooter").textContent = data.school_name;
                        document.getElementById("schoolNameRigthsReserves").textContent = data.school_name;


                    } else {
                        document.getElementById("schoolName").textContent = "No Name";
                        document.getElementById("schoolNameAction").textContent = "No Name";
                        document.getElementById("schoolNameFooter").textContent = "No Name";
                        document.getElementById("schoolNameRigthsReserves").textContent = "No Name";
                    }
                })
                .catch(error => {
                    console.error("Error fetching school name:", error);
                    document.getElementById("schoolName").textContent = "No Name";
                    document.getElementById("schoolNameAction").textContent = "No Name";
                    document.getElementById("schoolNameFooter").textContent = "No Name";
                    document.getElementById("schoolNameRigthsReserves").textContent = "No Name";
                });
        });

    </script>
</head>
<body>
    <!-- Header -->
    <div class="header position-relative">
        <div class="container">
            <div class="header-content d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img id="navLogo" src="assets/homepage_images/logo/placeholder.png" alt="School Logo" width="75" class="me-2 rounded-circle">
                    <span id="schoolName" class="logo-text d-md-inline logo-container">Loading...</span>

                </div>
                
                <!-- Desktop Navigation -->
                <div class="top-links d-none d-lg-flex flex-wrap justify-content-end">
                    <a href="#procedures" class="me-2 mb-2"><i class="fas fa-list-ul me-1"></i> Enrollment Procedures</a>
                    <a href="admission-form.php" class="me-2 mb-2"><i class="fas fa-pen-to-square me-1"></i> Enroll Now</a>
                    <a href="login.php" class="me-2 mb-2"><i class="fas fa-sign-in-alt me-1"></i> Log In</a>
                </div>
                
                <!-- Mobile Hamburger Menu -->
                <button class="navbar-toggler d-lg-none" type="button" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"><i class="fas fa-bars text-white"></i></span>
                </button>
            </div>
            
            <!-- Mobile Menu Dropdown -->
            <div class="mobile-menu">
                <a href="#procedures"><i class="fas fa-list-ul me-2"></i>Enrollment Procedures</a>
                <a href="admission-form.php"><i class="fas fa-pen-to-square me-2"></i>Enroll Now</a>
                <a href="login.php"><i class="fas fa-sign-in-alt me-2"></i>Log In</a>
            </div>
        </div>
    </div>

    <!-- Banner Carousel -->
    <div id="bannerCarousel" class="carousel slide container banner" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators" id="carouselIndicators">
            <!-- JS will inject indicator buttons here -->
        </div>

        <!-- Slides -->
        <div class="carousel-inner" id="carouselContainer">
            <!-- JS will inject <div class="carousel-item"> here -->
        </div>

        <!-- Controls (Moved outside of indicators!) -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Load the carousel images dynamically -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        loadBannerCarousel();
    });

    function loadBannerCarousel() {
        fetch("databases/fetch_carousel.php")
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then(images => {
                const container = document.getElementById("carouselContainer");
                const indicators = document.getElementById("carouselIndicators");

                container.innerHTML = "";
                indicators.innerHTML = "";

                if (!images || images.length === 0) {
                    container.innerHTML = `
                        <div class="carousel-item active">
                            <img src="assets/homepage_images/logo/placeholder.png"
                                class="d-block w-100"
                                style="height:50vh; object-fit:cover;"
                                alt="Default Banner">
                        </div>`;
                    return;
                }

                images.forEach((image, idx) => {
                    // Slide
                    const item = document.createElement("div");
                    item.className = `carousel-item${idx === 0 ? " active" : ""}`;
                    item.innerHTML = `
                        <img src="${image.image_path}"
                            class="d-block w-100"
                            style="height:50vh; object-fit:cover;"
                            alt="Banner ${idx + 1}">`;
                    container.appendChild(item);

                    // Indicator
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.setAttribute("data-bs-target", "#bannerCarousel");
                    btn.setAttribute("data-bs-slide-to", idx);
                    btn.setAttribute("aria-label", `Slide ${idx + 1}`);
                    if (idx === 0) btn.classList.add("active");
                    indicators.appendChild(btn);
                });
            })
            .catch(error => {
                console.error("Error loading carousel images:", error);
                const container = document.getElementById("carouselContainer");
                container.innerHTML = `
                    <div class="carousel-item active">
                        <img src="assets/homepage_images/logo/placeholder.png"
                            class="d-block w-100"
                            style="height:50vh; object-fit:cover;"
                            alt="Default Banner">
                    </div>`;
            });
    }
    </script>

    <!-- Call to Action -->
    <div class="container call-to-action">
        <h2>Shape Your Future with Quality Catholic Education</h2>
        <p class="mb-4"><span id="schoolNameAction">Loading...</span> offers a comprehensive learning experience that combines academic excellence, Christian values, and 21st century skills.</p>
        <a href="admission-form.php" class="enroll-btn"><i class="fas fa-graduation-cap me-2"></i>Start Your Journey Today</a>
    </div>

    <!-- Mission Vision -->
    <div class="container mission-vision">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="mission-vision-box">
                    <h3><i class="fas fa-bullseye me-2"></i>Mission</h3>
                    <p id="missionText">Loading...</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mission-vision-box">
                    <h3><i class="fas fa-eye me-2"></i>Vision</h3>
                    <p id="visionText">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchMissionAndVision();
        });

        function fetchMissionAndVision() {
            // Fetch Mission
            fetch("databases/fetch_mission.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success" && data.mission) {
                        document.getElementById("missionText").textContent = data.mission;
                    } else {
                        document.getElementById("missionText").textContent = "Unable to load mission.";
                        console.error("Error fetching mission:", data.message);
                    }
                })
                .catch(error => {
                    document.getElementById("missionText").textContent = "Error loading mission.";
                    console.error("Error fetching mission:", error);
                });

            // Fetch Vision
            fetch("databases/fetch_vision.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success" && data.vision) {
                        document.getElementById("visionText").textContent = data.vision;
                    } else {
                        document.getElementById("visionText").textContent = "Unable to load vision.";
                        console.error("Error fetching vision:", data.message);
                    }
                })
                .catch(error => {
                    document.getElementById("visionText").textContent = "Error loading vision.";
                    console.error("Error fetching vision:", error);
                });
        }
    </script>

    <!-- School Gallery -->
    <div id="schoolGallery" class="container mt-5">
        <h2 class="text-center mb-4">SCHOOL GALLERY</h2>
        <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="row" id="galleryContainer">
                    <!-- Gallery cards will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Load the gallery images dynamically -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            loadGallery();
        });

        function loadGallery() {
            fetch("databases/fetch_gallery.php")
                .then(response => response.json())
                .then(data => {
                    const galleryContainer = document.getElementById("galleryContainer");
                    galleryContainer.innerHTML = ""; // Clear existing content

                    if (data.status === "success" && data.images.length > 0) {
                        data.images.forEach(image => {
                            const imageElement = `
                                <div class="col-md-3 col-lg-4 mb-4">
                                    <div class="card gallery-card">
                                        <img src="${image}" class="card-img-top" alt="Gallery Image">
                                    </div>
                                </div>
                            `;
                            galleryContainer.innerHTML += imageElement;
                        });
                    } else {
                        galleryContainer.innerHTML = `<p class="text-center">No images available.</p>`;
                        console.error("Error:", data.message);
                    }
                })
                .catch(error => {
                    console.error("Error fetching gallery data:", error);
                    document.getElementById("galleryContainer").innerHTML = `<p class="text-center">Error loading gallery.</p>`;
                });
        }
    </script>

    <!-- Enrollment Procedures -->
    <div id="procedures" class="container procedures">
        <h2 class="text-center pt-5 mb-4">ENROLLMENT PROCEDURES</h2>
        
        <div class="enrollment-highlight" id="enrollmentInfo">
            <h5><i class="fas fa-info-circle me-2"></i>Important Information</h5>
            <p>Loading enrollment information...</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="procedure-card">
                    <h4 class="procedure-title">
                        <i class="fas fa-user-plus me-2"></i> For Transferee/New Students
                    </h4>
                    <div id="transfereeRequirements">Loading...</div> <!-- Placeholder for fetched data -->
                </div>
            </div>

            

            <div class="col-md-6">
                <div class="procedure-card">
                    <h4 class="procedure-title">
                        <i class="fas fa-user-check me-2"></i> For Old Students
                    </h4>
                    <div id="oldStudentsRequirements">Loading...</div> <!-- Placeholder for fetched data -->
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-3">
                        <img id="footerLogo" src="assets/homepage_images/logo/placeholder.png" alt="School Logo" width="75" class="me-2 rounded-circle">
                        <span id="schoolNameFooter" class="logo-text">Loading...</span>
                    </div>
                    <div class="footer-text text-center text-md-start">
                        <p><i class="fas fa-map-marker-alt me-2"></i><a href="https://www.google.com/maps?q=Sumulong+Street+San+Isidro+Taytay+Rizal" target="_blank" style="color: white; text-decoration: none;">Sumulong Street, Brgy. San Isidro, Taytay, Rizal Philippines</a></p>
                        <p><i class="fas fa-envelope me-2"></i><a href="mailto:sjbps_10@yahoo.com" style="color: white; text-decoration: none;">sjbps_10@yahoo.com</a></p>
                        <p><i class="fas fa-phone me-2"></i><a href="tel:+63282965896" style="color: white; text-decoration: none;">(+632) 8296-5896</a> | <a href="tel:+639201225764" style="color: white; text-decoration: none;">0920 122 5764</a></p>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="social-media-container">
                        <h5 class="social-media-icons mb-3">Follow Us</h5>
                        <div class="social-media-icons">
                            <a href="https://www.facebook.com/sjbps2003" class="social-icon facebook" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/sjbps2003" class="social-icon instagram" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    <div class="social-media-icons mt-4">
                        <p>© 2025 <span id="schoolNameRigthsReserves">Loading</span>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    




    <!-- Load the enrollment information dynamically -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        fetchEnrollmentInfo();
        });

        function fetchEnrollmentInfo() {
        fetch("databases/fetch_enrollment_info.php")
            .then(response => response.json())
            .then(data => {
            const enrollmentInfo = document.getElementById("enrollmentInfo");
            if (data.status === "success" && data.info) {
                enrollmentInfo.innerHTML = `
                <h5><i class="fas fa-info-circle me-2"></i>Important Information</h5>
                <p>${data.info}</p>
                `;
            } else {
                enrollmentInfo.innerHTML = `
                <h5><i class="fas fa-info-circle me-2"></i>Important Information</h5>
                <p>Unable to load enrollment information at the moment.</p>
                `;
                console.error("Error:", data.message);
            }
            })
            .catch(error => {
            document.getElementById("enrollmentInfo").innerHTML = `
                <h5><i class="fas fa-info-circle me-2"></i>Important Information</h5>
                <p>Error loading enrollment information.</p>
            `;
            console.error("Error fetching enrollment information:", error);
            });
        }
    </script>

    <!-- Load the requirements for transferee/new students dynamically -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("databases/fetch_transferee_req_info.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        document.getElementById("transfereeRequirements").innerHTML = data.info;
                    } else {
                        document.getElementById("transfereeRequirements").innerHTML = "<p>Error loading data: " + data.message + "</p>";
                    }
                })
                .catch(error => {
                    console.error("Error fetching transferee requirements:", error);
                    document.getElementById("transfereeRequirements").innerHTML = "<p>Failed to load information.</p>";
                });
        });
    </script>

    <!-- Load the requirements for old students dynamically -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("databases/fetch_old_students_req_info.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        document.getElementById("oldStudentsRequirements").innerHTML = data.info;
                    } else {
                        document.getElementById("oldStudentsRequirements").innerHTML = "<p>Error loading data: " + data.message + "</p>";
                    }
                })
                .catch(error => {
                    console.error("Error fetching old students' requirements:", error);
                    document.getElementById("oldStudentsRequirements").innerHTML = "<p>Failed to load information.</p>";
                });
        });
    </script>

</body>
</html>