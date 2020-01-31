<?php
include "../connect.php";
session_start();
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $userN = $con->real_escape_string($_POST['email']);
  $pass = $con->real_escape_string($_POST['pass']);
  if(isset($userN) && isset($pass)){
    $hashedpas = sha1($pass);
    $sql = "SELECT fname, isAdmin FROM User WHERE email = ? AND psswrd = ?";
    if($stmt=$con->prepare($sql)){
      if($stmt->bind_param("ss", $userN, $hashedpas)){
        if($stmt->execute()){
          if($stmt->bind_result($fname, $admin)){
            if($stmt->fetch()){
              $_SESSION['user'] = $fname;
              $_SESSION['isAdmin'] = $admin;
              $_SESSION['email'] = $userN;

              mysqli_close($con);
              $stmt->close();
              if($_SESSION['isAdmin']){
                header("Location: ../Admin.php");
                exit();
              }
              else{
                header("Location: ../index.php");
                exit();
              }
            }
            else{
              mysqli_close($con);
              header("Location: ../loginform.php");
              exit();
            }
          }
        }
      }
      $stmt->close();
    }
  }
  mysqli_close($con);
}
else{
  header("Location: ../loginform.php");
}
?>
