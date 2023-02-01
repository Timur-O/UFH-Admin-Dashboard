<?php
    session_start();
    session_destroy();
    //Redirect
    header("Location: https://app.ultifreehosting.com/login.php"); die();