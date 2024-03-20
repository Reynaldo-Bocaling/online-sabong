<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_cashout'])){
		$result = 0;
		$error = 0;
		$cashoutAmount = $_POST['txtcashoutamount'];
		$cashoutto = $_POST['hiddentellercashoutID'];
		$userID = $_SESSION['companyID'];
		
		$qteller = $mysqli->query("SELECT `username` FROM `tblusers` WHERE id = '".$cashoutto."' ");
		$rteller = $qteller->fetch_assoc();
		$cashouttoUsername = $rteller['username'];
		$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
		$rtime = $qtime->fetch_assoc();
		$curdatetime = $rtime['curdatetime'];
		$nums = "123456789987654321123456789987654321";
		$piv = substr(str_shuffle($nums), 0, 12);
		$barcode = $piv;	
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
				$tcashin = 0;			//done
				$tcashout = 0;
				$betCancelled = 0;
				$tbets = 0;				//done
				$ttotalMoneyonhand =  0;
				if($query->num_rows > 0){
					while($row = $query->fetch_assoc()){
						$transactionID = $row['transactionID'];
						if($transactionID == 1){ //1 cash in
							$tcashin = $row['totalAmount'];
						}
						
						if($transactionID == 2){ // 2 bets
							$tbets = $row['totalAmount'];
						}
						if($transactionID == 8){ //1 cash out
							$tcashout = $row['totalAmount'];
						}
						if($transactionID == 9){ //1 cash out
							$tbetCancelled = $row['totalAmount'];
						}
					}
					$ttotalMoneyonhand = (($tcashin + $tbets) -  ($tcashout + $tbetCancelled)) ;
					
					
				}
				
				if($cashoutAmount < $ttotalMoneyonhand){
				
					$insert = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`, `cashoutID`) VALUES ('', '".$eventID."', '".$userID."', '8', '".$barcode."', '".$cashoutAmount."', NOW(), '".$cashoutto."')");
					// 8 means cash out
					if($insert){		
						$logAction = "Cash OUT: amounting to " . number_format($cashoutAmount,2);
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '10', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						
						$numsin = "23456789009876543211234567890098765432";
						$pivin = substr(str_shuffle($numsin), 0, 8);
						$barcodein = "2021".$pivin;	
						$insertin = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES ('', '".$eventID."', '".$cashoutto."', '1', '".$barcodein."', '".$cashoutAmount."', NOW())");
						
						if($insertin){		
							$logActionin = "Cash IN: amounting to " . number_format($cashoutAmount,2);
							$logsin = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '9', '', '".$cashoutto."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					
						
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
											<span style="font-weight:bold; font-size:7px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span><br/>
											<span style="font-weight:bold; font-size:7px;">'.$_SESSION['systemName'].'</span><br/>
											<span style="font-weight:bold; font-size:7px;">Cashier: '.$_SESSION['username'].'</span>
											<center><span style="font-weight:bold; font-size:9px;">BET COLLECTOR</span></center>
											<center><span style="font-weight:bold; font-size:9px;">CASH OUT</span></center>
											<br/>
											<center><span style="font-weight:bold; font-size:9px;">CASH IN TO '.$cashouttoUsername.'</span></center>
											<br/>
											<center>AMOUNT: <span style="font-weight:bold; font-size:9px;">'. number_format($cashoutAmount,2).'</span></center><br/>
											
											
											<center><span style="font-weight:bold; font-size:7px;">'.$_SESSION['systemName'].' ('.$_SESSION['systemLocation'].')</apan></center>
										</p>		
									</div>
								</body>
							</html>';
						}
					}else{
						$error++;
						$result = 2;
					}
				}else{
					$error++;
					$result = 10; //cash out amount is greater than money on hand	
				}
			}else{
				$error++;
				$result = 2;
			}
			
		}else{
			$error++;
			$result = 2;
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
						if($result == 2){
							echo '
							<div class = "row">
								<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center;">Error! Unable to CASH OUT! Please contact system developer for assistance.</h1>
								</div>
							</div>';
						}else if($result == 10){
							echo '
							<div class = "row">
								<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center;">Error! Cash out amount is greater than the teller\'s money on hand. Please contact system developer for assistance.</h1>
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
		
		
		
		
		
		
		
        