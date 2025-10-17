<?php

    session_start();
    if(!isset($_SESSION["user_id"]) && $_SESSION['role'] != 'Admin'){
        header("Location: ../index.php");
    }    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Dashboard</title>
    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .card-header{
            background-color: white !important;
            border: none !important;
        }
        .dashboard-card{
            height: fit-content !important;
            margin-bottom: 0 !important;
        }
        .bg-success-subtle{
            background-color: var(--bg-subtle-primary-color) !important;
            color: var(--primary-color) !important;
            border: none !important;
        }
        .bg-danger-subtle{
            background-color: var(--bg-subtle-secondary-color) !important;
            color: #db3545 !important;
            border: none !important;
        }
        .table td{
            color: var(--secondary-text) !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="content">
        <!-- Navbar -->
        <?php include '../includes/navbar.php'; ?>

        <!-- Page Content -->
        <div class="container-fluid mt-4">
            <h2>Dashboard Overview</h2>
            <p class="text-muted">Welcome back! Here's what's happening today.</p>
            
            <!-- Stats Cards -->
            <div class="row mt-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card shadow-sm bg-light text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Borrowed Books</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="stat-value me-2">12,426</div>
                                        <span class="badge bg-success-subtle text-success border rounded-pill px-2 py-1">+23%</span>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card shadow-sm bg-light text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Returned Books</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="stat-value me-2">2,846</div>
                                        <span class="badge bg-danger-subtle text-danger border rounded-pill px-2 py-1">-14%</span>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <i class="fa-solid fa-book-open-reader"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card shadow-sm bg-light text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Books</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="stat-value me-2">20,426</div>
                                        <span class="badge bg-success-subtle text-success border rounded-pill px-2 py-1">+10</span>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <i class="fa-solid fa-book-atlas"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card shadow-sm bg-light text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Members</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="stat-value me-2">1,230</div> 
                                        <span class="badge bg-success-subtle text-success border rounded-pill px-2 py-1">+40</span>
                                    </div>
                                </div>
                                <div class="card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Chart.js and Overdue -->
            <div class="row">
                <div class="col-xl-7 col-lg-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body" style="height:380px;">
                            <h5 class="card-title">Issued Book statistics</h5>
                            <canvas id="checkoutChart" height="300" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5 col-lg-12 mb-4">
                    <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Overdue’s History</h5>
                        <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="border-bottom">
                            <tr>
                                <th>Member ID</th>
                                <th>Title</th>
                                <th>ISBN</th>
                                <th>Due Date</th>
                                <th>Fine</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr><td>#48964</td><td>Magnolia Palace</td><td>3234</td><td>5</td><td>$10</td></tr>
                            <tr><td>#48964</td><td>Don Quixote</td><td>3234</td><td>5</td><td>$10</td></tr>
                            <tr><td>#48964</td><td>Alice's Adventures...</td><td>3234</td><td>5</td><td>$10</td></tr>
                            <tr><td>#48964</td><td>Pride and Prejudice</td><td>3234</td><td>5</td><td>$10</td></tr>
                            <tr><td>#48964</td><td>Treasure Island</td><td>3234</td><td>5</td><td>$10</td></tr>
                            <tr><td>#48964</td><td>Treasure Island</td><td>3234</td><td>5</td><td>$10</td></tr>
                            <tr><td>#48964</td><td>Treasure Island</td><td>3234</td><td>5</td><td>$10</td></tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            
            <!-- Charts and Activity -->
            <div class="row align-items-stretch">
                <div class="col-xl-9 col-lg-12 d-flex flex-column mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Issued</h5>
                            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="table-responsive card-body pt-0">
                            <table class="table table-borderless mb-0">
                                <thead class="border-bottom">
                                    <tr>
                                        <th>ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Author</th>
                                        <th scope="col">Member</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#544112</td>
                                        <td>The Great Gatsby</td>
                                        <td>Mr. Smith</td>
                                        <td>John Doe</td>
                                        <td>2024-10-01</td>
                                        <td><span class="badge" style="background-color: var(--primary-color);">Returned</span></td>
                                    </tr>
                                    <tr>
                                        <td>#341143</td>
                                        <td>Returned "1984"</td>
                                        <td>Adams Chester</td>
                                        <td>Jane Smith</td>
                                        <td>2024-10-02</td>
                                        <td><span class="badge" style="background-color: var(--primary-color);">Returned</span></td>    
                                    </tr>
                                    <tr>
                                        <td>#341143</td>
                                        <td>Returned "1984"</td>
                                        <td>Adams Chester</td>
                                        <td>Jane Smith</td>
                                        <td>2024-10-02</td>
                                        <td><span class="badge" style="background-color: var(--primary-color);">Returned</span></td>    
                                    </tr>
                                    <tr>
                                        <td>#341143</td>
                                        <td>Returned "1984"</td>
                                        <td>Adams Chester</td>
                                        <td>Jane Smith</td>
                                        <td>2024-10-02</td>
                                        <td><span class="badge" style="background-color: var(--primary-color);">Returned</span></td>    
                                    </tr>
                                    <tr>
                                        <td>#341143</td>
                                        <td>Returned "1984"</td>
                                        <td>Adams Chester</td>
                                        <td>Jane Smith</td>
                                        <td>2024-10-02</td>
                                        <td><span class="badge" style="background-color: var(--primary-color);">Returned</span></td>    
                                    </tr>
                                    <tr>
                                        <td>#341143</td>
                                        <td>Returned "1984"</td>
                                        <td>Adams Chester</td>
                                        <td>Jane Smith</td>
                                        <td>2024-10-02</td>
                                        <td><span class="badge" style="background-color: var(--primary-color);">Returned</span></td>    
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 d-flex flex-column mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center">
                            <!-- Tabs -->
                            <div class="rounded-pill me-2 px-3 p-1 fw-semibold" style="background-color: var(--primary-color); color: var(--primary-light);">
                                Top Books
                            </div>
                            <!-- <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                New arrivals
                            </button> -->
                        </div>

                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <!-- Book 1 -->
                                <div class="border-bottom pb-3">
                                    <h6 class="mb-1 fw-semibold">Magnolia Palace</h6>
                                    <p class="text-muted mb-1 small">Cristofer Bator</p>
                                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-1">
                                        Available
                                    </span>
                                </div>

                                <!-- Book 2 -->
                                <div class="border-bottom pb-3">
                                    <h6 class="mb-1 fw-semibold">Don Quixote</h6>
                                    <p class="text-muted mb-1 small">Aspen Siphon</p>
                                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-1">
                                        Available
                                    </span>
                                </div>

                                <!-- Book 3 -->
                                <div>
                                    <h6 class="mb-1 fw-semibold">Pride and Prejudice</h6>
                                    <p class="text-muted mb-1 small">Kianna Geidt</p>
                                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-1">
                                        Available
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="../assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart.JS Script -->
    <script>
        
    </script>
    
</body>
</html>