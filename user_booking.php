<?php
require 'assets/includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("document: login.php");
    exit;
}

date_default_timezone_set('Africa/Johannesburg'); // Adjust timezone if needed

$user_id = $_SESSION['user']['employee_id'];

// Fetch bookings with related resource info
$stmt = $pdo->prepare("
    SELECT b.*, r.resource_name
    FROM bookings b
    JOIN resources r ON b.resource_id  = r.resource_id
    WHERE b.user_id = ?
    ORDER BY b.booking_date ASC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Nkanyiso Consulting (IRBS) Pty Ltd</title>
    <meta charset="utf-8">
    <?php include 'assets/includes/header.php'?>
 
    <style>
    .nav-flash {
      font-size: 0.9rem;
      color: #dc3545; /* Bootstrap red or customize */
      margin-top: 4px;
      margin-left: 5px;
    }

    @keyframes shake {
      0% { transform: translateX(0); }
      20% { transform: translateX(-3px); }
      40% { transform: translateX(3px); }
      60% { transform: translateX(-3px); }
      80% { transform: translateX(3px); }
      100% { transform: translateX(0); }
    }

    .animate-shake {
      animation: shake 0.4s;
    }
  </style>

  </head>
  <body>
    <div class="container mt-5">
      <?php include 'assets/includes/navbar.php'; ?>
        
       <div class="d-flex justify-content-between align-items-center mb-3 add-resource">
  				<h4 class="mb-0"> 
                <i class="glyphicon glyphicon-edit"></i> Manage Bookings
            </h4>
            <div class="input-group" style="width: 300px">
              <input type="text" class="form-control" placeholder="Bookings ..." id="searchInput">
          
              <button class="btn btn-secondary" type="button" id="clearBtn" style="display:none;">
                  <i class="bi bi-x-lg"></i>
              </button>
              <button class="btn btn-primary" type="button" id="searchBtn">
                  <i class="bi bi-search"></i>
              </button>
            </div>
        </div>
        <?php if (empty($bookings)): ?>
          <div class="alert alert-info">You haven't made any bookings yet.</div>
        <?php else: ?>
          <table class="table table-bordered table-hover" id="lftable">
            <colgroup>
                <col width="54">
                <col width="15%">
                <col width="10%">
                <col width="10%">
                <col width="35%">
                <col width="10%">
                <col width="15%">
            </colgroup>
            <thead class="table-dark">
              <tr>
                <th>No.</th>
                <th>Resource</th>
                <th>Date</th>
                <th>Time</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $count = 1;
                foreach ($bookings as $b): ?>
                <tr>
                  <td><b><?php echo $count++; ?></b></td>
                  <td><?= htmlspecialchars($b['resource_name']) ?></td>
                  <td><?= date('Y-m-d', strtotime($b['booking_date'])) ?></td>
                  <td><?= date('H:i', strtotime($b['start_time'])) ?> - <?= date('H:i', strtotime($b['end_time'])) ?></td>
                  <td><?= htmlspecialchars($b['purpose']) ?></td>
                  <td>
                    <?php
                      $status = ucfirst($b['status']);
                      $badge = match ($b['status']) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'secondary',
                        default => 'dark'
                      };
                    ?>
                    <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
                  </td>
                  <td>
                    <?php
                      $now = date('Y-m-d H:i:s');
                      $canCancel = in_array($b['status'], ['pending', 'approved']);
                      $canEdit = in_array($b['status'], ['pending']);

                    ?>

                    <?php if ($canCancel): ?>
                      <form action="cancel_booking.php" class="d-inline" method="post" onsubmit="return confirm('Cancel this booking?')">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($b['booking_id']) ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                      </form>
                    <?php else: ?>
                      <span class="btn btn-sm btn-outline-default" disabled>Done</span>
                    <?php endif; ?>

                    <?php if ($canEdit): ?>
                      <form action="#" class="d-inline" method="post" onsubmit="return confirm('Save this booking update?')">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($b['booking_id']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
        <!-- View Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form method="post" action="handle_booking.php">
              <input type="hidden" name="booking_id" value="">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewModalLabel">
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  

                  <!-- Tab Contents -->
                  <div class="tab-content pt-3" id="modalTabsContent">
                    

                    <!-- Booking Tab -->
                    <div class="tab-pane fade" id="book" role="tabpanel">
                      <div class="mb-3">
                        <label class="form-label">Booking Date</label>
                        <input type="date" name="booking_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Starting Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control"  required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Ending Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" disabled required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Purpose</label>
                        <textarea name="purpose" id="purpose" class="form-control" rows="3" cols="30" placeholder="We will be hosting our..." required></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Submit Booking</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      <!-- Bootstrap 5.3 JS + Popper (required for dropdowns to work) -->
      <?php include 'assets/includes/footer.php'?>
      <script>
        $(document).ready(function(){
            var table = $('#lftable').DataTable({
                "pageLength": 8,
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
