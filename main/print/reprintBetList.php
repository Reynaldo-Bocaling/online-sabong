<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_barcode'])){
		include 'phpqrcode/qrlib.php';
		$result = 0;
		$barcode = $_POST['barcode_text'];
		$userID = $_SESSION['companyID'];
		
		$q = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
		$r = $q->fetch_assoc();
		$curdatetime = $r['curdatetime'];

		$query = $mysqli->query("SELECT a.`betCode`, a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, b.`id`, b.`fightCode`, b.`fightNumber`, b.`fightDate`, b.`isWinner`, b.`isBetting`, c.`betType` as betTypeStatus, d.`isBetting` as bettingStatus, e.`winner`, f.`mobileNumber` FROM `tblbetlists` a 
								   LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
								   LEFT JOIN `tblbettypes` c ON a.betType = c.id 
								   LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
								   LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
								   LEFT JOIN tblaccounts f ON a.accountID = f.id 
								   WHERE betCode = '".$barcode."' LIMIT 1 ");
									
				$count = $query->num_rows;
				$row = $query->fetch_assoc();
				$currentFightCode = $row['fightCode'];
				$isBetting = $row['isBetting'];
				$saveBetFightNumber = $row['fightNumber'];
				$saveBetType = $row['betTypeStatus'];
				$saveBetAmount	= $row['betAmount'];
				$file = "yourQRcode.png"; 
				$ecc = 'L'; 
				$pixel_Size = 9; 
				$frame_Size = 8; 
				QRcode::png($barcode, $file, $ecc, $pixel_Size, $frame_Size); 
		
				echo '
				<!DOCTYPE html>
				<html lang="en">
					<head>
						<meta charset="UTF-8">
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
						<meta http-equiv="X-UA-Compatible" content="ie=edge">
						<link rel="stylesheet" href="style.css">
						<title>REPRINT BET</title>
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
							<img src="'.$file.'">
							<p class="centered" style="margin-top:-10px;">
								<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center><br/>										
								<center><span style="font-size:7px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
								<center><span style="font-size:7px;">'.$_SESSION['systemName'].'</span></center>
								<center><span style="font-size:7px;">Cashier: '.$_SESSION['username'].'</span></center>
								<center><span style="font-size:10px;">REPRINT BET</span></center>
								<br/>
								<center>Fight #: <span style="font-weight:bold; font-size:13px;">'. $saveBetFightNumber.'</span></center>
								<center>SIDE: <span style="font-weight:bold; font-size:13px;">'.$saveBetType.'</span></center>
								<center>AMOUNT: <span style="font-weight:bold; font-size:13px;">'. number_format($saveBetAmount).'</span></center>
								<br/>
								<center><span style="font-size:7px;">Ang nanalong ticket ay pwede lamang i-claim sa lugar na ito.</span></center>
								<center><span style="font-size:7px;">'.$_SESSION['systemLocation'].'</span></center>
							</p>
						</div>
					</body>
				</html>';


	}
}
?>
		
		
		
		
		
		
		
        