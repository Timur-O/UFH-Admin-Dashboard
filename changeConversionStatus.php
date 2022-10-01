<?php
session_start();
include 'dbInfo.php';
if (isset($_POST['approveConversion'])) {
    include 'config.php';
    $sql = "UPDATE `{$conversionsTableName}` SET `approved` =  1 WHERE `conversionID` = {$_POST['conversionID']}";
    $result = $conn->query($sql) or die($conn->error);

    $sql2 = "SELECT `commissionAmount` FROM `{$conversionsTableName}` WHERE  `conversionID` = {$_POST['conversionID']}";
    $result = $conn->query($sql2)->fetch_assoc();
    $commissionAmount = $result['commissionAmount'];

    $sql3 = "UPDATE `{$affiliateTableName}` SET `commissionBalance` =  `commissionBalance` + {$commissionAmount} WHERE `affiliateID` = {$_POST['affiliateID']}";
    $result = $conn->query($sql3) or die($conn->error);

    header("Location: conversions.php");
    die();
} else if (isset($_POST['rejectConversion'])) {
    include 'config.php';
    $sql = "UPDATE `{$conversionsTableName}` SET `approved` = 2 WHERE `conversionID` = {$_POST['conversionID']}";
    $result = $conn->query($sql);
    header("Location: conversions.php");
    die();
}
?>