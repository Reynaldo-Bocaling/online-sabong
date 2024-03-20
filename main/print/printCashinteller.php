<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_cashinteller'])){
		$result = 0;
		$error = 0;
		$cashinmount = $_POST['txtfrmCashintelleramount'];
		$cashHandlerID = $_POST['txtfrmCashinhandlerID'];
		$cashinPassword = $_POST['txtfrmCashintellerpassword'];		
		$userID = $_SESSION['companyID'];
		
		$qcashpass = $mysqli->query("SELECT `id`, `username` FROM `tblusers` WHERE id = '".$cashHandlerID."' AND  password = '".md5($cashinPassword)."' ");
		
		if($qcashpass->num_rows > 0){
			$rowqcash = $qcashpass->fetch_assoc();
			$cashinHandlerUsername = $rowqcash['username'];
			
			$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime, YEAR(CURDATE()) as yearNow;");
			$rtime = $qtime->fetch_assoc();
			$curdatetime = $rtime['curdatetime'];
			$yearNow = $rtime['yearNow'];
			$rand = microtime($curdatetime);
			$stamper = str_replace('.', '', $rand);	
			$finalstamper =  substr($stamper, -9, 9);			
			$barcode = "1".$yearNow.$finalstamper;
			
			$qevent = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1");
			
			if($qevent->num_rows > 0){
				$revent = $qevent->fetch_assoc();
				$eventStatus = $revent['eventStatus'];
				$eventID = $revent['id'];
				
				if($eventStatus == 0){
					$insert = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`, `cashHandlerID`) VALUES (0, '".$eventID."', '".$userID."', '1', '".$barcode."', '".$cashinmount."', NOW(), '".$cashHandlerID."')");
					if($insert){		
						$logAction = "Cash IN: amounting to " . number_format($cashinmount,2);
						$logAction = "Cash IN amount " . number_format($cashinmount,2) ."; Cash Handler: ". $cashinHandlerUsername;
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '9', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							echo '
							<!DOCTYPE html>
							<html lang="en">
								<head>
									<meta charset="UTF-8">
									<meta name="viewport" content="width=device-width, initial-scale=1.0">
									<meta http-equiv="X-UA-Compatible" content="ie=edge">
									<link rel="stylesheet" href="style.css">
									<title>PRINT CASH IN</title>
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
											<center><span style="font-weight:bold; font-size:10px;">CASH IN</span></center>
											<br/>
											<center>AMOUNT: <span style="font-weight:bold; font-size:10px;">'. number_format($cashinmount).'</span></center>
											<center>CASH HANDLER: <span style="font-weight:bold;  font-size:10px;">'. $cashinHandlerUsername.'</span></center><br/>
										</p>		
									</div>
								</body>
							</html>';
					}else{
						$result = 2;
						$error++;
					}
				}else{
					$result = 3;
					$error++;
				}
				
			}else{
				$result = 4;
				$error++;
			}		
			
		}else{
			$result = 5;
			$error++;
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
							<link rel="stylesheet" type="text/css" href="../build/css/second-layout.css">
						</head>
						<body>';
							if($result == 5){
								echo '
								<div class = "row">
									<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">Cash handler Password is incorrect! Please contact system developer for assistance.</h1>
									</div>
								</div>';
					
							}else if($result == 4){
								echo '
								<div class = "row">
									<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">No Active Event! Please contact system developer for assistance.</h1>
									</div>
								</div>';
							}else if($result == 3){
								echo '
								<div class = "row">
									<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">No Event! Please contact system developer for assistance.</h1>
									</div>
								</div>';
							}else if($result == 2){
								echo '
								<div class = "row">
									<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">Unable to CASH IN! Please contact system developer for assistance.</h1>
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
		
		
		
		
		
		
		
        