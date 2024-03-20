<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['cancelBets'])){
		$result = 0;
		$userPassword = mysqli_real_escape_string($mysqli, $_POST['userPassword']);

		$quser = $mysqli->query("SELECT * FROM `tblusers` WHERE id = '".$_SESSION['companyID']."' LIMIT 1");
		if($quser->num_rows > 0){
			$ruser = $quser->fetch_assoc();
			
			if($ruser['password'] == md5($userPassword)){	
				$qf = $mysqli->query("SELECT `eventID`, `fightCode`, `fightNumber` FROM `tblfights`  ORDER BY id DESC LIMIT 1");
				if($qf->num_rows > 0){
					while($rf = $qf->fetch_assoc()){
						$eventID = $rf['eventID'];
						$fightCode = $rf['fightCode'];
						$fightNumber = $rf['fightNumber'];
					}
					$q = $mysqli->query("SELECT a.`id`, a.`betCode`, a.`betAmount`, a.`accountID`, b.`fightCode` FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE a.fightCode = '".$fightCode."' AND a.betRoleID = '3' ORDER BY a.id DESC");
					if($q->num_rows > 0){
						while($r = $q->fetch_assoc()){
							$betListID = $r['id'];
							$accountID = $r['accountID'];
							$betCode = $r['betCode'];
							$betAmount = $r['betAmount'];
							$dbfightCode = $r['fightCode'];
							
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
													
							if($update){
								$logAction = $dbfightCode .": BET amounting to " . number_format($betAmount,2) . " has been refunded due to cancelled fight; Account Balance from ".number_format($currentBalance,2)." to " . number_format($newBalance,2). " using Transaction Code: " . $betCode;
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '4', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							}else{
								$result = 0;
							}
							
						}
						$u = $mysqli->query("UPDATE `tblfights` SET isBetting = '5' ORDER BY id DESC LIMIT 1 ");
						if($u){
							$insertTransfer = $mysqli->query("INSERT INTO `tblbetlists` (`fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`) SELECT `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned` FROM `tblbetliststemp` WHERE fightCode = '".$fightCode."' ");
							if($insertTransfer){
								$delete = $mysqli->query("DELETE FROM `tblbetliststemp`");
								$logAction = $fightCode.": Cancelled fight bettings";
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
								$result = 1;
								$insertReport = $mysqli->query("INSERT INTO `tblfightsreport` (`id`, `eventID`, `fightCode`, `fightNumber`, `fightWinner`, `betMeron`, `betWala`, `totalBets`, `fightIncome`) VALUES ('', '".$eventID."', '".$fightCode."', '".$fightNumber."',  'CANCELLED', '0', '0', '0', '0') ");
							}else{
								$result = 0;
							}
						}else{
							$result = 0;
						}
					}else{
						$u = $mysqli->query("UPDATE `tblfights` SET isBetting = '5' ORDER BY id DESC LIMIT 1 ");
						if($u){
							$insertTransfer = $mysqli->query("INSERT INTO `tblbetlists` (`fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned`) SELECT `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`, `betRoleID`, `accountID`, `isReturned` FROM `tblbetliststemp` WHERE fightCode = '".$fightCode."' ");
							if($insertTransfer){
								$delete = $mysqli->query("DELETE FROM `tblbetliststemp`");
								$logAction = $fightCode.": Cancelled fight bettings";
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
								$result = 2;
								
								$insertReport = $mysqli->query("INSERT INTO `tblfightsreport` (`id`, `eventID`, `fightCode`, `fightNumber`, `fightWinner`, `betMeron`, `betWala`, `totalBets`, `fightIncome`) VALUES ('', '".$eventID."', '".$fightCode."', '".$fightNumber."',  'CANCELLED', '0', '0', '0', '0') ");
								
							}else{
								$result = 0;
							}
						}else{
							$result = 0;
						}
					}
				}
			}else{
				$result = 3; // password mismatch
			}
		}
		echo $result;
	}

}
?>