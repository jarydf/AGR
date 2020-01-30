<?php
if (!isset($_SESSION)) {
  session_start();
}

$isAuth = false;
if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
  $isAuth = true;
}
?>
