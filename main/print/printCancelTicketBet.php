 <?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['txtCancelTicketBetBarcode1'])){
		$result = 0;
		$error = 0;
		$userID = $_SESSION['companyID'];
		$barcode = $_POST['txtCancelTicketBetBarcode1'];
	
	
		$q = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`betCode`, a.`betType`, a.`betAmount`, a.`userID`, a.`isCancelled`, b.`eventID`, b.`fightNumber`, b.`isBetting`, u.`cname` FROM `tblbetliststemp` a 
		LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
		LEFT JOIN `tblusers` u ON a.userID = u.id
		WHERE a.betCode = '".$barcode."'  AND a.accountID = '0'");
		if($q->num_rows > 0){
			while($r = $q->fetch_assoc()){
				$betListID = $r['id'];
				$eventID = $r['eventID'];
				$userID = $r['userID'];
				$cashier = $r['cname'];
				$saveBetAmount = $r['betAmount'];
				$fightCode = $r['fightCode'];
				$saveBetFightNumber = $r['fightNumber'];
				$betType = $r['betType'];
				$isCancelled = $r['isCancelled'];
				$isBetting = $r['isBetting'];
				if($betType == 1){
					$saveBetType = "MERON";
				}else if($betType == 2){
					$saveBetType = "WALA";
				}else{
					$saveBetType = "DRAW";
				}
				
				if($isBetting == 1 || $isBetting == 4){
				
					if($isCancelled == 1){
						$error++;
						$result = 30;
					}else{
						$update = $mysqli->query("UPDATE `tblbetliststemp` SET `isCancelled` = '1', `isClaim` = '1', `isReturned` = '1'   WHERE betCode = '".$barcode."'");
						if($update){
							$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES ('', '".$eventID."', '".$userID."', '9', '".$barcode."', '".$saveBetAmount."', NOW() ) ");
							if($insertTrans){
								$logAction = $fightCode.": Ticket BET under ". $saveBetType. " amounting to " . number_format($saveBetAmount,2) . "; Generated Transaction Code: ". $barcode;
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '11', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
								$q = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
								$r = $q->fetch_assoc();
								$curdatetime = $r['curdatetime'];
								echo '
								<!DOCTYPE html>
								<html lang="en">
								<head>
									<meta charset="UTF-8">
									<meta name="viewport" content="width=device-width, initial-scale=1.0">
									<meta http-equiv="X-UA-Compatible" content="ie=edge">
									<link rel="stylesheet" href="style.css">
									<title>PRINT CANCEL TICKET BET</title>
									<style type="text/css">
										html, body{
											width: 2in;
											height: 1in;
											font-size: 8px;
											font-family: Arial, Helvetica, sans-serif; 
										}
										@media print{
											@page {
												size: 2in 1in;
												size: portrait;
											}
										}
									</style>	
								</head>
								<body oncontextmenu="return false;" onload = "window.print()" style="padding:3px;">
									<div class="ticket">
										<p class="centered" style="margin-top:-10px;">
											<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>		
											<center><span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
											<center><span style="font-size:10px;">'.$_SESSION['systemName'].'</span></center>
											<center><span style="font-size:10px;">Cashier: '.$cashier.'</span></center>
											<center><span style="font-weight:bold; font-size:10px;">CANCELLED TICKET BET</span></center>
											<br/>
											<center>Fight #: <span style="font-weight:bold; font-size:12px;">'. $saveBetFightNumber.'</span></center>
											<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$saveBetType.'</span></center>
											<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
										</p>		
									</div>
								</body>
								</html>';
							}else{				
								$update = $mysqli->query("UPDATE `tblbetliststemp` SET `isCancelled` = '0', `isClaim` = '0', `isReturned` = '0' WHERE betCode = '".$barcode."' AND id = '".$betListID."' ");
								$error++;
								$result = 0;
							}
						}else{
							$error++;
							$result = 31;
						}
					}
				}else{
					$error++;
					$result = 33;
				}
			}
		}else{
			$error++;
			$result = 34;
		}	
		if($error > 0){	
			echo '
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>'.$_SESSION['systemName'].'</title>
				<link rel="stylesheet" type="text/css" href="../plugins/bootstrap/dist/css/bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="../plugins/themify-icons/themify-icons.css">
				<link rel="stylesheet" type="text/css" href="../build/css/second-layout.css">	
				</style>
				</head>
				<body  style="padding:3px;">';		
					if($result == 30){
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Ticket Bet with barcode '.$barcode.' is already cancelled.</h1>
							</div>
						</div>';
					}else if($result == 31){
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to cancel the ticket bet with barcode '.$barcode.'</h1>
							</div>
						</div>';
					}else if($result == 33){
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Cancellation of ticket bet '.$barcode.' is only available if fight status is still open or last call</h1>
							</div>
						</div>';
					}else if($result == 34){
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! ticket bet '.$barcode.' is does not exist</h1>
							</div>
						</div>';
					}else{
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to cancel the ticket bet with barcode '.$barcode.'</h1>
							</div>
						</div>';
					}
				
				echo'
					</body>
			</html>';
		}else{
	
		}
	}

}
?>
