<?php session_start();

if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
} else {
    $username = 'Guest'; // Or redirect to login page
}

?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cogs"></i>
        </div>
        <div class="sidebar-brand-text mx-3">TODA ADMIN</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Management
    </div>

    <!-- Nav Item - Driver Management -->
    <li class="nav-item">
        <a class="nav-link" href="drivers.php">
            <i class="fas fa-fw fa-user"></i>
            <span>Tricycle Management</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="violation.php">
            <i class="fas fa-fw fa-exclamation-triangle"></i>
            <span>Violations</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="customer_reports.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Customer Reports</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="messages.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Messages</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="revenue.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Revenue</span></a>
    </li>
    

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
    TODAnaTO App
    </div>
    <!-- Nav Item - Tables -->

    

    <li class="nav-item">
        <a class="nav-link" href="app_users_dashboard.php">
            <i class="fas fa-fw fa-gift"></i>
            <span>Users</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="app_drivers_dashboard.php">
            <i class="fas fa-fw fa-gift"></i>
            <span>Drivers</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="app_revenue.php">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>App Revenue</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="fare_matrix.php">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Fare Matrix</span>
        </a>
    </li>

    <!-- Nav Item - Driver Performance -->
    <li class="nav-item">
        <a class="nav-link" href="driver_performance.php">
            <i class="fas fa-fw fa-star"></i>
            <span>Driver Performance</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="app_trips.php">
            <i class="fas fa-fw fa-star"></i>
            <span>Trips</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>


        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                </a>
                <!-- Dropdown - Search -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search" action="search.php" method="POST">
                        <div class="input-group">
                            <input type="text" name="search_term" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" name="search">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>




<!-- Nav Item - User Information -->
<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span><?php echo "Admin"; ?></span>

    </a>
    <!-- Dropdown - User Information -->
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

        <a class="dropdown-item" href="logout.php"> <!-- Update href -->
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
        </a>
    </div>
</li>
</ul>
</nav>
<!-- End of Topbar -->

<!-- Custom CSS to ensure logout button is clickable -->
<style>
    .navbar-nav .nav-item .dropdown-menu {
        z-index: 1050; /* Ensure dropdown is above other elements */
    }
</style>