<?php
session_start();
require('../includes/connection.php');
if($_SESSION['roleID'] == 2){ // 2 = STAFF
	if(isset($_POST['barcode_payout'])){
	include 'phpqrcode/qrlib.php';
	$result = 0;
	$barcode = $_POST['barcode_payout'];
	$userID = $_SESSION['companyID'];
	
	$q = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
	$r = $q->fetch_assoc();
	$curdatetime = $r['curdatetime'];
	
	$qp = $mysqli->query("SELECT a.`betType`, a.`betAmount`, b.`fightNumber`, b.`isBetting`, b.`payoutMeron`, b.`payoutWala`, c.betType AS betTypeText FROM `tblbetlists` a 
		LEFT JOIN `tblfights` b ON a.fightCode= b.fightCode 
		LEFT JOIN `tblbettypes` c ON a.betType = c.id 
		WHERE a.betCode = '".$barcode."' AND isCancelled = '0' ORDER BY a.id DESC LIMIT 1 ");
		$file = "yourQRcode.png"; 
		$ecc = 'L'; 
		$pixel_Size = 9; 
		$frame_Size = 8; 
			QRcode::png($barcode, $file, $ecc, $pixel_Size, $frame_Size); 		
		while($rp = $qp->fetch_assoc()){
			$betType = $rp['betType']; //
			$saveBetAmount = $rp['betAmount'];
			$saveBetFightNumber = $rp['fightNumber']; //
			$isBetting = $rp['isBetting'];
			$betTypeText = $rp['betTypeText']; //
			$dbpayoutMeron = $rp['payoutMeron']; //
			$dbpayoutWala = $rp['payoutWala']; //
			if($betType == 1){
				$totalpayout = (($saveBetAmount / 100) * $dbpayoutMeron); //
			}else{
				$totalpayout = (($saveBetAmount / 100) * $dbpayoutWala); //
			}	

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
							<title>REPRINT PAYOUT</title>
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
									<center><span style="font-size:10px;">CLAIMED(REPRINT ONLY)</span></center>
									<br/>
									<center>Fight #: <span style="font-weight:bold; font-size:13px;">'.$saveBetFightNumber.'</span></center>
									<center>SIDE: <span style="font-weight:bold; font-size:10px;">'.$betTypeText.'</span></center>						
									<center>AMOUNT: <span style="font-weight:bold; font-size:10px;">'. number_format($saveBetAmount).'</span></center>';

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
							<div class="ticket">
								<p class="centered" style="margin-top:-10px;">
									<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>	
									<center><span style="font-size:7px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
									<center><span style="font-size:7px;">'.$_SESSION['systemName'].'</span></center>
									<center><span style="font-size:7px;">Cashier: '.$_SESSION['username'].'</span></center>
									<center><span style="font-size:10px;">CLAIMED(REPRINT ONLY)</span></center>
									<br/>
									<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
									<center>SIDE: <span style="font-weight:bold; font-size:10px;">'.$betTypeText.'</span></center>						
									<center>AMOUNT: <span style="font-weight:bold; font-size:10px;">'. number_format($saveBetAmount).'</span></center>';

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
							<title>REPRINT PAYOUT</title>
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
									<center><span style="font-size:10px;">CLAIMED(REPRINT ONLY)</span></center>
									<br/>
									<center>Fight #: <span style="font-weight:bold; font-size:12px;">'.$saveBetFightNumber.'</span></center>
									<center>SIDE: <span style="font-weight:bold; font-size:10px;">'.$betTypeText.'</span></center>						
									<center>AMOUNT: <span style="font-weight:bold; font-size:10px;">'. number_format($saveBetAmount).'</span></center>';

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
			}
		
		}
	}
}else{
	header('location: ../../index.php');
}
?>
