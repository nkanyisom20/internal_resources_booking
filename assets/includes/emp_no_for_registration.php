<?php
  include 'db.php';

  $employee_no = $_POST["employee_no"];
  
  if(empty($employee_no)){
    echo "<span style='color:red'> Please enter Employee No.</span>";
    echo "<script>$('#add_new_user').prop('disabled',true);</script>";
    }
  elseif(!ctype_digit($employee_no)){
    echo "<span style='color:red'> Enter Only Numbers as Employee No.</span>";
    echo "<script>$('#add_new_user').prop('disabled',true);</script>";
    }
  elseif(strlen($employee_no) < 9){
    echo "<span style='color:red'> Enter 9 Numbers as Employee No.</span>";
    echo "<script>$('#add_new_user').prop('disabled',true);</script>";
    }
  else
    {
      $sql_select = $pdo->prepare("SELECT employee_no FROM users  WHERE employee_no = ?;");
      $sql_select->execute([$_POST['employee_no']]);
      $user = $sql_select->fetch();
      
      if ($user > 0) 
      {
        echo "<span style='color:red'> Employee No. already associated with another account .</span>";
        echo "<script>$('#add_new_user').prop('disabled',true);</script>";
      }
    else
      {
      	echo "<span style='color:green'> Employee No. Available for Registration .</span>";
        echo "<script>$('#add_new_user').prop('disabled',false);</script>";
      }
    }
?>
