<?php
  include 'db.php';
  $employee_no = $_POST["employee_no"];
  if(empty($employee_no)){
    echo "<span style='color:red'> Please enter Employee No.</span>";
    echo "<script>$('#password_login').prop('disabled',true);</script>";
    echo "<script>$('#submit_login').prop('disabled',true);</script>";
    }
  elseif(!ctype_digit($employee_no)){
    echo "<span style='color:red'> Enter Only Numbers as Employee No.</span>";
    echo "<script>$('#password_login').prop('disabled',true);</script>";
    echo "<script>$('#submit_login').prop('disabled',true);</script>";
    }
  elseif(strlen($employee_no) < 9){
    echo "<span style='color:red'> Enter 9 Numbers for Employee No.</span>";
    echo "<script>$('#password_login').prop('disabled',true);</script>";
    echo "<script>$('#submit_login').prop('disabled',true);</script>";
    }
  else
    {
    $sql_select = $pdo->prepare("SELECT employee_no FROM users  WHERE employee_no = ?;");
    $sql_select->execute([$_POST['employee_no']]);
    $user = $sql_select->fetch();
    
    if ($user > 0) 
      {
        echo "<span style='color:green'> Employee No.".$employee_no." is valid, now enter password.</span>";
        echo "<script>$('#submit_login').prop('disabled',false);</script>";
        echo "<script>$('#password_login').prop('disabled',false);</script>";
      }
          
    else
      {
        echo "<span style='color:red'> Employee No.".$employee_no." does not exist, Please try again.</span>";
        echo "<script>$('#password_login').prop('disabled',true);</script>";
        echo "<script>$('#submit_login').prop('disabled',true);</script>";
      }
    }  
?>
