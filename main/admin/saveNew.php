<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['startNew'])){
		$result = 0;
		$checkEvent = $mysqli->query("SELECT * FROM `tblevents` WHERE eventStatus = '0' ORDER BY id DESC LIMIT 1; ");
		if($checkEvent->num_rows > 0){
			$rowcheckEvent =  $checkEvent->fetch_assoc();
			$eventID = $rowcheckEvent['id'];
			$curdate = $rowcheckEvent['eventDate'];
			if($rowcheckEvent['eventStatus'] == 0){
				$qcheck = $mysqli->query("SELECT `isBetting`, `fightNumber` + 1 as fightNum FROM `tblfights` WHERE eventID = '".$eventID."' ORDER BY id DESC LIMIT 1; ");
		
				if($qcheck->num_rows > 0 ){
					$rcheck = $qcheck->fetch_assoc();
					$isBetting = $rcheck['isBetting'];
					$currentFightNumber = $rcheck['fightNum'];
					$fightCode = $eventID.substr($curdate,2).$currentFightNumber;
					if($isBetting == 5 OR $isBetting == 6){
						$insert = $mysqli->query("INSERT INTO `tblfights`(`id`, `eventID`, `fightCode`, `fightNumber`, `isBetting`, `isWinner`, `percentlessID`) VALUES ('', '".$eventID."', '".$fightCode."', '".$currentFightNumber."', '1', '0', (SELECT MAX(id) FROM tblpercentless) );");
									
						if($insert){
							$logAction = $fightCode.": Started new fight bettings";
							$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							$result = 1;
						}else{
							$result = 0;
						}	
					}else{
						$result = 2;
					}
				}else{
					$fightCode = $eventID.substr($curdate,2) . '1';
					$insert = $mysqli->query("INSERT INTO `tblfights`(`id`, `eventID`, `fightCode`, `fightNumber`, `isBetting`, `isWinner`, `percentlessID`) VALUES ('', '".$eventID."', '".$fightCode."', '1', '1', '0', (SELECT MAX(id) FROM tblpercentless) );");
					if($insert){
						$logAction = $fightCode.": Started new fight bettings";
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						$result = 1;
					}else{
						$result = 0;
					}
				}
			}else{
				$result = 4; // event already clsoed
			}
		}else{
			$result = 3; // no event
		}

		echo $result;
	}

}
?>