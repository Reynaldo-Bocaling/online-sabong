<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_cashoutteller'])){
		$result = 0;
		$error = 0;
		$cashoutAmount = $_POST['txtfrmCashouttelleramount'];
		$cashHandlerID = $_POST['txtfrmCashouthandlerID'];
		$cashoutPassword = $_POST['txtfrmCashouttellerpassword'];		
		$userID = $_SESSION['companyID'];
		
		$qcashpass = $mysqli->query("SELECT `id`, `username` FROM `tblusers` WHERE id = '".$cashHandlerID."' AND  password = '".md5($cashoutPassword)."' ");
		
		if($qcashpass->num_rows > 0){
			$rowqcash = $qcashpass->fetch_assoc();
			$cashoutHandlerUsername = $rowqcash['username'];

			$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime, YEAR(CURDATE()) as yearNow;");
			$rtime = $qtime->fetch_assoc();
			$curdatetime = $rtime['curdatetime'];
			$yearNow = $rtime['yearNow'];
			$rand = microtime($curdatetime);
			$stamper = str_replace('.', '', $rand);	
			$finalstamper =  substr($stamper, -9, 9);
		
			$barcode = "2".$yearNow.$finalstamper;
			$qevent = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1");
			
			if($qevent->num_rows > 0){
				$revent = $qevent->fetch_assoc();
				$eventStatus = $revent['eventStatus'];
				$eventID = $revent['id'];
				
				if($eventStatus == 0){
					
					$query = $mysqli->query("SELECT a.`transactionID`, b.`transaction`, SUM(a.`amount`) as totalAmount,  a.`transactionCode`, ev.`eventDate` FROM `tblusertransactions` a
							LEFT JOIN `tblusertransactionsstatus` b ON a.transactionID = b.id 
							LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
							WHERE ev.id = (SELECT max(id) FROM `tblevents`) AND a.statusID = '0' AND userID = '".$userID."' GROUP BY a.transactionID ORDER BY a.transactionID ");
					$cashin = 0;			//done
					$bets = 0;				//done
					$cancelled = 0;			//done refer from tblbetlist where betting = 5
					$totalPayoutPaid = 0;		//done
					$totalCancelledPaid = 0;		//done
					$totalDrawPaid = 0;
					
					$totalCancelledDrawPaid = 0;
					$drawUnclaimed = 0;
					$mobileDeposit = 0;
					$mobileWithdraw = 0;
					$cashout = 0;
					$betCancelled = 0;
					$totalMoneyonhand =  0;
					if($query->num_rows > 0){
						while($row = $query->fetch_assoc()){
							$transactionID = $row['transactionID'];
							if($transactionID == 1){ //1 cash in
								$cashin = $row['totalAmount'];
							}
							
							if($transactionID == 2){ // 2 bets
								$bets = $row['totalAmount'];
							}
							
							if($transactionID == 3){ // 3 payout
								$totalPayoutPaid = $row['totalAmount'];
							}
							
							if($transactionID == 4){ // 4 refund cancelled
								$totalCancelledPaid = $row['totalAmount'];
							}
							
							if($transactionID == 5){ // refund draw
								$totalDrawPaid = $row['totalAmount'];
							}
							
							if($transactionID == 6){ // mobile deposit
								$mobileDeposit = $row['totalAmount'];
							}
							
							if($transactionID == 7){ // mobile withdraw
								$mobileWithdraw = $row['totalAmount'];
							}
							if($transactionID == 8){ //cash out
								$cashout = $row['totalAmount'];
							}
							if($transactionID == 9){ //bet cancelled
								$betCancelled = $row['totalAmount'];
							}
						}
						$totalMoneyonhand = (($cashin + $bets + $mobileDeposit ) - ($totalPayoutPaid + $totalCancelledPaid + $totalDrawPaid + $mobileWithdraw + $cashout + $betCancelled)) ;
						
						
					}
					
						
					if($cashoutAmount > $totalMoneyonhand){
						$error++;
						$result = 13;
					}else{
						//8 is cash out in tblusertransactionstatus
						$insert = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`, `cashHandlerID`) VALUES (0, '".$eventID."', '".$userID."', '8', '".$barcode."', '".$cashoutAmount."', NOW(), '".$cashHandlerID."')");
						if($insert){		
							$logAction = "Cash OUT amount " . number_format($cashoutAmount,2) ."; Cash Handler: ". $cashoutHandlerUsername;
							//10 is cash out from tbltransaction
							$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '10', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
								echo '
								<!DOCTYPE html>
								<html lang="en">
									<head>
										<meta charset="UTF-8">
										<meta name="viewport" content="width=device-width, initial-scale=1.0">
										<meta http-equiv="X-UA-Compatible" content="ie=edge">
										<link rel="stylesheet" href="style.css">
										<title>PRINT CASH OUT</title>
										<style type="text/css">
											html, body{
												width: 2in;
												height: 1in;
												font-size: 8px;
												font-family: Arial, Helvetica, sans-serif; 
											
											 
											@media print{ 
												@page {
													size: 2in 1in;
													size: portrait;
												}
											}
										</style>		
									</head>
									<body onload = "window.print()" style="padding:3px;">
										<div class="ticket">
											<p class="centered" style="margin-top:-10px;">									
												<span style="font-size:7px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span><br/>
												<span style="font-size:7px;">'.$_SESSION['systemName'].'</span><br/>
												<span style="font-size:7px;">Cashier: '.$_SESSION['username'].'</span>
												<center><span style="font-weight:bold; font-size:10px;">CASH OUT</span></center>
												<br/>
												<center>AMOUNT: <span style="font-weight:bold; font-size:10px;">'. number_format($cashoutAmount).'</span></center>
												<center>CASH HANDLER: <span style="font-weight:bold; font-size:10px;">'. $cashoutHandlerUsername.'</span></center><br/>
											</p>		
										</div>
									</body>
								</html>';
						}else{
							$error++;
							$result = 15;
						}
					}
				}else{
					$error++;
					$result = 7;
				}
				
			}else{
				$error++;
				$result = 8;
			}		
		}else{
			$error++;
			$result = 14;
			
		}
		
		if($error > 0){
			echo '
				<form method="POST" action="../print/printError.php" id="frmError">
					<div class="row">
						<div class="col-md-12">
							<input id="txtError" name="txtError" type="hidden" value = "'.$result.'" />
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type = "submit"  id = "sbmtError" value = "ERROR" class="btn btn-success btn-lg" style="display:none; font-size:30px; font-weight:bold; width:100%; height:100%;" />
						</div>									
					</div>
					<div class="row" style="margin-top:5px;">
						<div class="col-md-12" style="text-align:center;">
							<input type = "button"  id = "btnError" value = "ERROR" class="btn btn-success" style=" font-size:30px; font-weight:bold; width:100%; height:100%;" />
						</div>
					</div>
				</form>
				<script src="../design/vendor/jquery/jquery.min.js"></script>
				<script src="../design/js/sb-admin-2.min.js"></script>
				<script>
					$("#frmError").submit();
				</script>';	
		}else{
			
		}
	}
}
?>
		
		
		
		
		
		
		
        