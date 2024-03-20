<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_REQUEST['transCode'])){
		$transCode = $_REQUEST['transCode'];
		$accountID = $_REQUEST['accountID'];
		$transPin = $_REQUEST['transPin'];
		$transID = $_REQUEST['transID'];
		$q = $mysqli->query("SELECT a.`transAmount`, a.`isProcess`, b.`mobileNumber`, b.`balance`, SUM(b.balance + a.transAmount) as newbalance FROM `tblnewbalance` a LEFT JOIN `tblaccounts` b ON a.accountID = b.id WHERE a.accountID = '".$accountID."' AND a.transCode = '".$transCode."' AND a.transPin = '".$transPin."' AND a.id = '".$transID."' ");
		if($q->num_rows > 0){
			while($r = $q->fetch_assoc()){
				$transAmount = $r['transAmount'];
				$mobileNumber = $r['mobileNumber'];
				$currentBalance = $r['balance'];
				
				$newBalance = $r['newbalance'];
				if($r['isProcess'] == 0){
					$u = $mysqli->query("UPDATE `tblnewbalance` a INNER JOIN `tblaccounts` b ON a.accountID = b.id SET a.`isProcess` = '1', b.balance = (b.`balance` + $transAmount) WHERE a.accountID = '".$accountID."' AND a.transCode = '".$transCode."' AND a.transPin = '".$transPin."' AND a.id = '".$transID."'");
						
					if($u){
						$logAction = "Points added to ".$mobileNumber." amounting to " . number_format($transAmount,2) . "; Account Balance from ".number_format($currentBalance,2)." to ".number_format($newBalance,2)." using Transaction Code: ". $transCode;
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '1', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						$result = 1;
							
					}else{
						$result = 3; 
					}
				}else{
					$result = 2; // means already loaded
				}
			}
		}else{
			$result = 0; // transcode or pin does not exist
		}
		
	}
echo $result;
}
?>