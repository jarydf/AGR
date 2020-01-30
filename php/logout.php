<?php
	session_start();

	include './action/auth.php';

	if($isAuth){
		session_unset();
		session_destroy();

		header("Location: loginform.php");
	}
	else{
		header("Location: loginform.php");
		exit;
	}

?>
