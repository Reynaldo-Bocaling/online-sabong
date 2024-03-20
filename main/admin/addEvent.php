<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['userStatus'])){

	//	$eventStatus = $_POST['eventStatus'];
		$userStatus = $_POST['userStatus'];
		
		$checkEvent = $mysqli->query("SELECT `id` FROM `tblevents` WHERE `eventStatus` = '0' ");
		if($checkEvent->num_rows > 0){
			$result = 2; // may nakaopen pang event
		}else{
			$insert = $mysqli->query("INSERT INTO `tblevents`(`id`, `eventDate`, `eventStatus`, `userAccessStatus`) VALUES ('', NOW(), '0', '".$userStatus."')");
			if($insert){
				$logAction = "Added event";
				$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
				$result = 1;
			}else{
				$result = 0;
			}
		}
		echo $result;
	}
}
?>