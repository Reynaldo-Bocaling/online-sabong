<?php
require('connection.php');
session_start();

if($_SESSION['roleID'] == 3){
	$logAction = "LOGOUT";
	$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '".$_SESSION['accountID']."', '', '3', '".$logAction."', NOW());");
	session_destroy();
	header("Location: ../../index.php");
}else{
	$logAction = "LOGOUT";
	$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
	session_destroy();
	header("Location: ../../index.php");
}


?>