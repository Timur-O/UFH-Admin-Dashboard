<?php
/**
 * Declare variables defined in the dbInfo file
 * @var $conn mysqli The database connection variable
 */
session_start();

if (isset($_SESSION['user']) || isset($_SESSION['adminUser'])) {
    include 'dbInfo.php';
    include 'config.php';

    $_SESSION['adminLogin'] = false;

    if (isset($_SESSION['user'])) {
        $user = test_input($_SESSION['user']);
    } else {
        $user = test_input($_SESSION['adminUser']);
    }

    $sql = "SELECT `email`, `adStatus` FROM `clients` WHERE `clientID` = '$user'";
    $result = $conn->query($sql)->fetch_assoc();
    $adStatus = $result['adStatus'];

    if ($adStatus == "2") {
        $adminUser = $user;
        $_SESSION['adminUser'] = $adminUser;

        //Redirect to overview
        header("Location: overview.php"); die();
    }
} else {
    $_SESSION['adminLogin'] = true;

    // Redirect to main login page
    header("Location: https://app.ultifreehosting.com/login.php"); die();
}