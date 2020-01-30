<?php
	session_start();
	include './action/auth.php';
?>

<?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] && $isAuth): ?>
	<!DOCTYPE html>
	<html>
		<head>
			<title><?php echo "{$_SESSION['user']}"; ?></title>
			<script type="text/javascript" src="script/validate.js"></script>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
			<link href="styles/adm.css" rel="stylesheet">
		</head>
		<header>
		<body>
			<nav class="navbar navbar-default">
					<div class="container-fluid">
							<div class="navbar-header">

									<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
													data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
									</button>

									<a class="navbar-brand" href="#"><img
													src="styles/logo.png"
													class="img-rounded" alt="Logo" height="30" width="200"></a>
							</div>
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
									<ul class="nav navbar-nav">
									</ul>
									<ul class="nav navbar-nav navbar-right">
											<li><a href="./index.php">Home</a></li>
											<li class="dropdown">
													<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
														 aria-expanded="false">
															More <span class="caret"></span></a>
													<ul class="dropdown-menu">
															<li><a href="./changePassForm.php">Change Password</a></li>
															<li><a href="#">Help</a></li>
															<li role="separator" class="divider"></li>
															<li><a href="./logout.php">Logout</a></li>
													</ul>
											</li>
									</ul>
							</div>
					</div>
			</nav>
			<div class="col-md-4 col-md-offset-4">
			<form method="post" action="./action/adminAction.php" id="mainForm" enctype="multipart/form-data">
				First Name:<br>
				<input type="text" name="firstname" id="firstname" class="required">
				<br>
				Last Name:<br>
				<input type="text" name="lastname" id="lastname" class="required">
				<br>
				email:<br>
				<input type="email" name="email" id="email" class="required">
				<br>
				Password:<br>
				<input type="password" name="password" id="password" class="required">
				<br>
				Re-enter Password:<br>
				<input type="password" name="password-check" id="password-check" class="required">
				<br>
				<input type="submit" value="Create New User" id="sub">
			</form>
<hr>
			<a href="./changePassForm.php"><button>Change User Password</button></a><br>
			<a href="./logout.php"><button>Logout</button></a>
		</div>
		</body>
</header>
		<!-- Password checking script here!! -->
		<script type="text/javascript">
			console.log("works here");
		  	var checkPasswordMatch = function(e) {
		    var pswd = document.getElementById('password');
		    var pswd_chek = document.getElementById('password-check');
		    console.log(pswd);
		    console.log(pswd_chek);
		    if(pswd.value != pswd_chek.value){
		      e.preventDefault();
		      makeRed(pswd_chek);
		      makeRed(pswd);
		      alert("passwords do not match!");
		    }
		  }
			$("#sub").click(function() {
		    alert("User Created");

		  });
		</script>

	</html>
<?php else: ?>
<?php header("Location: loginform.php"); ?>
<?php endif; ?>
