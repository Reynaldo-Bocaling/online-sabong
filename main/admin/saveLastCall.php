<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['lastcall'])){
		$result = 0;
		
		$qcheck = $mysqli->query("SELECT `isBetting` FROM `tblfights` ORDER BY id DESC LIMIT 1; ");
		if($qcheck->num_rows > 0){
			$rcheck = $qcheck->fetch_assoc();
			
			if($rcheck['isBetting'] == 1){ // check if Current Fight Status is OPEN
				$u = $mysqli->query("UPDATE `tblfights` SET `isBetting` = '4' ORDER BY id DESC LIMIT 1 "); // isBetting = 4 means change fight status to LAST CALL
				if($u){
					$query = $mysqli->query("SELECT `fightCode` FROM `tblfights` ORDER BY id DESC LIMIT 1");
					$row = $query->fetch_assoc();
					$fightCode = $row['fightCode'];
					$logAction = $fightCode.": Last call for bettings";
					$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					$result = 1;
				}else{
					$result = 0;
				}
			
			}else{
				$result = 2;
			}
		}
		echo $result;
	}

}
?>