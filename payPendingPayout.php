<?php
session_start();

include 'dbInfo.php';

if (isset($_POST['markPaid'])) {
    /**
     * Declare variables from config file.
     *
     * @var $affiliateTableName string The name of the table containing affiliate information
     * @var $payoutsTableName string The name of the table containing payout information
     * @var $conn mysqli The MySQL DB Connection Variable
     */
    include 'config.php';

    $payoutAmount = $conn->real_escape_string($_POST['payoutAmount']);
    $affiliateId = $conn->real_escape_string($_POST['affiliateID']);
    
    $sql = "SELECT `commissionBalance`, `payoutEmail` FROM `$affiliateTableName` WHERE  `affiliateID` = $affiliateId";
    $result = $conn->query($sql)->fetch_assoc();
    $previousCommissionBalance = $result['commissionBalance'];
    $payoutEmail = $result['payoutEmail'];

    $newCommissionBalance = $previousCommissionBalance - $payoutAmount;

    $sql = "UPDATE `$affiliateTableName` SET `commissionBalance` =  $newCommissionBalance WHERE `affiliateID` = $affiliateId";
    $result = $conn->query($sql);

    $sql2 = "INSERT INTO  `$payoutsTableName` (`affiliate`, `date`, `amount`, `email`) VALUES ($affiliateId, now(), $payoutAmount, '$payoutEmail')";
    $result = $conn->query($sql2);

    header("Location: payout.php");
    die();
}