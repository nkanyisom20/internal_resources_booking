 <?php
require 'assets/includes/db.php';
$error = '';

// Only allow admin
if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $user_id = $_SESSION['user']['id'] ?? null;
    
    //Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users_update WHERE email = ?");
    $stmt -> execute([$email]);
    $existingEmail = $stmt->fetch();

        if($existingEmail){
          $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Email already exixts.',
            'redirect' => 'profile.php'
        ];  
    }
else
    {
        $stmt = $pdo->prepare("INSERT INTO users_update (email, user_id) VALUES (?, ?)");
        if ($stmt->execute([$email, $user_id])) {
            //  Store message with type + redirect target
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Email successfully added.',
                'redirect' => 'profile.php'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Email not added.',
                'redirect' => 'profile.php'
            ];
        }
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
  <body class="container mt-5">
      <?php include 'assets/includes/navbar.php'; ?>
        <h2>Welcome, <?= $_SESSION['user']['employee_no'] ?></h2>
        <p>We're working hard to lauch our profile page. Stay tune!</p>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email for updates">
            <button type="submit" class="btn btn-primary">Notify Me</button>
        </form>

    <!-- Bootstrap 5.3 JS + Popper (required for dropdowns to work) -->
    <?php include 'assets/includes/footer.php'?>
  </body>
</html>
