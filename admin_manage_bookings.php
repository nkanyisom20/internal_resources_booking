<?php
require 'assets/includes/db.php';

// Only allow admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("location: login.php");
    exit;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $booking_id = $_POST['booking_id'] ?? null;

    if ($booking_id) {
        $allowed = ['approved', 'rejected', 'cancelled'];
        if (in_array($action, $allowed)) {
            $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
            $stmt->execute([$action, $booking_id]);
        }
    }
}

// Fetch all bookings
$stmt = $pdo->query("SELECT b.*, u.employee_no, r.resource_name FROM bookings b JOIN users u ON b.user_id = u.employee_id
    JOIN resources r ON b.resource_id = r.resource_id ORDER BY b.start_time DESC
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

          <div class="d-flex justify-content-between align-items-center mb-3 add-resource">
        	<h4 class="mb-0"> 
    			<i class="bi bi-calendar-check"></i> Manage Bookings
			</h4>
              <div class="input-group" style="width: 300px">
                  <input type="text" class="form-control" placeholder="Booking ..." id="searchInput">
              
                  <button class="btn btn-secondary" type="button" id="clearBtn" style="display:none;">
                      <i class="bi bi-x-lg"></i>
                  </button>
                  <button class="btn btn-primary" type="button" id="searchBtn">
                      <i class="bi bi-search"></i>
                  </button>
              </div>
          </div>

          <?php if (empty($bookings)): ?>
              <div class="alert alert-info">No bookings found.</div>
          <?php else: ?>
          <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover" id="lftable">
                  <colgroup>
                      <col width="4%">
                      <col width="16%">
                      <col width="10%">
                      <col width="10%">
                      <col width="30%">
                      <col width="10%">
                      <col width="10">
                      <col width="10%">
                  </colgroup>
                  <thead class="table-dark">
                      <tr>
                          <th>No.</th>
                          <th>Resources</th>
                          <th>Date</th>
                          <th>Time</th>
                          <th>Purpose</th>
                          <th>Booked By</th>
                          <th>Status</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php 
                      $count = 1;
                      foreach ($bookings as $b): ?>
                      <tr>
                          <td><?php echo $count++; ?></td>
                          <td><?= htmlspecialchars($b['resource_name']) ?></td>
                          <td><?= date('Y-m-d', strtotime($b['booking_date'])) ?></td>
                          <td><?= date('H:i', strtotime($b['start_time'])) ?> - <?= date('H:i', strtotime($b['end_time'])) ?></td>
                          <td><?= htmlspecialchars($b['purpose']) ?></td>
                          <td><?= htmlspecialchars($b['employee_no']) ?></td>
                          <td>
                              <span class="badge bg-<?= match($b['status']) {
                                  'pending' => 'warning',
                                  'approved' => 'success',
                                  'rejected' => 'danger',
                                  'cancelled' => 'secondary',
                                  default => 'dark'
                              } ?>">
                                  <?= ucfirst($b['status']) ?>
                              </span>
                          </td>
                          <td>

                              <form method="post" class="d-inline">
                                  <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                  <input type="hidden" name="action" value="approved">
                                  <button class="btn btn-success btn-sm" title=" Approve"
                                      <?= ($b['status'] !== 'pending') ? 'disabled' : '' ?>>
                                      <?= ($b['status'] !== 'pending') ? '<s><i class="bi bi-check-circle"></i></s>' : '<i class="bi bi-check-circle"></i>' ?>
                                  </button>
                              </form>

                              <form method="post" class="d-inline">
                                  <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                  <input type="hidden" name="action" value="rejected">
                                  <button class="btn btn-danger btn-sm" title="Reject" 
                                      <?= ($b['status'] !== 'pending') ? 'disabled' : ''?>>
                                      <?= ($b['status'] !== 'pending') ? '<s><i class="bi bi-x-circle"></i></s>' : '<i class="bi bi-x-circle"></i>'?>
                                  </button>
                              </form> 

                              <form method="post" class="d-inline">
                                  <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                  <input type="hidden" name="action" value="cancelled">
                                  <button class="btn btn-secondary btn-sm" title="Cancel"
                                      <?= !in_array($b['status'], ['approved', 'pending']) ? 'disabled' : ''?>>
                                      <?= !in_array($b['status'], ['approved', 'pending']) ? '<s><i class="bi bi-x"></i></s>' : '<i class="bi bi-x"></i>'?>
                                  </button>
                              </form> 

                              
                          </td>
                      </tr>
                  <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
          <?php endif; ?>
      
      
          <!-- Bootstrap 5.3 JS + Popper (required for dropdowns to work) -->
          <?php include 'assets/includes/footer.php'?>
          <script> 
          $(document).ready(function(){
              var table = $('#lftable').DataTable({
                "pageLength": 9,
                "ordering": false,
                "searching": true,
                "dom": 'rtip',
                "language": {"zeroRecords": "No matching records found"}
              });

              $("#searchInput").on("keyup",function(){
                  table.search(this.value).draw();
              });

              $("#searchBtn").on("click",function(){
                  $("#searchInput").keyup();
              });

              $("#clearBtn").on("click", function(){
                  $("#searchInput").val('');
                  table.search('').draw();
                  $(this).hide();
              });

              $("#searchInput").on("input", function () {
                  $("#clearBtn").toggle($(this).val().length > 0);   
              });
          });
          </script>
      </div>
    </body>
</html>
