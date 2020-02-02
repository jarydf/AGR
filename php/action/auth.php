<?php
if (!isset($_SESSION)) {
  session_start();
}

$isAuth = false;
if (isset($_SESSION['email']) && $_SESSION['email'] != '') {
  $isAuth = true;
}
?>
