<?php
session_start();
include './action/auth.php';
if(!$isAuth){
  header('Location: loginform.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Analog_Gauge_Reader</title>
    <link  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="script/uploadImage.js" type="text/javascript"></script>
    <script src="script/savecsv.js" type="text/javascript"></script>
    <link href="styles/uploadImage.css" rel="stylesheet">


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
<button id="export" class="btn btn-primary btn-md center-block">export</button>
<div class="container">
  <table class="table">
    <thead>
      <tr>
        <th>Gauge type</th>
        <th>Value</th>
        <th>TaskId</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // JSON string
      $results=$_POST['results'];
      $myArray = explode('|', $results);
      $count=count($myArray);
      for($i = 0, $l = $count-1; $i < $l; ++$i) {
        $myArray1=explode(',',$myArray[$i]);
        ?>
        <tr>
        <td><?php echo $myArray1[1]; ?></td>
        <td><?php echo $myArray1[0]; ?></td>
        <td><?php echo $myArray1[2]; ?></td>
      </tr>
      <?php
    }
      ?>
    </tbody>
  </table>
</div>








<footer class="footer">
    <div class="navbar-fixed-bottom">
        <small>&copy; Copyright 2018, perceptsystems</small>
    </div>
</footer>
</body>
</header>
</html>
