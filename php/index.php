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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="script/uploadImage.js" type="text/javascript"></script>
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

                <span class="navbar-brand"><img
                        src="styles/logo.png"
                        class="img-rounded" alt="Logo" height="30" width="200"></span>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Home</a></li>
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


<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="col-sm-12 text-center">
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                  <input id="imageInput"  class="btn btn-primary btn-md center-block" type="file" name="pic" accept="image/*" id="image">
                </form>
                <button id="uploadButton" class="btn btn-primary btn-md center-block">Upload</button>
            </div>
        </div>
    </div>
</div>

<div class="">
  <canvas  id="displayimage" class="img-responsive center-block" width="1000" height="600"></canvas>
  <p id="coordinates1"></p>

</div>

<div class="testDisplayResultsForm">
  <form id="resultsForm" action="displayResults.php" method="post">
    <input type="hidden" name="results" id="results" value="This needs to be set.">
  </form>
</div>

<div id="myNav" class="overlay">
  <span class="closebtn">&times;</span>
  <div class="overlay-content">
    <select class="mySelect">
    <option value="fuel_quantity">fuel quanitity</option>
    <option value="knots">knots</option>
    <option value="eng">eng</option>
    <option value="torque">torque</option>
    <option value="fuel_psi">fuel psi</option>
  </select>
  <span id="closeNav" class="center" ><button id="confirmButton">confirm</button></span>
  </div>
</div>

<footer class="footer">
    <div class="navbar-fixed-bottom">
        <small>&copy; Copyright 2018, perceptsystems</small>
    </div>
</footer>
</body>
</header>
</html>
