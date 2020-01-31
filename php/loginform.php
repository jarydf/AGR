<?php
session_start();
?>
<?php if(isset($_SESSION['user']) && $_SESSION['user'] != ""): ?>
<?php header("Location: ./index.php"); exit;?>
<?php else: ?>
<!DOCTYPE html>
<html>
   <head>
      <title>Login</title>
      <meta charset="utf-8">

            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
            <link href="styles/login.css" rel="stylesheet">
   </head>
<header>
   <body>

      <div class = "container">

        <form action = "action/loginAction.php" method = "post">
          <div class="form-group">
            <label for="email">Email address:</label>
            <input type = "email" class = "form-control"
               name = "email" placeholder = "email@example.com"
               required autofocus></br>
          </div>

          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type = "password" class = "form-control"
               name = "pass" required>
          </div>
          <div class="form-group form-check">
            <label class="form-check-label">
            <input class="form-check-input" type="checkbox"> Remember me
          </label>
         </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>

   </body>
 </header>
</html>
<?php  endif; ?>
