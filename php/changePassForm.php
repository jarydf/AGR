
<?php
	session_start();
	include './action/auth.php';
	$check = isset($_SESSION['user']) && isset($_SESSION['isAdmin']) && $_SESSION['user'] != '' && $_SESSION['isAdmin'] && $isAuth;
  $check2 = isset($_SESSION['user']) && $_SESSION['user'] != '' && $isAuth && $_SESSION['email'] != '';
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<script type="text/javascript" src="script/validate.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="styles/cp.css" rel="stylesheet">

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
<?php if($check): ?>
<div class="col-md-4 col-md-offset-4">
  <form method="post" action="./action/changePasswrd.php" id="mainForm" >
    Email:<br>
    <input type="email" name="email" id="email" class="required">
    <br><br>
    New Password:<br>
    <input type="password" name="psswrd" id="pssword" class="required">
    <br>
    Re-enter New Password:<br>
    <input type="password" name="newpassword-check" id="pssword-check" class="required">
    <br><br>
    <input type="submit" value="Update Password">
  </form>
</div>
<?php elseif ($check2): ?>
<div class="col-md-4 col-md-offset-4">
  <form method="post" action="./action/changePasswrd.php" id="mainForm" >
    Old Password:<br>
    <input type="password" name="oldpass" id="oldpass" class="required">
    <br><br>
    New Password:<br>
    <input type="password" name="psswrd" id="pssword" class="required">
    <br>
    Re-enter New Password:<br>
    <input type="password" name="newpassword-check" id="pssword-check" class="required">
    <br><br>
    <input type="submit" value="Update Password">
  </form>
</div>
<?php else: ?>
<?php header("Location: loginForm.php"); ?>
<?php endif; ?>

</body>
</header>
<!-- Password checking script here! -->
<script type="text/javascript">
  var checkPasswordMatch = function(e){
    var pswd = document.getElementById('pssword');
    var pswd_chek = document.getElementById('pssword-check');
    if(pswd.value != pswd_chek.value){
      e.preventDefault();
      makeRed(pswd_chek);
      makeRed(pswd);
      alert("passwords must match!");
    }
  }
</script>
</html>
