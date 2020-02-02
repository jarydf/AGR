<?php
session_start();
include '../connect.php';

// catch the image file and write it to the server's file system
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ( 0 < $_FILES['image']['error'] ) {
    echo 'Error: ' . $_FILES['image']['error'];
  }
  else {

    // retrieve the user's id from the database based on their email

    $sql = "SELECT pId FROM user WHERE email = ?";
    $userId = NULL;
    $retrievalSuccess = false;

    if ($stmt = $con->prepare($sql)) {
      if ($stmt->bind_param('s', $_SESSION['email'])) {
        if ($stmt->execute()) {
          if ($stmt->bind_result($userId)) {
            if ($stmt->fetch()) {
              $retrievalSuccess = true;
              $stmt->close();
            }
          }
        }
      }
    }

    // if the retrieval failed, report it
    if (!$retrievalSuccess) {
      echo 'Failed to retrieve user\'s id from the database';
      mysqli_close($con);
      exit();
    }

    // insert a new task into the database, linked to the user's id
    $sql = "INSERT INTO Task (pId, taskName, taskState) VALUES (?, ?, ?)";
    $insertSuccess = false;
    $state = 0;

    if ($stmt = $con->prepare($sql)) {
      if ($stmt->bind_param('isi', $userId, $_FILES['image']['name'], $state)) {
        if ($stmt->execute()) {
          $insertSuccess = true;
          $stmt->close();
        }
      }
    }

    // if the insertion failed, report it
    if (!$insertSuccess) {
      echo 'Failed to insert task to the database!';
      mysqli_close($con);
      exit();
    }

    // retrieve the task id from the database
    $taskId = $con->insert_id;
    mysqli_close($con);

    // create a directory for this task if it doesn't already exist
    $directoryPath = '../../input/' . $taskId;
    if (!file_exists($directoryPath)) {
      mkdir($directoryPath);
    }

    // write the image on the server
    $filePath = $directoryPath . '/' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $filePath);

    echo $taskId; // report back the name of the subdirectory
  }
}
?>
