<?php
session_start();

/**
 * Declare variables defined in the dbInfo file
 * @var $conn mysqli The database connection variable
 */
include 'dbInfo.php';

if (isset($_SESSION['adminUser'])) {
  $adminUser = $_SESSION['adminUser'];
  $sql = "SELECT `email`, `adStatus` FROM `clients` WHERE `clientID` = '$adminUser'";
  $result = $conn->query($sql)->fetch_assoc();
  $adStatus = $result['adStatus'];
  if ($adStatus == "2") {
    //Redirect to overview
    header("Location: overview.php"); die();
  }
}

$passwordEmailError = "";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <?php include 'head.php';?>
    <title>Login - Admin Panel</title>
  </head>

  <body>
    <div class="loginmain">
      <div class="row rowtoppadded10">
        <div class="col s4 offset-s4 loginbox center">
          <h5>Login</h5>
          <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $passwordEmailError = "";
                $passwordValid = $emailValid = false;

                if (empty($_POST["email"])) {
                  $passwordEmailError = "Email Required";
                } else {
                  $email = test_input($_POST["email"]);
                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $passwordEmailError = "Invalid Email Format";
                  } else {
                    $sql = "SELECT * FROM `clients` WHERE `email` = '$email' AND `adStatus` = '2'";
                    $result = $conn->query($sql);

                    if (empty($result) OR $result->num_rows === 0) {
                      $passwordEmailError = "Username & Password Combination Not Found";
                    } else {
                      // Account Exists
                      $emailValid = true;
                    }
                  }
                }

                if (empty($_POST["password"])) {
                  $passwordEmailError = "Password Required";
                } else {
                  $password = test_input($_POST["password"]);

                  $sql = "SELECT `password` FROM `clients` WHERE `email` = '$email' AND `adStatus` = '2'";
                  $result = $conn->query($sql) or die($conn->error);
                  $result = $result->fetch_assoc();
                  $hashPass = $result['password'];

                  if (password_verify($password, $hashPass)) {
                    //Passwords Match
                    $passwordValid = true;
                  } else {
                    $passwordEmailError = "Username & Password Combination Not Found";
                  }
                }

                if ($passwordValid && $emailValid) {
                  $sql = "SELECT `clientID` FROM `clients` WHERE `email` = '$email'";
                  $result = $conn->query($sql) or die($conn->error);
                  $result = $result->fetch_assoc();
                  $clientID = $result['clientID'];

                  $_SESSION['adminUser'] = $clientID;
                  $_SESSION['email'] = $email;

                  //Redirect
                  header("Location: overview.php"); die();
                }
            }
          ?>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="input-field col s12">
              <input id="email" type="email" class="validate" name="email">
              <label for="email">Email</label>
            </div>
            <div class="input-field col s12">
              <input id="password" type="password" class="validate" name="password">
              <label for="password">Password</label>
            </div>
            <p class="red-text"><?php echo $passwordEmailError;?></p>
            <div class="col s12 rowbottompadded">
              <button class="btn waves-effect waves-light" type="Submit" name="action">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php include 'foot.php';?>

  </body>

</html>
