<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['eventID'])){
		$result = 0;
		$eventID = $_POST['eventID'];
		
		$u = $mysqli->query("UPDATE `tblevents` SET `userAccessStatus` = '0' WHERE id = '".$eventID."'");
		if($u){
			$logAction = "Opened the system to user access";
			$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
			$result = 1;
		}else{
			$result = 0;
		}	
		echo $result;
	}
}
?>