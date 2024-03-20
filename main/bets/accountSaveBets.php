<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['accountID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['amount'])){
		$saveBetType = $_POST['bettingType'];
		$saveBetAmount = $_POST['amount'];
		$accountID = $_SESSION['accountID'];
		$result = 0;
		if($saveBetType == "MERON"){
			$betTypeID = 1;
		}else{
			$betTypeID = 2;
		}
							
		$qf = $mysqli->query("SELECT `id`, `fightCode`, `fightNumber` as fightNum, `isBetting`, `closeMeron`, `closeWala`  FROM `tblfights` ORDER BY id DESC LIMIT 1");
		if($qf->num_rows > 0){
			$rf = $qf->fetch_assoc();	
			$currentFightID = $rf['id'];
			$currentFightCode = $rf['fightCode'];
			$currentFightNumber = $rf['fightNum'];
			$isBetting = $rf['isBetting'];
			$closeMeron = $rf['closeMeron'];
			$closeWala = $rf['closeWala'];
			if($closeMeron == 1 AND $betTypeID == 1){
				$result = 11; //fight betting for Meron is close
			}else if($closeWala == 1 AND $betTypeID == 2){
				$result =12; //fight betting for Wala is close
			}else{
				$query = $mysqli->query("SELECT `balance` FROM `tblaccounts` WHERE id = '".$accountID."' ");
				if($query->num_rows > 0){
					while($row = $query->fetch_assoc()){
						$currentBalance = $row['balance'];
						 $newBalance = ($currentBalance - $saveBetAmount);
					}
					$check = $mysqli->query("SELECT a.id FROM `tblbetliststemp` a 
								WHERE a.fightCode = '".$currentFightCode."' AND a.accountID = '".$accountID."' AND a.betAmount ='".$saveBetAmount."' ");
					if($check->num_rows > 0){
						$result = 7;
					}else{
						if($saveBetAmount <= $currentBalance){
							if($isBetting  == 1 OR $isBetting == 4){					
								$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
								$rtime = $qtime->fetch_assoc();
								$curdatetime = $rtime['curdatetime'];
								$rand = microtime($curdatetime);
								$stamper = str_replace('.', '', $rand);
								
								$finalstamper =  substr($stamper, -7, 7);
								if($saveBetFightNumber < 100){
									$barFightNumber = 500 + $saveBetFightNumber;
								}else{
									$barFightNumber = $saveBetFightNumber;
								}
								
								$barcode =   sprintf("%03d", $barFightNumber) . sprintf("%07d", $finalstamper) . sprintf("%04d", $accountID);
								
								$insertBet = $mysqli->query("INSERT INTO `tblbetliststemp`(`id`, `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `betRoleID`, `accountID`) VALUES ('', '".$currentFightCode."', '".$barcode."', '".$betTypeID."', '".$saveBetAmount."', '".$currentFightID."', '0', '3', '".$accountID."' )");
								if($insertBet){	
									$update = $mysqli->query("UPDATE `tblaccounts` SET `balance` = (`balance` - '".$saveBetAmount."') WHERE id = '".$accountID."' ");
									if($update){
										$logAction = $currentFightCode .": BET under ". $saveBetType ." amounting ". number_format($saveBetAmount,2) . "; Account Balance from ".number_format($currentBalance,2)." to " . number_format($newBalance,2) ." Generated Transaction Code: ".$barcode;
										$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '3', '".$accountID."', '', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
										$result = 1;
									}else{
										$result = 0;
									}
								}else{
									$result = 0;
								}
							}else if($isBetting == 2){
								$result = 2;
							}else if($isBetting == 3){
								$result = 3;
							}else if($isBetting == 5){
								$result = 5;
							}
						}else{
							$result = 6;
						}
					}
					
				}
			}
		}else{
			$result = 0;
		}
		echo $result;
	}

}
?>