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
		$qe = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1; ");
		if($qe->num_rows > 0){
			$re =  $qe->fetch_assoc();
			$eventID = $re['id'];
			if($sessionAccountID == $accountID){
				$check = $mysqli->query("SELECT * FROM `tblnewbalance` WHERE accountID = '".$accountID."' AND isProcess = '0' AND transID = '2' ");
				
				if($check->num_rows > 0){
					$result = 4;	// with existing
				}else{
					$query = $mysqli->query("SELECT `balance` FROM `tblaccounts` WHERE id = '".$accountID."'");
					if($query->num_rows > 0){
						while($row = $query->fetch_assoc()){
							$currentBalance = $row['balance'];
							
							if($transAmount > $currentBalance){
								$result = 3; // 3 means error will pop up, stating points to withdraw is greater than your current balance.
							}else{
								$nums = "13579";
								$piv = substr(str_shuffle($nums), 0, 3);
								
								$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
								$rtime = $qtime->fetch_assoc();
								$curdatetime = $rtime['curdatetime'];
								$rand = microtime($curdatetime);
								$stamper = str_replace('.', '', $rand);
								
								$finalstamper =  substr($stamper, -7, 7);
								$transCode =   sprintf("%03d", $piv)  . sprintf("%07d", $finalstamper) . sprintf("%04d", $sessionAccountID);
								$qcheck = $mysqli->query("SELECT `id` FROM `tblnewbalance` WHERE transCode = '".$transCode."' ");
								if($qcheck->num_rows > 0){
									$result =5; //system generation code error. please refresh the page.		
								}else{
									//transID = 1 for DEPOST, 2 for WITHDRAW
									$mysqli->begin_transaction();
									try {
									   $mysqli->query("INSERT INTO `tblnewbalance`(`id`, `eventID`, `accountID`, `transCode`, `transID`, `transAmount`, `isProcess`, `transDate`) 
																VALUES ('', '".$eventID."', '".$accountID."', '".$transCode."', '2', '".$transAmount."', '0', NOW() ) ");
										if(!true) {
											throw new Exception("Something went wrong");
											$result = 0;
										}else{
											$logAction = "Request for Withdrawal of points amounting to ". number_format($transAmount,2). "; Generated Transaction Code: ".$transCode;
											$mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '2', '".$accountID."', '', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
											
										}
										$mysqli->commit();
										//$result = $barcode; use this for barcode
										$result = '<span style="font-weight:bold;">Transaction Code:</span><br/><span style="font-size:40px; font-weight:bold; color:blue;">' . $transCode .'</span><br/>';
									}
									catch (Exception $e) {
									   $mysqli->rollback();
									   $result = 0;
									}
								}
							}
						}
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
		echo $result;
	}

}
?>