<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['printsettingsID'])){
		$result = 0;
		$printSettingsID = $_POST['printsettingsID'];
		
		$update = $mysqli->query("UPDATE `tblsystem` SET `systemPrint` = '".$printSettingsID."'");
		if($update){
			if($printSettingsID == 1){
				$printSettingsValue = "YES";
			}else{
				$printSettingsValue = "NO";
			}
			$logAction = " Updated Payout Print Settings for Double Printing of Receipt to  " .$printSettingsValue;
			$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
			$result = 1;
		}else{
			$result = 0;
		}
		echo $result;
	}

}
?>