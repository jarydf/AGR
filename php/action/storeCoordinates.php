<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['coordinates'])) {
    $response = array();
    $response['coordinates'] = $_POST['coordinates'];

    $fp = fopen('../../img/pre_proc/test.txt', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
  }
}
?>
