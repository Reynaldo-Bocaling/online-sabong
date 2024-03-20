<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_REQUEST['releasePayout'])){
		$result = 0;
				$qf = $mysqli->query("SELECT `id`, `fightCode`, `isBetting` FROM `tblfights` ORDER BY id DESC LIMIT 1");
				$rf = $qf->fetch_assoc();
					$dbfightID = $rf['id'];
					$dbFightCode = $rf['fightCode'];
					$dbisBetting = $rf['isBetting'];
				
				if($dbisBetting == 3){
					$qbets = $mysqli->query("SELECT a.`id`, a.`betCode`, a.`betAmount`, a.`isClaim`, a.`accountID`, f.`isWinner`, f.`payoutMeron`, f.`payoutWala`, b.`balance` FROM `tblbetlists` a 
					LEFT JOIN `tblfights` f ON a.fightCode = f.fightCode 
					LEFT JOIN `tblaccounts` b ON a.accountID = b.id 
					WHERE a.fightCode = '".$dbFightCode."' AND a.betType = (SELECT `isWinner` FROM `tblfights` ORDER BY id DESC LIMIT 1) AND 
					a.betRoleID = '3' AND a.isClaim = '0' AND a.isReturned = '0' AND isCancelled = '0' ORDER BY a.id ASC");
					if($qbets->num_rows > 0){
						 $count = 1;
						 $addedBalance = 0;
						while($rbets = $qbets->fetch_assoc()){
							$newBalance = 0;	
							$accountID = $rbets['accountID'];
							$betID = $rbets['id'];
							
							$winnerID = $rbets['isWinner'];
							$betCode = $rbets['betCode'];
							$betAmount = $rbets['betAmount'];
							if($winnerID == 1){
								$payoutMultiplier = $rbets['payoutMeron'];
							}else if($winnerID == 2){
								$payoutMultiplier = $rbets['payoutWala'];
							}else{
								$payoutMultiplier = 0;
							}
							
							if($winnerID == 1 || $winnerID == 2){
								
								$query = $mysqli->query("SELECT `balance` FROM `tblaccounts` WHERE id = '".$accountID."' ");
								if($query->num_rows > 0){
									while($row = $query->fetch_assoc()){
										$currentBalance = $row['balance'];
									}
									$winningAmount = (($betAmount / 100) * $payoutMultiplier);
									$update = $mysqli->query("UPDATE `tblbetlists` a INNER JOIN `tblaccounts` b ON a.accountID = b.id SET a.`isClaim` = '1', b.`balance` = (b.`balance` + $winningAmount)
														WHERE a.id = '".$betID."' AND a.accountID = '".$accountID."'");
														
									$newBalance = ($currentBalance + $winningAmount);
									if($update){
										$logAction = $dbFightCode.": Increasing the Winning, Winning Amount: " . number_format($winningAmount,2) . "; Account Balance from ".number_format($currentBalance,2)." to " . number_format($newBalance,2);
										$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");										
									}	
								}	
							}else{
							}
						}
						
						$u = $mysqli->query("UPDATE `tblfights` SET `isBetting` = '6' ORDER BY id DESC LIMIT 1 ");			
						if($u){
							$logAction =  $dbFightCode.": Released the payouts of the winners.";
							$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							$result = 1;
						}else{
							$result = 0;
						}
					}else{
						$u = $mysqli->query("UPDATE `tblfights` SET `isBetting` = '6' ORDER BY id DESC LIMIT 1 ");			
						if($u){
							$logAction = $dbFightCode.": Released the payouts of the winners.";
							$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							$result = 2;
						}else{
							$result = 0;
						}
					}
				}else{
					$result = 5;
				}
		echo $result;
	}

}
?>