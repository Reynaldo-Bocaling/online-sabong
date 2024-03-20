<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['openMeron'])){
		$result = 0;
		
		$qcheck = $mysqli->query("SELECT `id`, `isBetting`, `fightCode` FROM `tblfights` ORDER BY id DESC LIMIT 1; ");
		if($qcheck->num_rows > 0){
			$rcheck = $qcheck->fetch_assoc();
			$fightCode = $rcheck['fightCode'];
			$fightID = $rcheck['id'];
			if($rcheck['isBetting'] == 1 OR $rcheck['isBetting'] == 4){ // check if Current Fight Status is OPEN or LAST CALL
				$u = $mysqli->query("UPDATE `tblfights` SET `closeMeron` = '0' WHERE id = '".$fightID."' ");
				if($u){
					$logAction = $fightCode.": Bettings for MERON is now Open.";
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