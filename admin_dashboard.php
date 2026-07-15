<?php
require 'assets/includes/db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Admin') {
    header("location: login.php");
    exit;
}

// Fetch all bookings with resource info
$stmt = $pdo->query("
    SELECT b.*, r.resource_name
    FROM bookings b
    JOIN resources r ON b.resource_id = r.resource_id
");
$bookings = $stmt->fetchAll();
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
      
      <!-- Sidebar -->
      <div class="sidebar">
        <ul>
          <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>

          <li class="menu-title">Resources</li>
          <li><a href="admin_manage_resources.php"><i class="fas fa-box"></i> View</a></li>
          <li><a href="#"><i class="fas fa-box"></i> Actions</a></li>

          <li class="menu-title">Bookings</li>
          <li><a href="admin_manage_bookings.php"><i class="fas fa-box"></i> View</a></li>
          <li><a href="#"><i class="fas fa-box"></i> Actions</a></li>

          <li class="menu-title">Employees</li>
          <li><a href="admin_manage_users.php"><i class="fas fa-box"></i> View</a></li>
          <li><a href="#"><i class="fas fa-box"></i> Actions</a></li>
        </ul>
      </div>

      <div id="calendar"></div>
    

      <script>
        document.addEventListener('DOMContentLoaded', function () {
          var calendarEl = document.getElementById('calendar');

          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: {
              left: 'prev,next today',
              center: 'title',
              right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
              <?php foreach ($bookings as $b):
                $status = $b['status'];
                $color = match ($status) {
                  'pending' => 'orange',
                  'approved' => 'green',
                  'rejected' => 'red',
                  'cancelled' => 'gray',
                  default => 'blue'
                };
              ?>
              {
                title: "<?= addslashes($b['resource_name']) ?> (<?= ucfirst($status) ?>)",
                start: "<?= $b['start_time'] ?>",
                end: "<?= $b['end_time'] ?>",
                color: "<?= $color ?>"
              },
              <?php endforeach; ?>
            ]
          });

          calendar.render();
        });
      </script>

      <!-- Bootstrap 5.3 JS + Popper (required for dropdowns to work) -->
      <?php include 'assets/includes/footer.php'?>
    </div>
  </body>
</html>
