<?php
require 'assets/includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE employee_no = ?");
    $stmt->execute([$_POST['employee_no']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        // Set session with user data
        $_SESSION['user'] = [
            'employee_id' => $user['employee_id'],
            'employee_no' => $user['employee_no'],
            'role' => $user['role']
        ];

        // Redirect based on role
        if ($user['role'] =='Admin') {
            header("location: admin_dashboard.php");
        } else {
            header("location: index.php");
        }
        exit;
    } 
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Nkanyiso Consulting (IRBS) Pty Ltd</title>
    <meta charset="utf-8">
    <?php include 'assets/includes/header.php'?>
</head>
<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="row shadow-lg rounded overflow-hidden bg-white" style="max-width: 900px; width:100%;">

        <!-- Left Side Image -->
        <div class="col-md-6 d-none d-md-block p-0">
            <img src="assets/images/bg.jpeg" alt="Booking System" class="img-fluid h-100 w-100" style="object-fit: cover;">
        </div>

        <!-- Right Side Login Form -->
        <div class="col-md-6 p-5">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Internal Resource Booking System</h2>
                <p class="text-muted">
                    Nkanyiso Consulting (IRBS) Pty Ltd
                </p>
            </div>

            <form method="post">
                <small><span id="employee_no_for_login"></span></small>
                <div class="mb-3">
                  <label class="form-label">Employee Number</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                      <input type="text" name="employee_no" id="employee_no_login" maxlength="9" class="form-control" 
                      placeholder="Enter Employee Number" onblur="check_employee_no_for_login(this.value)" required autofocus >
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label"> Password </label>
                  <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-lock"></i></span>
                      <input type="password" name="password" id="password_login" class="form-control" placeholder="Enter Password" required>
                      <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                      </button>
                  </div>
                </div>

                <div class="d-grid mt-4">
                  <button type="submit" id="submit_login" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right"></i>
                      Login
                  </button>
                </div>
            </form>

            <hr class="my-4">

            <div class="text-center text-muted">
                <small>
                    Copyright © <?= date('Y') ?>
                    Nkanyiso Consulting Pty Ltd
                </small>
            </div>

        </div>

    </div>
</div>
<script type="text/javascript" src="assets/js/jquery-key-restrictions.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
      $("#employee_no_login").numbersOnly();
  });
</script>

<script>
    function togglePassword() {
        const password = document.getElementById('password_login');

        password.type =
            password.type === 'password'
            ? 'text'
            : 'password';
    }
</script>

</body>
</html>
<script>
  function check_employee_no_for_login(va) {
    $.ajax({
    type: "POST",
    url: "assets/includes/employee_no_for_login.php",
    data:'employee_no='+va,
    success: function(data){
        $("#employee_no_for_login").html(data);
        }
    });
    }
</script>
