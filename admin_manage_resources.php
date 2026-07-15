<?php
require 'assets/includes/db.php';

// Only allow admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("location: login.php");
    exit;
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pictureName = null;

  // Handle image upload
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
      $tmpName = $_FILES['picture']['tmp_name'];
      $originalName = basename($_FILES['picture']['name']);
      $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
      $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

      if (in_array($ext, $allowedExts)) {
          $pictureName = uniqid('img_', true) . '.' . $ext;

          // Create 'uploads' folder if it doesn't exist
          if (!is_dir('uploads')) {
              mkdir('uploads', 0755, true);
          }

          // Move file to uploads directory
          move_uploaded_file($tmpName, 'uploads/' . $pictureName);
      } else {
          echo "<div class='alert alert-danger'>Only image files (JPG, PNG, GIF, WEBP) are allowed.</div>";
          exit;
      }
  }

  // Insert resource into database
  if (isset($_POST['add'])) {
      $stmt = $pdo->prepare("INSERT INTO resources (resource_name, description, location, capacity, status, picture) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([
          $_POST['resource_name'],
          $_POST['description'],
          $_POST['location'],
          $_POST['capacity'],
          'Inactive',
          $pictureName
      ]);

      echo "<div class='alert alert-success'>Resource added successfully!</div>";
  }


    if (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE resources SET resource_name = ?, description = ?, location = ?, capacity = ?, status = ? WHERE resource_id = ?");
        $stmt->execute([$_POST['resource_name'], $_POST['description'], $_POST['location'], $_POST['capacity'], $_POST['status'], $_POST['resource_id']]);
    }

    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM resources WHERE resource_id = ?");
        $stmt->execute([$_POST['resource_id']]);
    }

    header("location: admin_manage_resources.php"); // prevent resubmission
    exit;
}

$resources = $pdo->query("SELECT * FROM resources ORDER BY resource_id DESC")->fetchAll();
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
              <i class="bi bi-box-seam"></i> Existing Resources
          </h4>
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addResourceModal">+ Add Resource</button>
      </div>
      <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover" id="lftable">
              <colgroup>
                  <col width="4%">
                  <col width="18%">
                  <col width="32%">
                  <col width="18%">
                  <col width="9%">
                  <col width="9%">
                  <col width="10%">
              </colgroup>
              <thead class="table-dark">
                  <tr>
                      <th>No.</th>
                      <th>Resource Name</th>
                      <th>Description</th>
                      <th>Location</th>
                      <th>Capacity</th>
                      <th>Status</th>
                      <th>Actions</th>
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

                  <td>
                      <button type="button"
                          class="btn btn-primary btn-sm editResource"
                          data-bs-toggle="modal"
                          data-bs-target="#editResourceModal"

                          data-id="<?= $res['resource_id'] ?>"
                          data-name="<?= htmlspecialchars($res['resource_name']) ?>"
                          data-description="<?= htmlspecialchars($res['description']) ?>"
                          data-location="<?= htmlspecialchars($res['location']) ?>"
                          data-capacity="<?= htmlspecialchars($res['capacity']) ?>"
                          data-available="<?= htmlspecialchars($res['status']) ?>"
                          data-picture="<?= htmlspecialchars($res['picture']) ?>">
                          <i class="bi bi-eye"></i> / <i class="bi bi-pencil"></i>
                      </button>

                      <form method="post" style="display:inline;">
                          <input type="hidden"  name="resource_id" value="<?= $res['resource_id'] ?>">

                          <button type="submit" name="delete"  class="btn btn-danger btn-sm"
                              onclick="return confirm('Delete this resource?')"><i class="bi bi-trash"></i>
                          </button>
                      </form>
                  </td>
              </tr>
              <?php endforeach; ?>
              </tbody>
          </table>
      </div>
      <!-- Edit Resource Modal -->
      <div class="modal fade" id="editResourceModal" tabindex="-1">
          <div class="modal-dialog modal-lg">

              <form method="post" class="modal-content">

                  <div class="modal-header">
                      <h5 class="modal-title"> Edit Resource </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">
                      <input type="hidden"  name="resource_id"  id="edit_id">

                      <div class="mb-3">
                          <label>Resource Name</label>
                          <input type="text"  name="resource_name" id="edit_name"  class="form-control"  required>
                      </div>

                      <div class="row">

                          <div class="col-md-6">
                              <label>Location</label>
                              <input type="text" name="location" id="edit_location" class="form-control"  required>
                          </div>

                          <div class="col-md-3">
                              <label>Capacity</label>
                              <input type="number" name="capacity" id="edit_capacity" class="form-control" min="1" required>
                          </div>

                          <div class="col-md-3">
                              <label>Status</label>
                              <select name="status" id="edit_status" class="form-control">
                                  <option value="Active">Active</option>
                                  <option value="Inactive">Inactive</option>
                              </select>
                          </div>
                      </div>

                      <div class="mb-3">
                          <label>Description</label>
                          <textarea name="description" id="edit_description"  class="form-control"  rows="4"  required></textarea>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
              </form>
          </div>
      </div>
      <!-- Add Resource Modal -->
      <div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <form method="post" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceLabel">Add Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <div class="mb-3">
                <label for="resourceName" class="form-label">Resource Name</label>
                <input type="text" class="form-control" id="resourceName" name="resource_name" required>
              </div>

              <div class="row">
                <div class=" col-md-8 mb-3">
                  <label for="resourcelocation" class="form-label">Location</label>
                  <input type="text" class="form-control" id="resourcelocation" name="location" required>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="resourceCapacity" class="form-label">Capacity</label>
                  <input type="number" class="form-control" id="resourceCapacity" min="1" name="capacity" required></textarea>
                </div>
              </div>
              
              <div class="mb-3">
                <label for="resourceDescription" class="form-label">Description</label>
                <textarea class="form-control" id="resourceDescription" name="description" required></textarea>
              </div>

              <div class="mb-3">
                <label for="resourcePicture" class="form-label">Resource Image (<i>Dimensions:720*400</i>)</label>
                <input type="file" class="form-control" name="picture" accept="image/*" required>
              </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="add" class="btn btn-primary">Save Resource</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
      <?php include 'assets/includes/footer.php'?>
    </div>
    <script> 
        $(document).ready(function(){
            var table = $('#lftable').DataTable({
                "pageLength": 7,
                "ordering": false,
                "pagingType": "full",
                "searching": true,
                "dom": 'rtip',
                "language": {"zeroRecords": "No matching records found"}
            });

            $("#searchInput").on("keyup",function(){
                table.search(this.value).draw();
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
    $(document).ready(function(){

    $('.viewRow').click(function(){

        let detailsRow = $(this)
            .closest('tr')
            .next('.view-details');

        $('.view-details').not(detailsRow).hide();

        detailsRow.toggle();

    });

    $('.editResource').click(function(){

        $('#edit_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#edit_description').val($(this).data('description'));
        $('#edit_location').val($(this).data('location'));
        $('#edit_capacity').val($(this).data('capacity'));
        $('#edit_available').val($(this).data('available'));

    });

    });
    </script>
  </body>
</html>
