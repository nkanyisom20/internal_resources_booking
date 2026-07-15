<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container-fluid">
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin'): ?>
      <a class="navbar-brand position-relative py-o" href="admin_dashboard.php" style="width: 200px;">
        <img src="assets/images/logo.png" alt="Logo" 
        class="position-absolute" 
        style="top: 50%; transform: translateY(-35%);height: 50px; left: 0; z-index: 1000;">
      </a>
    <?php endif; ?> 
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Clerk'): ?>
      <a class="navbar-brand position-relative py-o" href="index.php" style="width: 200px;">
        <img src="assets/images/logo.png" alt="Logo" 
        class="position-absolute" 
        style="top: 50%; transform: translateY(-35%);height: 50px; left: 0; z-index: 1000;">
      </a>
    <?php endif; ?>
    <!-- Toggler for mobile view -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Admin Only Link -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="admin_manage_resources.php"><i class="bi bi-folder"></i> Resources</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admin_manage_bookings.php"><i class="bi bi-calendar"></i> Bookings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admin_manage_users.php"><i class="bi bi-people"></i> Employees</a>
          </li>
        <?php endif; ?>

        <!-- User Only Link -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Clerk'): ?>
          <li class="nav-item">
            <a class="nav-link" href="resources.php"><i class="bi bi-folder"></i> Resources</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user_booking.php"><i class="bi bi-calendar"></i> Bookings</a>
          </li>
        <?php endif; ?>
      </ul>

      <!-- Right-side User/Login -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user']['employee_no']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="profile.php">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="assets/includes/logout.php">Logout</a></li>
            </ul>
          </li>
      </ul>
    </div>
  </div>
</nav>