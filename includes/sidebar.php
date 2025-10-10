<?php 

    $current_page = basename($_SERVER['PHP_SELF']);

?>
<!-- Sidebar -->
<link rel="stylesheet" href="../assets/css/styles.css">
<nav id="sidebar" class="shadow-sm">
    <div class="sidebar-header d-flex justify-content-center">
        <h3> <i class="fas fa-building" style="color: var(--primary-dark);"></i> LibraryMS</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="../admin/dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        <li>
            <a href="../admin/add-book.php" class="<?php echo ($current_page == 'add-book.php') ? 'active' : ''; ?>"><i class="fas fa-circle-plus"></i> <span>Add Books</span></a>
        </li>
        <li>
            <a href="../admin/manage-book.php" class="<?php echo ($current_page == 'manage-book.php') ? 'active' : ''; ?>"><i class="fas fa-book"></i> <span>Manage Books</span></a>
        </li>
        <li><a href="#"><i class="fas fa-users"></i> <span>Members</span></a></li>
        <li><a href="#"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a></li>
        <li><a href="#"><i class="fas fa-box"></i> <span>Products</span></a></li>
        <li><a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
        <li><a href="#"><i class="fas fa-question-circle"></i> <span>Help</span></a></li>
    </ul>
</nav>