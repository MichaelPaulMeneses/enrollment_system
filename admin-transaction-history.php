<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Retrieve admin details from session
$userId = $_SESSION['user_id'];
$adminFirstName = $_SESSION['first_name'];
$adminLastName = $_SESSION['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SJBPS Payment Transactions</title>
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
        .navbar {
            background-color: var(--primary-blue);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
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
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
        }
        .status-paid {
            color: #2ecc71;
            font-weight: bold;
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
                        <a class="nav-link active" href="admin-transaction-history.php">
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
                    <h4 class="mb-0">Transactions History</h4>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <!-- School Year Dropdown -->
                        <div class="me-3">
                            <select id="schoolYearSelect" class="form-select school-year-select">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        
                        <!-- Advanced Filters Button -->
                        <div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter me-2"></i>Advanced Filters
                            </button>
                        </div>
                    </div>
                </div>


                
                <!-- Search Bar -->
                <div class="search-container d-flex justify-content-end">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search applications" aria-label="Search">
                        <button class="btn btn-outline-secondary" type="button" id="clearBtn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Payment Amount</th>
                                <th>Payment Date</th>
                                <th>Transaction by</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsContainer">
                            <!-- JavaScript will populate this section -->

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
                    <!-- Amount Range Filter -->
                    <div class="mb-3">
                        <label class="form-label">Amount Range</label>
                        <div class="d-flex">
                            <input type="number" id="minAmount" class="form-control me-2" placeholder="Min Amount">
                            <input type="number" id="maxAmount" class="form-control" placeholder="Max Amount">
                        </div>
                    </div>


                    <!-- Interview Date Range Filter -->
                    <div class="mb-3">
                        <label class="form-label">Interview Date Range</label>
                        <select id="interviewDateRange" class="form-select">
                            <option value="">Select Date Range</option>
                            <option value="today">Today</option>
                            <option value="1_week_ago">1 Week Ago</option>
                            <option value="2_weeks_ago">2 Weeks Ago</option>
                            <option value="1_month_ago">1 Month Ago</option>
                            <option value="1_month_ago">3 Months Ago</option>
                            <option value="6_months_ago"> 6 Months Ago</option>
                        </select>
                    </div>

                    <!-- School Year Filter -->
                    <div class="mb-3">
                        <label class="form-label">Facilitator</label>
                        <select id="userFilter" class="form-select">

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



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Advance Filter Method -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const schoolYearSelect = document.getElementById("schoolYearSelect");
            const transactionsContainer = document.getElementById("transactionsContainer");

            // Fetch and populate the school years for the filter dropdown
            fetchSchoolYears([schoolYearSelect]);

            // Handle School Year Change
            schoolYearSelect.addEventListener("change", () => {
                fetchTransactionHistory(schoolYearSelect.value);  // Fetch transactions when school year is selected
            });

            // Fetch school years for the filter dropdown
            fetchUserForTransactions();
            
            // Search Method
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


        // Fetch school years for the dropdown
        function fetchSchoolYears(selectElements) {
            fetch("databases/school_years.php")
                .then(res => res.json())
                .then(data => {
                    if (data.status !== "success") {
                        throw new Error(data.message || "Failed to fetch school years");
                    }

                    selectElements.forEach(select => {
                        if (select) select.innerHTML = ""; // Clear old options
                    });

                    data.schoolYears.forEach(year => {
                        selectElements.forEach(select => {
                            if (select) {
                                const option = document.createElement("option");
                                option.value = year.school_year_id;
                                option.textContent = year.school_year;
                                select.appendChild(option);
                            }
                        });
                    });
                    if (schoolYearSelect.value) {
                        fetchTransactionHistory(schoolYearSelect.value);
                        }
                })
                .catch(error => console.error("Error fetching school years:", error));
        }

        // Fetch and Display Transactions based on school year
        function fetchTransactionHistory(schoolYearId) {
            if (!schoolYearId) return;

            const formData = new FormData();
            formData.append('school_year_id', schoolYearId);

            fetch('databases/fetch_transaction_history.php', {
                method: 'POST',  // Using POST method
                body: formData,  // Sending the data using FormData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    transactionsContainer.innerHTML = '';  // Clear existing data

                    if (data.length === 0) {
                        transactionsContainer.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center py-5 empty-table-message">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No transactions available for the selected school year.</p>
                                </td>
                            </tr>
                        `;
                    } else {
                        data.forEach((transaction, index) => {
                            let row = document.createElement("tr");
                            row.classList.add("transaction-row");
                            row.setAttribute("data-id", transaction.payment_id);

                            row.innerHTML += `
                                <td>${index + 1}</td>
                                <td>${transaction.student_name}</td>
                                <td>${transaction.payment_amount}</td>
                                <td>${transaction.payment_date}</td>
                                <td>${transaction.facilitator_name}</td>
                            `;
                            transactionsContainer.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error("Error fetching transactions:", error));
        }



        // Fetch school years for the filter dropdown Modal
        function fetchUserForTransactions() {
            fetch("databases/users_for_transactions.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        const userDropdown = document.getElementById("userFilter");
                        userDropdown.innerHTML = '<option value="">All Users</option>';

                        data.users.forEach(user => {
                            const option = document.createElement("option");
                            option.value = user.full_name;
                            option.textContent = user.full_name;
                            userDropdown.appendChild(option);
                        });
                    } else {
                        console.error("Failed to fetch users.");
                    }
                })
                .catch(error => console.error("Error fetching users:", error));
        }

        function getDateRange(range) {
            const endDate = new Date();  // Today
            const startDate = new Date(); // Will be modified

            switch (range) {
                case "today":
                    // Start and end are the same day
                    break;
                case "1_week_ago":
                    startDate.setDate(endDate.getDate() - 7);
                    break;
                case "2_weeks_ago":
                    startDate.setDate(endDate.getDate() - 14);
                    break;
                case "1_month_ago":
                    startDate.setMonth(endDate.getMonth() - 1);
                    break;
                case "3_months_ago":
                    startDate.setMonth(endDate.getMonth() - 3);
                    break;
                case "6_months_ago":
                    startDate.setMonth(endDate.getMonth() - 6);
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

        function filterTable() {
            let minAmount = parseFloat(document.getElementById('minAmount').value) || 0;
            let maxAmount = parseFloat(document.getElementById('maxAmount').value) || Infinity;

            let dateRange = document.getElementById("interviewDateRange").value;
            let { startDate, endDate } = getDateRange(dateRange);

            let userFilter = document.getElementById("userFilter").value.toLowerCase();

            document.querySelectorAll("tbody tr").forEach(row => {
                // Amount filter (assumed to be in column index 2)
                let amountText = row.cells[2].textContent.replace(/[^0-9.-]+/g, ""); // remove currency symbols, commas, etc.
                let amount = parseFloat(amountText) || 0;

                let amountMatch = amount >= minAmount && amount <= maxAmount;

                // Interview date filter (assumed to be in column index 3)
                let interviewDateText = row.cells[3].textContent.trim();
                let interviewDate = new Date(interviewDateText);

                let dateMatch = true;
                if (startDate && endDate) {
                    interviewDate.setHours(0, 0, 0, 0);
                    startDate.setHours(0, 0, 0, 0);
                    endDate.setHours(23, 59, 59, 999);
                    dateMatch = interviewDate >= startDate && interviewDate <= endDate;
                }

                // User filter (assumed to be in column index 4)
                let userMatch = userFilter === "" || row.cells[4].textContent.toLowerCase() === userFilter;

                if (amountMatch && dateMatch && userMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }


    </script>
</body>
</html>