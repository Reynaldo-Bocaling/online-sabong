<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['accountID'])){
		$accountID = $_POST['accountID'];
		$mobileNumber = $_POST['mobileNumber'];
		$query = $mysqli->query("SELECT `id` FROM `tblaccounts` WHERE id = '".$accountID."' AND mobileNumber = '".$mobileNumber."' ");
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$update = $mysqli->query("UPDATE `tblaccounts` SET `mobilePassword` = '".md5($mobileNumber)."' WHERE id = '".$accountID."' ");
				if($update){
					$logAction = "Password reset successfully for Mobile Number: ".$mobileNumber;
					$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					$result = 1;
				}
			}
		}else{
			$result = 0;
		}
		echo $result;
	}
}
?>