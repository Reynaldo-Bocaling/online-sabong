<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_barcode'])){
		$result = 0;
		$error = 0;
		$userID = $_SESSION['companyID'];
		
		$saveBetAmount = $_POST['txtBetAmountBarcode'];
		$saveBetType = $_POST['hiddenBetTypeBarcode'];

		if($saveBetType == "MERON"){
			$betTypeID = 1;
		}else{
			$betTypeID = 2;
		}
		
		$qe = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1; ");
		if($qe->num_rows > 0){
			$re =  $qe->fetch_assoc();
			$eventID = $re['id'];
			$curdate = $re['eventDate'];
			if($re['eventStatus'] == 0){
				
				$qfight = $mysqli->query("SELECT `id`, `fightNumber`, `fightCode`, `isBetting` FROM `tblfights` ORDER BY id DESC LIMIT 1 ");
				$rfight = $qfight->fetch_assoc();
				$fightCode = $rfight['fightCode'];
				$isBettingStatus = $rfight['isBetting'];
				$saveBetFightNumber = $rfight['fightNumber'];
				$saveBetFightID = $rfight['id'];
				if($isBettingStatus == 1 OR $isBettingStatus == 4){
					
					$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
					$rtime = $qtime->fetch_assoc();
					$curdatetime = $rtime['curdatetime'];
					$rand = microtime($curdatetime);
					$stamper = str_replace('.', '', $rand);
					
					$finalstamper =  substr($stamper, -7, 7);
					
					if($saveBetFightNumber < 100){
						$barFightNumber = 800 + $saveBetFightNumber;
					}else{
						$barFightNumber = $saveBetFightNumber;
					}
					$barcodeFinal = sprintf("%03d", $barFightNumber) . sprintf("%04d", $userID) . sprintf("%07d", $finalstamper) ;
					$barcodeLen = strlen($barcodeFinal);			
					if($barcodeLen == 14){
						$barcode = substr($barcodeFinal, -14,14);
					}else{
						$barcode = substr($barcodeFinal, -14,14);
						//$barcode = str_pad($barcodeFinal, 14, "8", STR_PAD_LEFT);
					}
					$result = 1;
					$insertBet = $mysqli->query("INSERT INTO `tblbetliststemp`(`id`, `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`) VALUES (0, '".$fightCode."', '".$barcode."', '".$betTypeID."', '".$saveBetAmount."', '".$saveBetFightID."', '0', '".$userID."' )");
					if($insertBet){
						$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES (0, '".$eventID."', '".$userID."', '2', '".$barcode."', '".$saveBetAmount."', NOW() ) ");
						//transactionID 8  is for BET
						$logAction = $fightCode.": Ticket BET under ". $saveBetType. " amounting to " . number_format($saveBetAmount,2) . "; Generated Transaction Code: ". $barcode;
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '3', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						 echo '
						<!DOCTYPE html>
						<html lang="en">
							<head>
								<meta charset="UTF-8">
								<meta name="viewport" content="width=device-width, initial-scale=1.0">
								<meta http-equiv="X-UA-Compatible" content="ie=edge">
								<link rel="stylesheet" href="style.css">
								<title>PRINT BET</title>
								<style type="text/css">
									html, body{
										width: 2in;
										height: 1in;
										font-size: 10px;
										font-family: Arial, Helvetica, sans-serif; 
									}
									@media print{
										@page {
											size: 2in 1in;
											size: portrait;
										}
									}
								</style>
								<script type="text/javascript" src="jquery.min.js"></script>
								<script type="text/javascript" src="qrcode.js"></script>								
							</head>
							<body onload = "window.print()" style="padding:3px;">
								<div class="ticket">
									<input type = "hidden" value = "'.$barcode.'" id = "hiddennewqrcode" />
									<center><div id="qrcode" style="width:100px; height:100px; text-align:center;"></div></center>
									<p class="centered" style="margin-top:-10px;">
										<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>									
										<span style="font-weight:bold; font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span><br/>
										<span style="font-weight:bold; font-size:10px;">'.$_SESSION['systemName'].'</span><br/>
										<span style="font-weight:bold; font-size:10px;">Cashier: '.$_SESSION['username'].'</span>
										<center><span style="font-weight:bold; font-size:10px;">BET</span></center>
										<br/>
										<center>Fight #: <span style="font-weight:bold; font-size:16px;">'. $saveBetFightNumber.'</span></center>
										<center>SIDE: <span style="font-weight:bold; font-size:16px;">'.$saveBetType.'</span></center>
										<center>AMOUNT: <span style="font-weight:bold; font-size:16px;">'. number_format($saveBetAmount).'</span></center>
										<br/>
									</p>		
								</div>
								<script type="text/javascript">
									var qrcode = new QRCode(document.getElementById("qrcode"), {
										width : 100,
										height : 100
									});
									function makeCode () {		
										var elText = document.getElementById("hiddennewqrcode");
										qrcode.makeCode(elText.value);
									}
										makeCode();
								</script>
							</body>
						</html>';
					}else{
						$error++;
						$result = 0; //error on insert
					}
				}else{
					$error++;
					$result =2; //fight already closed for betting
				}
			}else{
				$error++;
				$result = 7; // event already closed
			}
		}else{
			$error++;
			$result = 8; // no event
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
						<!-- Bootstrap CSS-->
							<link rel="stylesheet" type="text/css" href="../plugins/bootstrap/dist/css/bootstrap.min.css">
						<!-- Fonts-->
							<link rel="stylesheet" type="text/css" href="../plugins/themify-icons/themify-icons.css">
						<!-- Primary Style-->
						
							<link rel="stylesheet" type="text/css" href="../build/css/second-layout.css">
							
						</style>
						</head>
						<body>';
						
						if($result == 2){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">ERROR! Unable to placed a BET. Betting is already CLOSED! Please refresh the page.</h1>
								</div>
							</div>';
						}else if($result == 7){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">ERROR! Event is already closed!. </h1>
								</div>
							</div>';
						}else if($result == 8){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">ERROR! No event for today. </h1>
								</div>
							</div>';
						}else{
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">ERROR! Unable to placed a BET! Please refresh the page.</h1>
								</div>
							</div>';
						}
					echo'
						</body>
					</html>';
		}
	}
}
?>
		
		
		
		
		
		
		
        