<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['closeEventID'])){
		$result = 0;
		
		$qcheck = $mysqli->query("SELECT `id` FROM `tblevents` WHERE eventStatus = '0' ORDER BY id DESC LIMIT 1; ");
		if($qcheck->num_rows > 0){
			$rcheck = $qcheck->fetch_assoc();
			$eventID = $rcheck['id'];
			$qcheck = $mysqli->query("SELECT `isBetting` FROM `tblfights` WHERE eventID = '".$eventID."' ORDER BY id DESC LIMIT 1; ");
			if($qcheck->num_rows > 0 ){
				$rcheck = $qcheck->fetch_assoc();
				$isBetting = $rcheck['isBetting'];
				if($isBetting == 5 OR $isBetting == 6){
					$u = $mysqli->query("UPDATE `tblevents` SET `eventStatus` = '1' WHERE id = '".$eventID."'");
					if($u){
						$logAction = "Closed the event";
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						$result = 1;
					}else{
						$result = 0;
					}	
				}else{
					$result = 2;
				}
			}else{
				$u = $mysqli->query("UPDATE `tblevents` SET `eventStatus` = '1' WHERE id = '".$eventID."'");
				if($u){
					$logAction = "Closed the event";
					$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					$result = 1;
				}else{
					$result = 0;
				}	
			}
		}
		echo $result;
	}

}
?>