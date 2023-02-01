<?php
/**
 * Declare Variables from dbInfo file.
 *
 * @var $conn mysqli The Database Connection Variable.
 */
include 'dbInfo.php';
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

// Session Fixation Safeguard
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id();
    $_SESSION['initialized'] = true;
}

// Session Hijacking Safeguard
if (!isset($_SESSION['returnedByAdmin']) || $_SESSION['returnedByAdmin'] == false) {
    $seed = "SuperSecretSeed";
    if (isset($_SESSION['HTTP_USER_AGENT'])) {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'] . $seed)) {
            // Redirect to login
            session_destroy();
            header("Location: index.php");
            die();
        }
    } else {
        $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT'] . $seed);
    }
}

if (isset($_SESSION['adminUser'])) {
    $adminUser = test_input($_SESSION['adminUser']);

    $sql = "SELECT `email`, `passChangedTimestamp`, `adStatus` FROM `clients` WHERE `clientID` = '$adminUser'";
    $result = $conn->query($sql)->fetch_assoc();
    $adStatus = $result['adStatus'];

    // Session Takeover Safeguard
    $lastPasswordChangeTime = $result['passChangedTimestamp'];

    if (($_SESSION['loginTime'] - $lastPasswordChangeTime) < 0) {
        session_destroy();
        header("Location: index.php");
        die();
    }

    // Admin Check Safeguard
    if ($adStatus == "2") {
        $email = $result['email'];
        $_SESSION['email'] = $email;
    } else {
        header("Location: index.php");
        die();
    }
} else {
    header("Location: index.php");
    die();
}