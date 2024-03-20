<?php
session_start();
require('../includes/connection.php');
if($_SESSION['roleID'] == 2){ // 2 = STAFF	
	if(isset($_REQUEST['txtBarCode'])){
		$result = 0;
		$error = 0;
		$barcode = $_REQUEST['txtBarCode1'];
		$userID = $_SESSION['companyID'];	
		$trimmed_str = strlen($barcode);
		$q = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
		$r = $q->fetch_assoc();
		$curdatetime = $r['curdatetime'];
		$qe = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1; ");
		if($qe->num_rows > 0){
			$re =  $qe->fetch_assoc();
			$eventID = $re['id'];
			$curdate = $re['eventDate'];
			if($re['eventStatus'] == 0){
				$query = $mysqli->query("SELECT a.`id`, a.`betType`, a.`betAmount`, a.`isReturned`, b.`fightCode`, b.`fightNumber`,  c.betType AS betTypeText FROM `tblbetliststemp` a 
								LEFT JOIN `tblfights` b ON a.fightCode= b.fightCode 
								LEFT JOIN `tblbettypes` c ON a.betType = c.id 
								WHERE a.`betCode` = '".$barcode."' AND a.isCancelled= '1' AND a.isReturned = '0' AND a.isClaim = '0'  "); // isBetting =5 means fight CANCELLED
						
				if($query->num_rows > 0){
					while($row = $query->fetch_assoc()){
						$betListID = $row['id'];
						$isReturned = $row['isReturned'];
						$saveBetAmount = $row['betAmount'];
						$fightCode = $row['fightCode'];		
						$betType = $row['betType'];
						$saveBetFightNumber = $row['fightNumber'];
						$betTypeText = $row['betTypeText'];
						
						if($isReturned == 0){
							$update = $mysqli->query("UPDATE `tblbetliststemp` SET `isReturned` = '1', isClaim = '1' WHERE id = '".$betListID."' ");
							if($update){
								$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES (0, '".$eventID."', '".$userID."', '4', '".$barcode."', '".$saveBetAmount."', NOW() ) ");
								//transactionID 4  is for CANCELLED FIGHT	
								$logAction = $fightCode .": Ticket BET amounting to " . number_format($saveBetAmount,2) . " has been refunded(cancelled) using Transaction Code: " . $barcode;
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '4', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
								$result = 1;
								$ppQuery = $mysqli->query("SELECT `systemPrint` FROM `tblsystem` ");
								if($ppQuery->num_rows > 0){
									$ppr = $ppQuery->fetch_assoc();
									$systemPrintVal = $ppr['systemPrint'];
									if($systemPrintVal == 1){
										echo '
										<!DOCTYPE html>
										<html lang="en">
											<head>
												<meta charset="UTF-8">
												<meta name="viewport" content="width=device-width, initial-scale=1.0">
												<meta http-equiv="X-UA-Compatible" content="ie=edge">
												<link rel="stylesheet" href="style.css">
												<title>PRINT REFUND</title>
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
											<body onload = "window.print()" style="padding:3px;">
												<div class="ticket">
													<p class="centered" style="margin-top:-10px;">
														<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>								
														<center><span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
														<center><span style="font-size:10px;">'.$_SESSION['systemName'].'</span></center>
														<center><span style="font-size:10px;">Cashier: '.$_SESSION['username'].'</span></center>
														<center><span style="font-size:10px;">REFUNDED</span></center>
														<br/>
														<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
														<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
														<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
													</p>	
												</div>
												
												<br/><hr/><br/>
												
												<div class="ticket">
													<p class="centered" style="margin-top:-10px;">
														<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>								
														<center><span style="font-size:7px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
														<center><span style="font-size:7px;">'.$_SESSION['systemName'].'</span></center>
														<center><span style="font-size:7px;">Cashier: '.$_SESSION['username'].'</span></center>
														<center><span style="font-size:10px;">REFUNDED</span></center>
														<br/>
														<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
														<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
														<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
>
													</p>	
												</div>
											</body>
										</html>';
									}else{
										echo '
										<!DOCTYPE html>
										<html lang="en">
											<head>
												<meta charset="UTF-8">
												<meta name="viewport" content="width=device-width, initial-scale=1.0">
												<meta http-equiv="X-UA-Compatible" content="ie=edge">
												<link rel="stylesheet" href="style.css">
												<title>PRINT REFUND</title>
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
											<body onload = "window.print()" style="padding:3px;">
												<div class="ticket">
													<p class="centered" style="margin-top:-10px;">
														<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>									
														<center><span style="font-size:7px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
														<center><span style="font-size:7px;">'.$_SESSION['systemName'].'</span></center>
														<center><span style="font-size:7px;">Cashier: '.$_SESSION['username'].'</span></center>
														<center><span style="font-size:10px;">REFUNDED</span></center>
														<br/>
														<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
														<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
														<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
													</p>	
												</div>
											</body>
										</html>';
									}
								}else{
									$error++;
									$result = 40;
								}
							}else{
								$error++;
								$result = 0;
							}
						}else{
							$error++;
							$result = 3; // nareturn na
						}
					}
				}else{
					$error++;
					$result = 4;
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
				<title>PRINT REFUND</title>
					<link rel="stylesheet" type="text/css" href="../plugins/bootstrap/dist/css/bootstrap.min.css">
					<link rel="stylesheet" type="text/css" href="../plugins/themify-icons/themify-icons.css">
					<link rel="stylesheet" type="text/css" href="../build/css/second-layout.css">
					
				</style>
				</head>
				<body>';
			
				if($result == 2){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! Please contact system administrator. Unable to process your request for refund for this barcode</h1>
						</div>
					</div><br/>';
				}else if($result == 3){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/>The bet for this barcode has been returned/refunded already!  </h1>
						</div>
					</div><br/>';
				}else if($result = 4){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/> Barcode does not exist or barcode is not for the current fight!</h1>
						</div>
					</div><br/>';
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
				}else if($result == 40){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! System Print Settings. Please Contact System Developer for Assistance. </h1>
						</div>
					</div>';
				}else{
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! Please refresh the page and try again.</h1>
						</div>
					</div>';
				}	
				echo'
				</body>
			</html>';
		}
	}else{
		exit;
	}
}else{
	header('location: ../../index.php');
}
?>
