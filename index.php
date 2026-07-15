 <?php
require 'assets/includes/db.php';
if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit;
}
?>
<?php
$resources = $pdo->query("SELECT * FROM resources ORDER BY resource_id DESC LIMIT 3")->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Nkanyiso Consulting (IRBS) Pty Ltd</title>
    <meta charset="utf-8">
    <?php include 'assets/includes/header.php'?>
  </head>
  <body>
    <div class="container mt-5">
      <?php include 'assets/includes/navbar.php'; ?>
      <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
              <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>

              <li class="menu-title">Resources</li>
              <li><a href="resources.php"><i class="fas fa-box"></i> Newly </a></li>
              <li><a href="resources.php"><i class="fas fa-box"></i> View All </a></li>

              <li class="menu-title">Bookings</li>
              <li><a href="user_booking.php"><i class="fas fa-box"></i> Approved </a></li>
              <li><a href="user_booking.php"><i class="fas fa-box"></i> Cancelled </a></li>
              <li><a href="user_booking.php"><i class="fas fa-box"></i> Pending </a></li>

            </ul>
        </div>
        <!-- Content -->
        <div class="main-content">
          <!-- Cards -->
          <div class="container-fluid mt-4">
            <div class="row">
              <div class="col-md-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <i class="fas fa-users"></i>
                    <h3>15</h3>
                    <p>Total Resources </p>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <i class="fas fa-box"></i>
                    <h3>12</h3>
                    <p>Active Resources</p>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>3</h3>
                    <p>Inactive Resources</p>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <i class="fas fa-money-bill"></i>
                    <h3>22</h3>
                    <p>Employees</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Invoices -->
            <div class="card mt-3">
              <div class="card-header">
                Recent Resources
              </div>

              <div class="card-body">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Resource Name</th>
                      <th>Description</th>
                      <th>Location</th>
                      <th>Capacity</th>
                      <th>Status</th>
                    </tr>
                  </thead>

                  <tbody>
                <?php 
                $count = 1;
                foreach ($resources as $res): ?>

                <tr>
                    <td><b><?php echo $count++; ?></b></td>
                    <td><?= htmlspecialchars($res['resource_name']) ?></td>
                    <td><?= htmlspecialchars(substr($res['description'],0,50)) ?>...</td>
                    <td><?= htmlspecialchars($res['location']) ?></td>
                    <td><?= htmlspecialchars($res['capacity']) ?></td>
                    <td>
                        <span class="badge bg-<?= match($res['status']) {
                            'Active' => 'success',
                            'Inactive' => 'secondary'
                        } ?>">
                            <?= ucfirst($res['status']) ?>
                        </span>
                    </td>

                </tr>
                <?php endforeach; ?>
                </tbody>
                </table>
              </div>
            </div>
            <br>
          </div>
        </div>
      </div>
      <!-- Bootstrap 5.3 JS + Popper (required for dropdowns to work) -->
      <?php include 'assets/includes/footer.php'; ?>
    </div>
  </body>
</html>
