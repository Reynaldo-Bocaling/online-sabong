<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['accountID'])){
		$result = 0;
		$accountID = $_POST['accountID'];
		$payoutSettings = $_POST['payoutSettings'];
		
		$query = $mysqli->query("SELECT a.`id` FROM `tblusers` a WHERE a.isActive = '1' AND a.roleID = '2' AND a.id = '".$accountID."' LIMIT 1");
		if($query->num_rows > 0){
			$update = $mysqli->query("UPDATE `tblusers` SET `payoutSettings` = '".$payoutSettings."' WHERE id = '".$accountID."' ");
			if($update){
				if($payoutSettings == 1){
					$payoutSettingsValue = "YES";
				}else{
					$payoutSettingsValue = "NO";
				}
				$logAction = " Updated Teller Payout Settings to allow PAYOUT  " .$payoutSettingsValue;
				$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
				$result = 1;
			}else{
				$result = 0;
			}
		}else{
			$result = 2; // no teller ID
		}
		
		
		echo $result;
	}

}
?>