<?php
require 'assets/includes/db.php';

// Only allow admin access
if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit;
}

// Fetch all resources
$stmt = $pdo->query("SELECT * FROM resources WHERE status = 'active'");
$resources = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Nkanyiso Consulting (IRBS) Pty Ltd</title>
    <meta charset="utf-8">
    <?php include 'assets/includes/header.php'?>
  </head>
  <body>
    <div class="container mt-5">
      <?php 
      if (isset($_SESSION['error'])){
        echo '<div class="alert alert-warning alert=dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                '.$_SESSION['error'].'
              </div>';
              unset($_SESSION['error']);
        }
      ?>
      <?php include 'assets/includes/navbar.php'; ?>
      <div class="d-flex justify-content-between align-items-center mb-3 add-resource">
          <h4 class="mb-0"> 
              <i class="bi bi-eye"></i> View / Book Resources
          </h4>
          <div class="input-group" style="width: 300px">
            <input type="text" class="form-control" placeholder="Resources ..." id="searchInput">
        
            <button class="btn btn-secondary" type="button" id="clearBtn" style="display:none;">
                <i class="bi bi-x-lg"></i>
            </button>
            <button class="btn btn-primary" type="button" id="searchBtn">
                <i class="bi bi-search"></i>
            </button>
          </div>
      </div>
      <div class="row">
        <?php foreach ($resources as $res): ?>
          <div class="col-md-4 mb-4">
            <div class="card shadow">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($res['resource_name']) ?></h5>
                <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($res['location']) ?></p>
                <p class="card-text"><strong>Capacity:</strong> <?= htmlspecialchars($res['capacity']) ?></p>
                <div class="d-flex justify-content-between">
                  <!-- View button triggers modal -->
                  <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?= $res['resource_id'] ?>">
                    <i class="bi bi-eye"></i> View
                  </button>
                  <!-- Book button -->
                  <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?= $res['resource_id'] ?>">
                    <i class="bi bi-calendar-check"></i> Book
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- View Modal -->
          <div class="modal fade" id="viewModal<?= $res['resource_id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $res['resource_id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <form method="post" action="assets/includes/handle_booking.php">
                <input type="hidden" name="resource_id" value="<?= $res['resource_id'] ?>">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel<?= $res['resource_id'] ?>">
                      <?= htmlspecialchars($res['resource_name']) ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <!-- Bootstrap Tabs -->
                    <ul class="nav nav-tabs" id="modalTabs<?= $res['resource_id'] ?>" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab<?= $res['resource_id'] ?>" data-bs-toggle="tab" data-bs-target="#details<?= $res['resource_id'] ?>" type="button" role="tab">Details</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="book-tab<?= $res['resource_id'] ?>" data-bs-toggle="tab" data-bs-target="#book<?= $res['resource_id'] ?>" type="button" role="tab">Book</button>
                      </li>
                    </ul>

                    <!-- Tab Contents -->
                    <div class="tab-content pt-3" id="modalTabsContent<?= $res['resource_id'] ?>">
                      <!-- Details Tab -->
                      <div class="tab-pane fade show active" id="details<?= $res['resource_id'] ?>" role="tabpanel">
                      <div class="row">  
                        <div class="col-md-4 mb-3">
                            <p><strong>Location:</strong> <?= htmlspecialchars($res['location']) ?>
                        </div>
                        <div class="col-md-2 mb-3">
                            <strong>Capacity:</strong> <?= htmlspecialchars($res['capacity']) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Description:</strong><?= nl2br(htmlspecialchars($res['description'])) ?></p>
                        </div>
                      </div>
                        <?php if (!empty($res['picture'])): ?>
                          <img src="uploads/<?= htmlspecialchars($res['picture']) ?>" alt="Resource image" class="img-fluid rounded mt-2">
                        <?php endif; ?>
                      </div>

                      <!-- Booking Tab -->
                      <div class="tab-pane fade" id="book<?= $res['resource_id'] ?>" role="tabpanel">
                        <div class="row">  
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Booking Date</label>
                            <input type="date" name="booking_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                          </div>
                          <div class=" col-md-3 mb-3">
                            <label class="form-label">Starting Time</label>
                            <input type="time" name="start_time" id="start_time<?= $res['resource_id'] ?>" class="form-control"  required>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label class="form-label">Ending Time</label>
                            <input type="time" name="end_time" id="end_time<?= $res['resource_id'] ?>" class="form-control" disabled required>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Purpose</label>
                          <textarea name="purpose" id="purpose" class="form-control" rows="3" cols="30" placeholder="We will be hosting our..." required></textarea>
                        </div>
                        <div class="mb-3">
                          <button type="submit" class="btn btn-primary">Submit Booking</button>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        <?php endforeach; ?>
      </div>
      <!-- Bootstrap 5.3 JS + Popper (required for dropdowns to work) -->
      <?php include 'assets/includes/footer.php'?>
    </div>
    
    <script> 
        $(document).ready(function(){
            var table = $('#lftable').DataTable({
                "pageLength": 7,
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
    <script>
        document.querySelectorAll('[id^="start_time"]').forEach(startInput => {
        startInput.addEventListener('change', (e) => {
            const startTime = e.target.value;

            // Get resource ID from element ID
            const resourceId = e.target.id.replace('start_time', '');
            const endTimeInput = document.getElementById('end_time' + resourceId);

            endTimeInput.disabled = false;
            endTimeInput.min = startTime;

            // Add 2 hours
            const [hours, minutes] = startTime.split(':');
            let newHour = parseInt(hours) + 2;

            if (newHour >= 24) newHour -= 24;

            endTimeInput.value = `${String(newHour).padStart(2, '0')}:${minutes}`;
          });
        });
    </script>
  </body>
</html>
