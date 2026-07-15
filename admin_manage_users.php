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

  // Add Employee into database
  if (isset($_POST['add_new_user'])) {
      $stmt = $pdo->prepare("INSERT INTO users (employee_no, surname, fullnames, password) VALUES (?, ?, ?, ?)");
      $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $stmt->execute([
          $_POST['employee_no'],
          $_POST['surname'],
          $_POST['fullnames'],
          $hashedPassword 
      ]);

      echo "<div class='alert alert-success'>User added successfully!</div>";
    }

  if(isset($_POST['edit'])){

    if(!empty($_POST['password'])){

        $hashedPassword = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );

        $stmt = $pdo->prepare(" UPDATE users SET surname=?, fullnames=?, role=?, password=? WHERE employee_id=? ");

        $stmt->execute([
            $_POST['surname'],
            $_POST['fullnames'],
            $_POST['role'],
            $hashedPassword,
            $_POST['employee_id']
        ]);

    } else {

        $stmt = $pdo->prepare(" UPDATE users SET surname=?, fullnames=?, role=? WHERE employee_id=?");

        $stmt->execute([
            $_POST['surname'],
            $_POST['fullnames'],
            $_POST['role'],
            $_POST['employee_id']
        ]);
    }

    header("Location: admin_manage_users.php");
    exit;
}

    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE employee_id = ?");
        $stmt->execute([$_POST['employee_id']]);
    }

    header("location: admin_manage_users.php"); // prevent resubmission
    exit;
}

//Selecting from users table
$users = $pdo->query("SELECT * FROM users ORDER BY employee_id DESC")->fetchAll();
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
           

            <div class="d-flex justify-content-between align-items-center mb-3 add-employees">
            <h4 class="mb-0">
                <i class="bi bi-people-fill"></i> Manage Employees
            </h4>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal"> <i class="bi bi-person-plus"></i> Add Employee
            </button>
        </div>

<?php if(empty($users)): ?>

<div class="alert alert-info">
    No employees found.
</div>

<?php else: ?>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="lftable">
        <thead class="table-dark">
            <tr>
                <th width="5%">#</th>
                <th width="15%">Employee No</th>
                <th width="20%">Surname</th>
                <th width="40%">Full Names</th>
                <th width="10%">Role</th>
                <th width="10%">Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $count = 1;
        foreach($users as $user):
        ?>

        <tr>
            <td><b><?= $count++; ?></b></td>
            <td><?= htmlspecialchars($user['employee_no']) ?></td>
            <td><?= htmlspecialchars($user['surname']) ?></td>
            <td><?= htmlspecialchars($user['fullnames']) ?></td>
            <td>
                <?php if($user['role']=="Admin"): ?>
                    <span class="badge bg-secondary">Admin</span>
                <?php else: ?>
                    <span class="badge bg-warning">Clerk</span>
                <?php endif; ?>

            </td>
            <td>
                <button
                    type="button"
                    class="btn btn-primary btn-sm editUser"

                    data-bs-toggle="modal"
                    data-bs-target="#editUserModal"

                    data-id="<?= $user['employee_id'] ?>"
                    data-employee="<?= htmlspecialchars($user['employee_no']) ?>"
                    data-surname="<?= htmlspecialchars($user['surname']) ?>"
                    data-fullnames="<?= htmlspecialchars($user['fullnames']) ?>"
                    data-role="<?= htmlspecialchars($user['role']) ?>">

                    <i class="bi bi-eye"></i> / <i class="bi bi-pencil"></i>

                </button>

                <form method="post" class="d-inline">
                    <input type="hidden" name="employee_id" value="<?= $user['employee_id'] ?>">
                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this employee?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php endif; ?>


<!-- Add Employee Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <form method="post" class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"> Add Employee </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
      
        <div class="row">
        <small><span id="employee_no_for_registration"></span></small>
          <div class="col-md-9 mb-3">
            <label class="form-label"> Employee Number </label>
            <input type="text" name="employee_no" id="reg_employee_no" maxlength="9" 
            class="form-control" onblur="check_employee_no_for_registration(this.value)" required autofocus >     
          </div>
        
          <div class="col-md-3 mb-3">
            <label class="form-label"> Role </label>
            <select name="role" class="form-select" required>
              <option value="">-Select-</option>
              <option value="Clerk"> Clerk </option>
              <option value="Admin"> Admin </option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label"> Surname </label>
            <input type="text" name="surname" class="form-control" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label"> Full Names </label>
            <input type="text" name="fullnames" class="form-control" required>
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label"> Password </label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label"> Confirm Password </label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
          </div>
        </div>
          
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="showAddPassword" onclick="toggleAddPasswords()">
          <label class="form-check-label" for="showAddPassword"> Show Passwords </label>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
        <button type="submit" name="add_new_user" id="add_new_user" class="btn btn-success"> Save Employee </button>
      </div>
    </form>
  </div>
</div>
			
<!-- Edit Employee Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="post" class="modal-content">

      <div class="modal-header">
          <h5 class="modal-title" id="editUserLabel">View / Edit Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- Hidden Employee ID -->
        <input type="hidden" name="employee_id" id="edit_id">

        <div class="row">
          <div class="col-md-9 mb-3">
            <label class="form-label">Employee Number</label>
            <input type="text" id="edit_employee" class="form-control"  readonly>
          </div>

          <div class="col-md-3 mb-3">
            <label class="form-label">Role</label>
            <select name="role" id="edit_role" class="form-select">
              <option value="Clerk">Clerk</option>
              <option value="Admin">Admin</option>
            </select>
          </div>
        </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Surname</label>
              <input type="text" name="surname"  id="edit_surname"  class="form-control"  required>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Full Names</label>
              <input  type="text" name="fullnames" id="edit_fullnames" class="form-control" required>
            </div>
          </div>

          <hr>

          <h6>Change Password</h6>
          <small class="text-muted"><i>
            ( Leave the password fields blank if you don't want to change the current password.)</i>
          </small>

          <div class="row mt-3">

              <div class="col-md-6 mb-3">
                  <label class="form-label">New Password</label>

                  <input type="password"  name="password" id="edit_password" class="form-control">

              </div>

              <div class="col-md-6 mb-3">
                  <label class="form-label">Confirm Password</label>

                  <input type="password"  name="confirm_password" id="edit_confirm_password"  class="form-control">

              </div>

          </div>

          <div class="form-check">
              <input  class="form-check-input" type="checkbox" id="showPasswords" onclick="togglePasswords()">

              <label class="form-check-label" for="showPasswords">
                  Show Passwords
              </label>
          </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
        <button type="submit" name="edit" class="btn btn-primary"> Save Changes </button>
      </div>

    </form>
  </div>
</div>
        <?php include 'assets/includes/footer.php'?>
        <script> 
            $(document).ready(function(){
                var table = $('#lftable').DataTable({
                    "pageLength": 9,
                    "ordering": false,
                    "searching": true,
                    "dom": 'rtrip',
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
            $(document).ready(function () {
                $('.editUser').click(function () {
                    $('#edit_id').val($(this).data('id'));
                    $('#edit_employee').val($(this).data('employee'));
                    $('#edit_surname').val($(this).data('surname'));
                    $('#edit_fullnames').val($(this).data('fullnames'));
                    $('#edit_role').val($(this).data('role'));

                    // Clear password fields each time the modal opens
                    $('#edit_password').val('');
                    $('#edit_confirm_password').val('');
                });
            });
        </script>
        <script type="text/javascript" src="assets/js/jquery-key-restrictions.js"></script>
        
        <script type="text/javascript">
          $(document).ready(function() {
              $("#reg_employee_no").numbersOnly();
          });
        </script>
			  <!--Password Checking when adding new user information-->
        <script>
            function toggleAddPasswords() {

                const password = document.getElementById("password");
                const confirm = document.getElementById("confirm_password");

                const type = password.type === "password" ? "text" : "password";

                password.type = type;
                confirm.type = type;
            }
        </script>
			  <!--Password Checking when Editing existing user information-->
        <script>
            function togglePasswords() {

                const password = document.getElementById('edit_password');
                const confirm = document.getElementById('edit_confirm_password');

                const type = password.type === "password" ? "text" : "password";

                password.type = type;
                confirm.type = type;
            }
        </script>
        <!--Username checking when adding a new user -->
        <script>
        function check_employee_no_for_registration(va) {
          $.ajax({
            type: "POST",
            url: "assets/includes/emp_no_for_registration.php",
            data:'employee_no='+va,
            success: function(data){
              $("#employee_no_for_registration").html(data);
              }
            });
          }
      </script>
    </body>
    
</html>
