<?php
include "../connect.php";
session_start();
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $userN = $con->real_escape_string($_POST['email']);
  $pass = $con->real_escape_string($_POST['psswrd']);
    $hashedpas = sha1($pass);
    $sql = "SELECT * FROM user WHERE email = ? AND psswrd = ?";
    $stmt=$con->prepare($sql);
      $stmt->bind_param("ss", $userN, $hashedpas);
        $stmt->execute();
          $result = $stmt->get_result();
          $num_of_rows = $result->num_rows;
            if($num_of_rows > 0){
              while($row = $result->fetch_assoc()) {
                $isAdmin=$row["isAdmin"];
                $_SESSION['fname'] = $row["fname"];
                $_SESSION['isAdmin'] = $isAdmin;
                $_SESSION['email'] = $userN;
                $_SESSION['psswrd'] = $hashedpas;
                if($isAdmin=="1"){
                  header("Location: ../Admin.php");
                  exit();
                }
                else {
                  header("Location: ../index.php");
                  exit();
              }
            }
        }
        else{          
          header("Location: ../loginform.php");
          $message = "Username and/or Password incorrect.\\nTry again.";
          echo "<script type='text/javascript'>alert('$message');</script>";
        }
        $stmt->close();
    }
    $con->close();
?>