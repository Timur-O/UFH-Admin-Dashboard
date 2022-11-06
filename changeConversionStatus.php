<?php
session_start();

/**
 * Declare variables defined in config and dbInfo files.
 *
 * @var $conn mysqli The database connection variable
 * @var $conversionsTableName string The name of the table containing conversion information
 * @var $affiliateTableName string The name of the table containing affiliate information
 */
include 'dbInfo.php';
include 'config.php';

if (isset($_POST['approveConversion'])) {
    $conversionId = $conn->real_escape_string($_POST['conversionID']);
    $affiliateId = $conn->real_escape_string($_POST['affiliateID']);

    $sql = "UPDATE `$conversionsTableName` SET `approved` =  1 WHERE `conversionID` = $conversionId";
    $result = $conn->query($sql) or die($conn->error);

    $sql2 = "SELECT `commissionAmount` FROM `$conversionsTableName` WHERE  `conversionID` = $conversionId";
    $result = $conn->query($sql2)->fetch_assoc();
    $commissionAmount = $result['commissionAmount'];

    $sql3 = "UPDATE `$affiliateTableName` SET `commissionBalance` =  `commissionBalance` + $commissionAmount WHERE `affiliateID` = $affiliateId";
    $result = $conn->query($sql3) or die($conn->error);

    header("Location: conversions.php");
    die();
} else if (isset($_POST['rejectConversion'])) {
    $conversionId = $conn->real_escape_string($_POST['conversionID']);

    $sql = "UPDATE `$conversionsTableName` SET `approved` = 2 WHERE `conversionID` = $conversionId";
    $result = $conn->query($sql);
    header("Location: conversions.php");
    die();
}