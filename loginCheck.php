<?php
/**
 * Declare Variables from dbInfo file.
 *
 * @var $conn mysqli The Database Connection Variable.
 */
include 'dbInfo.php';

if (isset($_SESSION['adminUser'])) {
  $adminUser = $_SESSION['adminUser'];
  $sql = "SELECT `email`, `adStatus` FROM `clients` WHERE `clientID` = '$adminUser'";
  $result = $conn->query($sql)->fetch_assoc();
  $adStatus = $result['adStatus'];

  if ($adStatus == "2") {
    $email = $result['email'];
    $_SESSION['email'] = $email;
  } else {
    //Redirect Bc Not Admin
    header("Location: index.php"); die();
  }
} else {
  //Redirect
  header("Location: index.php"); die();
}