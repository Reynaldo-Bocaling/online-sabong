<?php
session_start();
require('../includes/connection.php');
require('../includes/functions.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_REQUEST['oldPassword'])){
		$result = 0;
		$oldPassword = sanitize($_REQUEST['oldPassword'], $mysqli);
		$newPassword = sanitize($_REQUEST['newPassword'], $mysqli);
		
		$query = $mysqli->query("SELECT `password` FROM `tblusers` WHERE password = '".md5($oldPassword)."' AND roleID = '9'  AND isActive = '1' and betTypeID = '0'  LIMIT 1");
		if($query->num_rows > 0){
			$update = $mysqli->query("UPDATE `tblusers` SET `password`= '".md5($newPassword)."' WHERE password = '".md5($oldPassword)."' AND roleID = '9'  AND isActive = '1' and betTypeID = '0'  ");
			if($update){
				$logAction = " Updated the system refresh password ";
				$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
				$result = 1;
			}else{
				$result = 0;
			}
		}else{
			$result = 2;
		}
		echo $result;
	}

}
?>