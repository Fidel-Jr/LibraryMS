<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $current_page = basename($_SERVER['PHP_SELF']);

?>
<!-- Sidebar -->
<link rel="stylesheet" href="../assets/css/styles.css">
<style>
    .sidebar-header-collapse h3 .fa-building{
        display: none !important;
    }
    #sidebar.collapsed .sidebar-header-collapse h3 .fa-building{
        display: block !important;
    }
    #sidebar.collapsed .sidebar-header{
    padding: 0 !important;
}


</style>
<nav id="sidebar" class="shadow-sm">
    <div class="sidebar-header d-flex justify-content-center">
        <h3> <i class="fas fa-building" style="color: var(--primary-dark);"></i> LibraryMS</h3>
    </div>
    <div class="sidebar-header-collapse d-flex justify-content-center">
        <h3> <i class="fas fa-building" style="color: var(--primary-dark);"></i></h3>
    </div>
    <ul class="sidebar-menu">
        <?php 
            if($_SESSION['role'] == 'Admin'):
        ?>
        <li><a href="../admin/dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        <li>
            <a href="../admin/add-book.php" class="<?php echo ($current_page == 'add-book.php') ? 'active' : ''; ?>"><i class="fas fa-circle-plus"></i> <span>Add Book</span></a>
        </li>
        <li>
            <a href="../admin/manage-book.php" class="<?php echo ($current_page == 'manage-book.php') ? 'active' : ''; ?>"><i class="fa-solid fa-list-check"></i><span>Manage Book</span></a>
        </li>
        <li>
            <a href="../admin/issue-book.php" class="<?php echo ($current_page == 'issue-book.php') ? 'active' : ''; ?>"><i class="fas fa-book"></i> <span>Issue Book</span></a>
        </li>
        <li>
            <a href="../admin/issue-book.php" class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>"><i class="fa-solid fa-clock-rotate-left"></i> <span>History</span></a>
        </li>
        <?php endif; ?>
        <?php 
            if($_SESSION['role'] == 'User'):
        ?>
        <li><a href="../users/dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
       
        <li>
            <a href="../users/issue-book.php" class="<?php echo ($current_page == 'issue-book.php') ? 'active' : ''; ?>"><i class="fas fa-book"></i> <span>Issue Book</span></a>
        </li>

        <li>
            <a href="../users/history.php" class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>"><i class="fas fa-book"></i> <span>History</span></a>
        </li>
        <?php endif; ?>
    </ul>
</nav>