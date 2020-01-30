<?php
if (!isset($_SESSION)) {
  session_start();
}

include 'auth.php';
if (!$isAuth) {
  exit();
}

// look for the file containing the dummy output data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['taskId'])) {
  $file_name = '../../output/' . $_POST['taskId'] . '.json';

  if (!file_exists($file_name)) {
    die('no results');
  }

  $jsonOutput = file_get_contents($file_name);
  $arrayOutput = json_decode($jsonOutput, true);
  foreach($arrayOutput as $item) {
    echo $item['value'].',';
    echo $item['gaugeType'].',';
    echo $item['taskId'].'|';
}
  exit();
}

echo 'no results';
?>
