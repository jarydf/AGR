<?php
	session_start();
	include 'auth.php';

	$check = isset($_SESSION['user']) && isset($_SESSION['isAdmin']) && $_SESSION['user'] != '' && $_SESSION['isAdmin'] && $isAuth;
	$check2 = isset($_SESSION['user']) && isset($_SESSION['email']) && $_SESSION['user'] != '' && $isAuth && $_SESSION['email'] != '';

	if($check){

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			include 'connect.php';

			$email = $_POST['email'];
			$newPass = sha1($_POST['psswrd']);

			$userId = "SELECT pId FROM User WHERE email=?";
			$stmt = $con->prepare($userId);
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$stmt->store_result();
			$numberofrows = $stmt->num_rows;
			$stmt->close();


			if($numberofrows > 0){
				$pasupdate = "UPDATE User SET psswrd=? where email=?";
				$stmt = $con->prepare($pasupdate);
				$stmt->bind_param('ss', $newPass, $email);
				$stmt->execute();
				$stmt->close();
				mysqli_close($con);

				$message = "The password for user with email: {$email} has changed";
				echo "<script type='text/javascript'>alert('$message');</script>";
				header("refresh:1;url=../Admin.php");
			}

			else{

				$message = "no such user with email ==> {$email} exists";
				echo "<script type='text/javascript'>alert('$message');</script>";
				mysqli_close($con);
				header("refresh:1;url=../Admin.php");
			}
		}
		else{
			header('HTTP/1.1 401 Unauthorized', true, 401);
			exit();
		}
	}
	elseif ($check2){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			include 'connect.php';

			$oldPass = sha1($con->real_escape_string($_POST['oldpass']));
			$newPass = sha1($con->real_escape_string($_POST['psswrd']));

			$email = $_SESSION['email'];

			$chstmt = $con->prepare("SELECT fname FROM User WHERE email=? and psswrd=?");
			$chstmt->bind_param('ss', $email, $oldPass);
			$chstmt->execute();
			$chstmt->store_result();
			$numRows = $chstmt->num_rows;
			$chstmt->close();
			if($numRows>0){
				$pasupdate = "UPDATE User SET psswrd=? where email= ? and fname=?";
				$stmt = $con->prepare($pasupdate);
				$stmt->bind_param('sss', $newPass, $email, $fnom);
				$stmt->execute();
				$message = "The password for user with email: {$email} has changed";
				echo "<script type='text/javascript'>alert('$message');</script>";
				$stmt->close();
				mysqli_close($con);
				header("refresh:1;url=../index.php");
			}

			else{
				$message = "password was entered incorrectly. Could not change password";
				echo "<script type='text/javascript'>alert('$message');</script>";
				mysqli_close($con);
				header("refresh:1;url=../index.php");
			}
		}

		else{
			header('HTTP/1.1 401 Unauthorized', true, 401);
			exit();
		}
	}

	else{
		header("Location: ../loginForm.php");
		exit();
	}
?>
