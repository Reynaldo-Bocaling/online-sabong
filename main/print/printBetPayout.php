<?php
session_start();
$mysqli = new mysqli('localhost', 'root', 'fF}9Wkb-PDFa', 'dbmendez', 3307);
	if(mysqli_connect_errno()){
		printf("<center>Unable to payout at the moment. Please Contact System Developer. Thank you<center>"); 
		exit();
	}
if($_SESSION['roleID'] == 2){ // 2 = STAFF	
	if(isset($_REQUEST['txtBarCode'])){
		include 'phpqrcode/qrlib.php';
		$result = 0;
		$error = 0;
		$barcode = $_REQUEST['txtBarCode1'];
		$userID = $_SESSION['companyID'];
		
		$trimmed_str = strlen($barcode);
		if($trimmed_str < 14){	
			$error++;
			$result = 27; // event already closed
			
		}else{
			$ppQuery = $mysqli->query("SELECT `systemPrint`, CURRENT_TIMESTAMP() as curdatetime FROM `tblsystem` ");
			if($ppQuery->num_rows > 0){
				$ppr = $ppQuery->fetch_assoc();
				$systemPrintVal = $ppr['systemPrint'];
				$curdatetime = $ppr['curdatetime'];
			}
			
			$qe = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1; ");
			if($qe->num_rows > 0){
				$re =  $qe->fetch_assoc();
				$eventID = $re['id'];
				$curdate = $re['eventDate'];
				if($re['eventStatus'] == 0){
					$query = $mysqli->query("SELECT a.`id`, a.`betType`, a.`betAmount`, a.`isClaim`, a.`isReturned`, a.`isCancelled`, b.`fightCode`, b.`fightNumber`, b.`isBetting`, b.`isWinner`, b.`payoutMeron`, b.`payoutWala`, c.betType AS betTypeText FROM `tblbetlists` a 
									LEFT JOIN `tblfights` b ON a.fightCode= b.fightCode 
									LEFT JOIN `tblbettypes` c ON a.betType = c.id 
									WHERE a.betCode = '".$barcode."' AND a.accountID = '0' ORDER BY a.id DESC LIMIT 1 ");
					if($query->num_rows > 0){
						while($row = $query->fetch_assoc()){
							$betListID = $row['id'];
							$isClaim = $row['isClaim'];
							$isReturned = $row['isReturned'];
							$isCancelled = $row['isCancelled'];
							$betType = $row['betType'];
							$saveBetAmount = $row['betAmount'];
							$fightCode = $row['fightCode'];
							$saveBetFightNumber = $row['fightNumber'];
							$isBetting = $row['isBetting'];
							$isWinner = $row['isWinner'];
							$betTypeText = $row['betTypeText'];
							$dbpayoutMeron = $row['payoutMeron'];
							$dbpayoutWala = $row['payoutWala'];
							
							if($isCancelled == 0){
							
								if($isClaim == 0 AND $isReturned == 0){
									if($betType == 1){
										$totalpayout = (($saveBetAmount / 100) * $dbpayoutMeron);
									}else{
										$totalpayout = (($saveBetAmount / 100) * $dbpayoutWala);
									}
									
									if($isBetting == '6' AND $isWinner == '3'){
										if($isReturned == 0){
											$update = $mysqli->query("UPDATE `tblbetlists` SET `isReturned` = '1', `isClaim` = '1' WHERE id = '".$betListID."' ");
											if($update){
												$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES (0, '".$eventID."', '".$userID."', '5', '".$barcode."', '".$saveBetAmount."', NOW() ) ");
												//transactionID 5  is for DRAW
												$logAction = $fightCode .": Ticket BET amounting to " . number_format($saveBetAmount,2) . " has been refunded due to fight result DRAW using Transaction Code: " . $barcode;
												$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '4', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW()) ");
												if($systemPrintVal == 1){
													echo '
													<!DOCTYPE html>
													<html lang="en">
														<head>
															<meta charset="UTF-8">
															<meta name="viewport" content="width=device-width, initial-scale=1.0">
															<meta http-equiv="X-UA-Compatible" content="ie=edge">
															<link rel="stylesheet" href="style.css">
															<title>PRINT REFUND DRAW</title>
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
																	<center>Fight #: <span style="font-weight:bold; font-size:12px;">'. $saveBetFightNumber.'</span> (DRAW)</center>
																	<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																	<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>

																</p>		
															</div>
															<br/><hr/><br/>
															<div class="ticket">
																<p class="centered" style="margin-top:-10px;">
																	<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>								
																	<center><span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span><br/>
																	<center><span style="font-size:10px;">'.$_SESSION['systemName'].'</span></center>
																	<center><span style="font-size:10px;">Cashier: '.$_SESSION['username'].'</span></center>
																	<center><span style="font-size:10px;">REFUNDED</span></center>
																	<br/>
																	<center>Fight #: <span style="font-weight:bold; font-size:12px;">'. $saveBetFightNumber.'</span> (DRAW)</center>
																	<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																	<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
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
															<title>PRINT REFUND DRAW</title>
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
																	<center>Fight #: <span style="font-weight:bold; font-size:12px;">'. $saveBetFightNumber.'</span> (DRAW)</center>
																	<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																	<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>

																</p>		
															</div>
														</body>
													</html>';
												}
											}else{
												$error++;
												$result = 26;
											}
										}else{
											$error++;
											$result = 25;
										}
										
									
									}else if($isBetting == 5){
										if($isReturned == 0){
											$update = $mysqli->query("UPDATE `tblbetlists` SET `isReturned` = '1', `isClaim` = '1' WHERE id = '".$betListID."' ");
											if($update){
												$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES (0, '".$eventID."', '".$userID."', '4', '".$barcode."', '".$saveBetAmount."', NOW() ) ");
												//transactionID 4  is for CANCELLED FIGHT	
												$logAction = $fightCode .": Ticket BET amounting to " . number_format($saveBetAmount,2) . " has been refunded due to cancelled fight using Transaction Code: " . $barcode;
												$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '4', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
												$result = 1;
												if($systemPrintVal == 1){
													echo '
													<!DOCTYPE html>
													<html lang="en">
														<head>
															<meta charset="UTF-8">
															<meta name="viewport" content="width=device-width, initial-scale=1.0">
															<meta http-equiv="X-UA-Compatible" content="ie=edge">
															<link rel="stylesheet" href="style.css">
															<title>PRINT REFUND CANCELLED</title>
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
																	<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span> (CANCELLED)</center>
																	<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																	<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
																</p>	
															</div>
															<br><hr/><br/>
															<div class="ticket">
																<p class="centered" style="margin-top:-10px;">
																	<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>								
																	<center><span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
																	<center><span style="font-size:10px;">'.$_SESSION['systemName'].'</span></center>
																	<center><span style="font-size:10px;">Cashier: '.$_SESSION['username'].'</span></center>
																	<center><span style="font-size:10px;">REFUNDED</span></center>
																	<br/>
																	<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span> (CANCELLED)</center>
																	<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																	<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
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
															<title>PRINT REFUND CANCELLED</title>
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
																	<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span> (CANCELLED)</center>
																	<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																	<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
																</p>	
															</div>
														</body>
													</html>';
												}
											}else{
												$error++;
												$result = 24;
											}
										}else{
											$error++;
											$result = 23; // 23 already returned
										}
									
									
									}else if($isBetting == 6){
										if($isWinner == $betType){
											$update = $mysqli->query(" UPDATE `tblbetlists` SET `isClaim` = '1', `isReturned` = '1' WHERE `betCode` = '".$barcode."'  ");										
											if($update){ 
												$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES (0, '".$eventID."', '".$userID."', '3', '".$barcode."', '".$totalpayout."', NOW() ) ");
												//transactionID 3  is for PAYOUT
												$logAction = $fightCode.": Ticket BET claimed the winning amount " . number_format($totalpayout,2) . "; Transaction Code: ". $barcode;
												$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '8', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
												$result = 1;
												
													if($systemPrintVal == 1){
														echo '
														<!DOCTYPE html>
														<html lang="en">
															<head>
																<meta charset="UTF-8">
																<meta name="viewport" content="width=device-width, initial-scale=1.0">
																<meta http-equiv="X-UA-Compatible" content="ie=edge">
																<link rel="stylesheet" href="style.css">
																<title>PRINT PAYOUT</title>
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
																<div class="ticket" >
																	<p class="centered" style="margin-top:-10px;">
																		<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>	
																		<center><span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
																		<center><span style="font-size:10px;">'.$_SESSION['systemName'].'</span></center>
																		<center><span style="font-size:10px;">Cashier: '.$_SESSION['username'].'</span></center>
																		<center><span style="font-size:10px;">CLAIMED</span></center>
																		<br/>
																		<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
																		<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																		<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>';

																	if($betType == 1){
																		echo '<center>ODDS: <span style="font-weight:bold; font-size:12px;">'.number_format($dbpayoutMeron).'</span></center>';
																	}else{
																		echo '<center>ODDS: <span style="font-weight:bold; font-size:12px;">'.number_format($dbpayoutWala).'</span></center>';
																	}
																		echo'
																		<center>PAYOUT: <span style="font-weight:bold; font-size:12px;">'.number_format($totalpayout).'</span></center>
																	</p>		
																</div>
																
																<br/><hr/><br/>
																
																<div class="ticket" >
																	<p class="centered" style="margin-top:-10px;">
																		<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>	
																		<center><span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
																		<center><span style="font-size:10px;">'.$_SESSION['systemName'].'</span></center>
																		<center><span style="font-size:10px;">Cashier: '.$_SESSION['username'].'</span></center>
																		<center><span style="font-size:10px;">CLAIMED</span></center>
																		<br/>
																		<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
																		<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																		<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>';

																	if($betType == 1){
																		echo '<center>ODDS: <span style="font-weight:bold; font-size:12px;">'.number_format($dbpayoutMeron).'</span></center>';
																	}else{
																		echo '<center>ODDS: <span style="font-weight:bold; font-size:12px;">'.number_format($dbpayoutWala).'</span></center>';
																	}
																		echo'
																		<center>PAYOUT: <span style="font-weight:bold; font-size:12px;">'.number_format($totalpayout).'</span></center>
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
																<title>PRINT PAYOUT</title>
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
																<div class="ticket" >
																	<p class="centered" style="margin-top:-10px;">
																		<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>	
																		<center><span style=" font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
																		<center><span style="font-size:10px;">'.$_SESSION['systemName'].'</span></center>
																		<center><span style=" font-size:10px;">Cashier: '.$_SESSION['username'].'</span></center>
																		<center><span style=" font-size:10px;">CLAIMED</span></center>
																		<br/>
																		<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
																		<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
																		<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>';

																	if($betType == 1){
																		echo '<center>ODDS: <span style="font-weight:bold; font-size:12px;">'.number_format($dbpayoutMeron).'</span></center>';
																	}else{
																		echo '<center>ODDS: <span style="font-weight:bold; font-size:12px;">'.number_format($dbpayoutWala).'</span></center>';
																	}
																		echo'
																		<center>PAYOUT: <span style="font-weight:bold; font-size:12px;">'.number_format($totalpayout).'</span></center>
																	</p>		
																</div>
															</body>
														</html>';
													}
											}else{
												$error++;
												$result = 22; // 22 means payout unsuccessful
											}
											
										}else{
											$error++;
											$result = 21; // means the BARCODE is not the winning Type.
										}
									}else if($isBetting ==3){
										$error++;
										$result = 20; // means Payout is not yet released!
									}else{
										$error++;
										$result = 19; // 19 means RESULT or Winner is not yet Declared!
									}
								}else{
									$error++;
									$result = 18; // 18 means the bet is already CLAIMED
								}
							}else{
								if($isClaim == 0 AND $isReturned == 0){
									$update = $mysqli->query("UPDATE `tblbetlists` SET `isReturned` = '1', isClaim = '1' WHERE id = '".$betListID."' ");
									if($update){
										$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES (0, '".$eventID."', '".$_SESSION['companyID']."', '4', '".$barcode."', '".$saveBetAmount."', NOW() ) ");
										//transactionID 4  is for CANCELLED FIGHT	
										$logAction = $fightCode .": Ticket BET amounting to " . number_format($saveBetAmount,2) . " has been refunded(cancelled) using Transaction Code: " . $barcode;
										$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '4', 0, '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
										$result = 1;
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
													<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span> (CANCELLED BET)</center>
													<center>SIDE: <span style="font-weight:bold; font-size:12px;">'.$betTypeText.'</span></center>						
													<center>AMOUNT: <span style="font-weight:bold; font-size:12px;">'. number_format($saveBetAmount).'</span></center>
												</p>	
											</div>
										</body>
										</html>';
									}else{
										$result = 0 ;
									}
								}else{
									$error++;
									$result = 17; // 17 means the bet is returned
								}
							}
						}
					}else{
						$error++;
						$result = 16; // 16 means the payout does not exist
					}
				}else{
					$error++;
					$result = 7; // event already closed
				}
			}else{
				$error++;
				$result = 8; // event already closed
			}
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
				<body  style="padding:3px;">';
			
				if($result == 5){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to placed a BET. Please refresh the page.</h1>
								</div>
							</div>';
						}else if($result == 6){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to placed a BET. Betting is already CLOSED! Please refresh the page.</h1>
								</div>
							</div>';
						}else if($result == 7){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Event is already closed!. </h1>
								</div>
							</div>';
						}else if($result == 8){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! No event for today. </h1>
								</div>
							</div>';
						}else if($result == 11){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Fight Bettings for MERON is temporarily closed. </h1>	
								</div>
							</div>';
						}else if($result == 12){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Fight Bettings for WALA is temporarily closed. </h1>
								</div>
							</div>';
						}else if($result == 13){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Cash Out amount is greater than the Teller\'s Money On Hand</h1>
								</div>
							</div>';
						}else if($result == 14){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Invalid Cash Out Password</h1>
								</div>
							</div>';
						}else if($result == 15){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to Cash Out. Please contact system administrator</h1>
								</div>
							</div>';
						}else if($result == 16){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Ticket does not exist or Fight Winner is not yet declared.</h1>
								</div>
							</div>';
						}else if($result == 17){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! The Cancelled Ticket was already Claimed/Returned .</h1>
								</div>
							</div>';
						}else if($result == 18){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Ticket was already Claimed.</h1>
								</div>
							</div>';
						}else if($result == 19){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Winner is not yet declared. Please wait for winner to be declared.</h1>
								</div>
							</div>';
						}else if($result == 20){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Payout was not yet released. Please wait for the payout to be released.</h1>
								</div>
							</div>';
						}else if($result == 21){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Ticket is not a winning ticket for this fight.</h1>
								</div>
							</div>';
						}else if($result == 22){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Payout unsuccessful.</h1>
								</div>
							</div>';
						}else if($result == 23){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Ticket amount already returned.</h1>
								</div>
							</div>';
						}else if($result == 24){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Ticket returned unsuccessfully.</h1>
								</div>
							</div>';
						}else if($result == 25){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! This Ticket for Draw Fight Result was already returned.</h1>
								</div>
							</div>';
						}else if($result == 26){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! unable to return this ticket with Draw Fight Result. Contact System Administrator for assistance</h1>
								</div>
							</div>';
						}elseif($result == 27){
							echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! <br/>Invalid Payout Ticket Code Length. <br/>Please refresh the page and re-scan the code. </h1>
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
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to placed a BET! Please refresh the page.</h1>
								</div>
							</div>';
						}
				echo'
					</body>
			</html>';
		}else{
	
		}
	}
	
}else{
	header('location: ../../index.php');
}

?>
		
		
		
		
		
		
		
        