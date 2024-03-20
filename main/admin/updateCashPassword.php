<?php
session_start();
require('../includes/connection.php');
require('../includes/functions.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_REQUEST['newPassword'])){
		$result = 0;
		$newPassword = sanitize($_REQUEST['newPassword'], $mysqli);
		
		$update = $mysqli->query("UPDATE `tblsystem` SET `cashPassword`= '".md5($newPassword)."' ");
		if($update){
			$logAction = " Updated the Cash In/Out Password ";
			$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
			$result = 1;
		}else{
			$result = 0;
		}
		echo $result;
	}
}
?>