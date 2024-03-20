<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['accountID'])){
	header('location: ../../index.php');
}else{
	$sessionAccountID = $_SESSION['accountID'];
	
	if(isset($_POST['accountID'])){
		$accountID = $_POST['accountID'];
		$transAmount = $_POST['points'];
		$mobileNumber = $_POST['mobileNumber'];
		
		$qe = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1; ");
		if($qe->num_rows > 0){
			$re =  $qe->fetch_assoc();
			$eventID = $re['id'];
			if($sessionAccountID == $accountID){

				$nums = "24680";
				$piv = substr(str_shuffle($nums), 0, 3);
				
				$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
				$rtime = $qtime->fetch_assoc();
				$curdatetime = $rtime['curdatetime'];
				$rand = microtime($curdatetime);
				$stamper = str_replace('.', '', $rand);
				
				$finalstamper =  substr($stamper, -7, 7);
				$transCode =   sprintf("%03d", $piv)  . sprintf("%07d", $finalstamper) . sprintf("%04d", $sessionAccountID);
				//transID = 1 for DEPOST, 2 for WITHDRAW
				$qcheck = $mysqli->query("SELECT `id` FROM `tblnewbalance` WHERE transCode = '".$transCode."' ");
				if($qcheck->num_rows > 0){
						$result =5; //system generation code error. please refresh the page.
				}else{
					$insert  = $mysqli->query("INSERT INTO `tblnewbalance`(`id`, `eventID`, `accountID`, `transCode`, `transID`, `transAmount`, `isProcess`, `transDate`) 
												VALUES ('', '".$eventID."', '".$accountID."', '".$transCode."', '1', '".$transAmount."', '0', NOW() ) ");
												// transaction ID 1 = DEPOSIT, reference table tbltransaction
					if($insert){
						
						$logAction = "Request for Additional points amounting to ". number_format($transAmount,2). "; Generated Transaction Code: ". $transCode;
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '1', '".$accountID."', '', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						
						//$result = $barcode;	
						$result = '<span style="font-weight:bold;">Transaction Code:</span><br/><span style="font-size:40px; font-weight:bold; color:blue;">' . $transCode .'</span><br/>';
					}else{
						$result = 0;
					}
				}
			}else{
				$result = 2;
			}
		}else{
			$result = 7;
		}
	}else{
		$result = 0;
	}
	echo $result;
}
?>