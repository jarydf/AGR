<?php
	session_start();
	include './auth.php';
	if(!$isAuth){
		header("Location: ../loginForm.php");
		exit;
	}
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		include './connect.php';
		$fname = $con->real_escape_string($_POST['firstname']);
		$lname = $con->real_escape_string($_POST['lastname']);
		$email = $con->real_escape_string($_POST['email']);
		$paswrd = $con->real_escape_string($_POST['password']);
		$set = isset($fname) && isset($lname) && isset($email) && isset($paswrd);
		if($set){
			$hashpass = sha1($paswrd);

			$insrt = "INSERT INTO User(fname, lname, email, psswrd) VALUES (?,?,?,?)";
			$stmt = $con->prepare($insrt);
			$stmt->bind_param("ssss",$fname,$lname,$email,$hashpass);
			$stmt->execute();

			// header("Location: ../Admin.php");
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
