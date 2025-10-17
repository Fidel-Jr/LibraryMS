<?php 

    // session_start();
    require_once "../config/database.php";
    require_once '../models/User.php';
    
    if(!isset($_SESSION["user_id"])){
        header("Location: ../index.php");
    }
    $user = new User($pdo);

    $userData = $user->getUserInfo($_SESSION["user_id"]);


?>
<!-- Navbar -->
<link rel="stylesheet" href="../assets/css/styles.css">
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i> <?= $userData["data"]["full_name"] ?>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="../users/profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>