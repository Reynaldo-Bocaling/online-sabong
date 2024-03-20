<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_mobilesummaryreport'])){
		$result = 0;
		$eventID = mysqli_real_escape_string($mysqli, $_POST['hiddenEODMobileEventID']);
		$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
		$rtime = $qtime->fetch_assoc();
		$curdatetime = $rtime['curdatetime'];
		
		$totalDepositPoints = 0;
		$totalWithdrawnPoints = 0;
		$totalBets = 0;
		$totalCancelled = 0; 		
		$totalDraw = 0;	
		$totalPayout = 0;
		$query = $mysqli->query("SELECT `id`, `eventID`, `accountID`, `transCode`, `transID`, SUM(`transAmount`) as totalAmount, `isProcess`, `transDate` FROM `tblnewbalance` WHERE eventID = '".$eventID."' AND isProcess = '1' GROUP BY `transID` ");
		// transaction ID 1 = DEPOSIT
		// transaction ID 2 = WITHDRAW
		//isProcess 1 means successfull
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$amount = $row['totalAmount'];
				if($row['transID'] == 1){
					$totalDepositPoints += $amount;
				}
				if($row['transID'] == 2){
					$totalWithdrawnPoints += $amount;
				}
			}
		}
	

		$query2 = $mysqli->query("SELECT a.`betType`, a.`betAmount`, a.`isClaim`, a.`userID`, a.`isReturned`, b.`isBetting`, b.`isWinner`
		FROM `tblbetlists` a 
		LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
		LEFT JOIN `tblbettypes` c ON a.betType = c.id 
		LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
		LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
		LEFT JOIN `tblevents` ev ON b.eventID = ev.id
		WHERE ev.id = '".$eventID."' AND a.betRoleID = '3' AND (b.isBetting = '5' OR b.isBetting = 6)");

		//betRoleID 3 means Mobile bets, mobile bettors

		if($query2->num_rows > 0){
			while($row2 = $query2->fetch_assoc()){
				$betAmount = $row2['betAmount'];
				$totalBets += $betAmount;
				if($row2['isBetting'] == 5){ //getting the total cancelled bets
					$totalCancelled += $betAmount;
				}
				
				
				if($row2['isBetting'] == 6 AND $row2['isWinner'] == 3){ //getting the total draw unpaid
					$totalDraw += $betAmount;
				}
				
				if($row2['isBetting'] == 6 AND $row2['isWinner'] != 3){ //getting the total draw unpaid
					$totalPayout += $betAmount;
				}
			}
		}				
										 
		echo '
		<!DOCTYPE html>
			<html lang="en">
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<meta http-equiv="X-UA-Compatible" content="ie=edge">
					<link rel="stylesheet" href="style.css">
					<title>PRINT MOBILE SUMMARY </title>
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
									<p style="margin-top:-10px; margin-left:10px;">									
										<br/>
										<span style="font-weight:bold; font-size:9px;">SUMMARY REPORT(Cash Out)</span><br/>
										<span style="font-weight:bold; font-size:10px;">'.$_SESSION['systemName'].'</span><br/>
										<span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span><br/>
										<span style="font-size:10px; font-weight:bold;">MOBILE BETS</span><br/>
										<br/>
										<span style="font-size:10px; font-weight:bold;">Total APP deposit: '.number_format($totalDepositPoints,2).'</span><br/>
										<span style="font-size:10px; font-weight:bold;">Total APP withdraw: '.number_format($totalWithdrawnPoints,2).'</span><br/>
										<br/>
										<span style="font-size:10px;">Total bets: '.number_format($totalBets,2).'</span><br/>
										<span style="font-size:10px;">Total cancelled bets: '.number_format($totalCancelled,2).'</span><br/>
										<span style="font-size:10px;">Total draw bets: '.number_format($totalDraw,2).'</span><br/>	
										<span style="font-size:10px;">Total Win bets: '.number_format($totalPayout,2).'</span><br/>										
									</p>		
								</div>
							</body>
						</html>';
			
	}
}
?>
		
		
		
		
		
		
		
        