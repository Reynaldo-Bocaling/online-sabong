<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_REQUEST['winnerID'])){
		$result = 0;
		$winnerID = $_REQUEST['winnerID'];
		$qwinnertext = $mysqli->query("SELECT `winner` FROM `tblwinner` WHERE id = '".$winnerID."' ");
		$rwinnertext = $qwinnertext->fetch_assoc();
		$winnerText = $rwinnertext['winner'];
		$queryPercent = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
		$rowPercent = $queryPercent->fetch_assoc();	
		$percentToLess = $rowPercent['percentToLess'];
		$qf = $mysqli->query("SELECT `eventID`, `fightCode`, `fightNumber` FROM `tblfights` ORDER BY id DESC LIMIT 1");
		if($qf->num_rows > 0){
			while($rf = $qf->fetch_assoc()){
				$eventID = $rf['eventID'];
				$fightCode = $rf['fightCode'];
				$fightNumber = $rf['fightNumber'];
			}
			//bet details
			$meronTotalBetAmount = 0;
			$walaTotalBetAmount = 0;
			$totalBetAmount = 0;
			$grandTotalBetAmount = 0;
			$threePercent = 0;
			$totalAmountLessThreePercent = 0;
			$totalAmountIfMeronWins = 0;
			$totalAmountIfWalaWins = 0;
			$pesoEquivalentIfMeronWins = 0;
			$pesoEquivalentIfWalaWins = 0;
			$payoutMeron = 0;
			$payoutWala = 0;
			$totalIncome = 0;
			$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE a.fightCode = '".$fightCode."' AND a.isClaim = '0' AND a.`isCancelled` = '0' GROUP BY a.betType");

			if($qbets->num_rows > 0){
				while($rbets = $qbets->fetch_assoc()){
					$betType = $rbets['betType'];
					if($betType == 1){
						$totalBetAmount += $rbets['bets'];
						$meronTotalBetAmount = $rbets['bets'];
					}else{
						$totalBetAmount += $rbets['bets'];
						$walaTotalBetAmount = $rbets['bets'];
					}
				}
				if($meronTotalBetAmount > 0 && $walaTotalBetAmount > 0){
					$threePercent = ($totalBetAmount * $percentToLess);
					$totalAmountLessThreePercent = ($totalBetAmount - $threePercent);
					$totalAmountIfMeronWins = ($totalAmountLessThreePercent - $meronTotalBetAmount);
					$pesoEquivalentIfMeronWins = ($totalAmountIfMeronWins / $meronTotalBetAmount);
					$payoutMeron = (($pesoEquivalentIfMeronWins * 100 ) + 100);
										
					$totalAmountIfWalaWins = ($totalAmountLessThreePercent - $walaTotalBetAmount);
					$pesoEquivalentIfWalaWins = ($totalAmountIfWalaWins / $walaTotalBetAmount);
					$payoutWala = (($pesoEquivalentIfWalaWins *100 ) +100);
					
					//isBetting 1= OPEN, 2 = CLOSED, 3 = FOR PAYOUT, 4 == LAST CALL, 5== CANCELLED, 6 == PAYOUT SENT
					//isWinner 1 = MERON, 2 = WALA, 3 FOR DRAW		
					if($winnerID == 3){
						$q = $mysqli->query("SELECT `id`, `betCode`, `betAmount`, `accountID` FROM `tblbetliststemp` WHERE fightCode = '".$fightCode."' AND betRoleID = '3' AND isCancelled = '0' ORDER BY id DESC");
						if($q->num_rows > 0){					
							while($r = $q->fetch_assoc()){
								$betListID = $r['id'];
								$accountID = $r['accountID'];
								$betAmount = $r['betAmount'];
								$transCode = $r['betCode'];
								
								$query = $mysqli->query("SELECT `balance` FROM `tblaccounts` WHERE id = '".$accountID."' ");
								if($query->num_rows > 0){
									while($row = $query->fetch_assoc()){
										$currentBalance = $row['balance'];
										 $newBalance = ($currentBalance + $betAmount);
									}
								}
								$update = $mysqli->query("UPDATE `tblbetliststemp` a
														INNER JOIN `tblaccounts` b ON a.accountID = b.id 
														SET a.isReturned = '1',
														b.`balance` = (b.`balance` + $betAmount)
														WHERE a.accountID = '".$accountID."'");
								$logAction =  $fightCode . ": BET Amount " . number_format($betAmount,2) . " refunded due to the fight result is DRAW; Account Balance from ".number_format($currentBalance,2)." to " . number_format($newBalance,2). "; Transaction Code: ". $transCode;
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '4', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							}
							
							$update = $mysqli->query(" UPDATE `tblfights` SET `isBetting` = '6', `isWinner` = '".$winnerID."' ORDER BY id DESC LIMIT 1 ");	
							
							if($update){
								$insertTransfer = $mysqli->query("INSERT INTO `tblbetlists` (`fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`, `isCancelled`) SELECT  `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`, `isCancelled` FROM `tblbetliststemp` WHERE fightCode = '".$fightCode."' ");
								if($insertTransfer){
									$delete = $mysqli->query("TRUNCATE TABLE `tblbetliststemp`;");
									$logAction = $fightCode . ": Betting fight result:  " .$winnerText;
									
									$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
									$result = 1;
								}else{
									$result = 0;
								}
								
							}else{
								$result = 0;
							}
						}else{
							$update = $mysqli->query(" UPDATE `tblfights` SET `isBetting` = '6', `isWinner` = '".$winnerID."' WHERE fightCode = '".$fightCode."' ");	
							if($update){
								$insertTransfer = $mysqli->query("INSERT INTO `tblbetlists` (`fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`, `isCancelled`) SELECT  `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`, `isCancelled` FROM `tblbetliststemp` WHERE fightCode = '".$fightCode."' ");
								if($insertTransfer){
									$delete = $mysqli->query("TRUNCATE TABLE `tblbetliststemp`;");
									$logAction = $fightCode . ": Betting fight result: " .$winnerText;
									$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
									$result = 2;
								}else{
									$result= 0;
								}
							}else{
								$result = 0;
							}
						}
						
					}else{
						$update = $mysqli->query(" UPDATE `tblfights` SET `isBetting` = '3', `isWinner` = '".$winnerID."', `payoutMeron` = '".$payoutMeron."', `payoutWala` = '".$payoutWala."' WHERE fightCode = '".$fightCode."' ");				
						if($update){
							$insertTransfer = $mysqli->query("INSERT INTO `tblbetlists` (`fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`, `isCancelled`) SELECT  `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`, `isCancelled` FROM `tblbetliststemp` WHERE fightCode = '".$fightCode."' ");
							if($insertTransfer){
								$delete = $mysqli->query("TRUNCATE TABLE `tblbetliststemp`;");
								$logAction = $fightCode . ": Betting fight result: " .$winnerText;	
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
								$result = 1;
							}else{
								$result = 0;
							}
						}else{
							$result = 0;
						}
					}
					$grandTotalBetAmount = ($meronTotalBetAmount + $walaTotalBetAmount);
					if($winnerText == "DRAW"){
						$totalIncome = 0;
						$meronTotalBetAmount = 0;
						$walaTotalBetAmount = 0;
						$totalBetAmount = 0;
						$grandTotalBetAmount = 0;
					}else{
						$totalIncome = ($grandTotalBetAmount * $percentToLess);
					}
					//start
					$insertReport = $mysqli->query("INSERT INTO `tblfightsreport` (`id`, `eventID`, `fightCode`, `fightNumber`, `fightWinner`, `betMeron`, `betWala`, `totalBets`, `fightIncome`) VALUES (0, '".$eventID."', '".$fightCode."', '".$fightNumber."',  '".$winnerText."', '".$meronTotalBetAmount."', '".$walaTotalBetAmount."', '".$grandTotalBetAmount."', '".$totalIncome."') ");
					//end
				}else{
					$result = 0;
				}
			}else{
				$update = $mysqli->query("UPDATE `tblfights` SET `isBetting` = '3', `isWinner` = '".$winnerID."' WHERE fightCode = '".$fightCode."'");				
				if($update){
					$logAction = $fightCode . ": Betting fight result: " .$winnerText;				
					$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					$result = 1;
				}else{
					$result = 0;
				}

			}
		}else{
			$result = 0;
			
		}

		echo $result;
	}

}
?>