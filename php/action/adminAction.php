<?php
	session_start();
	include '../connect.php';
	include './auth.php';
	if(!$isAuth){
		header("Location: ../loginForm.php");
		exit;
	}
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$fname = $con->real_escape_string($_POST['firstname']);
		$lname = $con->real_escape_string($_POST['lastname']);
		$email = $con->real_escape_string($_POST['email']);
		$psswrd = $con->real_escape_string($_POST['password']);
		$set = isset($fname) && isset($lname) && isset($email) && isset($psswrd);
		if($set){
			$hashpass = sha1($psswrd);
			$insrt = "INSERT INTO user(fname, lname, email, psswrd) VALUES (?,?,?,?)";
			$stmt = $con->prepare($insrt);
			$stmt->bind_param("ssss",$fname,$lname,$email,$hashpass);
			$stmt->execute();
			$stmt->close();
			header("Location: ../Admin.php");
		}
		mysqli_close($con);
	}
	else{
		mysqli_close($con);
		header("Location: ../Admin.php");
	}

?>
