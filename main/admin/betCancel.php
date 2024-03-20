<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['betCode'])){
		$result = 0;
		$barcode = mysqli_real_escape_string($mysqli, $_POST['betCode']);
		$q = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`betCode`, a.`betType`, a.`betAmount`, a.`userID`, b.`eventID` FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE a.betCode = '".$barcode."' AND (b.isBetting = '1' OR b.isBetting = '4') LIMIT 1");
		if($q->num_rows > 0){
			while($r = $q->fetch_assoc()){
				$betListID = $r['id'];
				$eventID = $r['eventID'];
				$userID = $r['userID'];
				$saveBetAmount = $r['betAmount'];
				$fightCode = $r['fightCode'];
				$betType = $r['betType'];
				
				if($betType == 1){
					$saveBetType = "MERON";
				}else{
					$saveBetType = "WALA";
				}
				
				$update = $mysqli->query("UPDATE `tblbetliststemp` SET `isCancelled` = '1' WHERE betCode = '".$barcode."' AND id = '".$betListID."' ");
				if($update){
					$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES ('', '".$eventID."', '".$userID."', '9', '".$barcode."', '".$saveBetAmount."', NOW() ) ");
					if($insertTrans){
						$result = 1;
						$logAction = $fightCode.": Ticket BET under ". $saveBetType. " amounting to " . number_format($saveBetAmount,2) . "; Generated Transaction Code: ". $barcode;
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '11', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					}else{
						$update = $mysqli->query("UPDATE `tblbetliststemp` SET `isCancelled` = '0' WHERE betCode = '".$barcode."' AND id = '".$betListID."' ");
						$result = 0;
					}
					//transactionID 8  is for BET
					
				}else{
					$result = 0;
				}
				
			}
		}else{
			$result = 2;
		}
		echo $result;
	}

}
?>