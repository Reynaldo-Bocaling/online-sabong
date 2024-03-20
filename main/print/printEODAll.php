<?php
session_start();
require('../includes/connection.php');
if($_SESSION['roleID'] == 1 OR $_SESSION['roleID'] == 12){
	
	if(isset($_POST['generate_allsummaryreport'])){
		$result = 0;
		$eventID = mysqli_real_escape_string($mysqli, $_POST['hiddenEODAllEventID']);
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
		WHERE ev.id = '".$eventID."' AND a.betRoleID = '3' AND (b.isBetting = '5' OR b.isBetting = 6) AND a.isCancelled = '0' ");

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
		

	$query3 = $mysqli->query("SELECT a.`transactionID`, b.`transaction`, SUM(a.`amount`) as totalAmount,  a.`transactionCode`, ev.`eventDate` FROM `tblusertransactions` a
							LEFT JOIN `tblusertransactionsstatus` b ON a.transactionID = b.id 
							LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
							WHERE ev.id = '".$eventID."' AND a.statusID = '0' GROUP BY a.transactionID ORDER BY a.transactionID ");
		$tcashin = 0;			//done
		$tbets = 0;				//done
		$tcancelled = 0;			//done refer from tblbetlist where betting = 5
		$ttotalPayoutPaid = 0;		//done
		$ttotalCancelledPaid = 0;		//done
		$ttotalDrawPaid = 0;
		
		$ttotalCancelledDrawPaid = 0;
		$tdrawUnclaimed = 0;
		$tmobileDeposit = 0;
		$tmobileWithdraw = 0;
		$cashout = 0;
		$betCancelled = 0;
		$ttotalMoneyonhand =  0;
		if($query3->num_rows > 0){
			while($row3 = $query3->fetch_assoc()){
				$ttransactionID = $row3['transactionID'];
				if($ttransactionID == 1){ //1 cash in
					$tcashin = $row3['totalAmount'];
				}
				
				if($ttransactionID == 2){ // 2 bets
					$tbets = $row3['totalAmount'];
				}
				
				if($ttransactionID == 3){ // 3 payout
					$ttotalPayoutPaid = $row3['totalAmount'];
				}
				
				if($ttransactionID == 4){ // 4 refund cancelled
					$ttotalCancelledPaid = $row3['totalAmount'];
				}
				
				if($ttransactionID == 5){ // refund draw
					$ttotalDrawPaid = $row3['totalAmount'];
				}
				
				if($ttransactionID == 6){ // mobile deposit
					$tmobileDeposit = $row3['totalAmount'];
				}
				
				if($ttransactionID == 7){ // mobile withdraw
					$tmobileWithdraw = $row3['totalAmount'];
				}
				
				if($ttransactionID == 8){ // cash out
					$cashout = $row3['totalAmount'];
				}
				if($ttransactionID == 9){ // bet cancelled
					$betCancelled = $row3['totalAmount'];
				}
			}
			$ttotalMoneyonhand = (($tcashin + $tbets + $tmobileDeposit ) - ($ttotalPayoutPaid + $ttotalCancelledPaid + $ttotalDrawPaid + $tmobileWithdraw + $cashout + $betCancelled)) ;
		}		
		

		$ttotalCancelled= 0; 		// for total Cancelled bets
		$ttotalCancelledUnpaid = 0;   // for total Cancelled Bets Unpaid		
		$ttotalDrawUnpaid = 0;		//getting the total draw unpaid
		$ttotalCancelledDrawUnpaid = 0; // getting the total cancelled and total draw bets unpaid
		$ttotalPayoutUnclaimed = 0; 		// getting the payout unclaimed

		$query4 = $mysqli->query("SELECT a.`betType`, a.`betAmount`, a.`isClaim`, a.`userID`, a.`isReturned`, b.`isBetting`, b.`isWinner`
		FROM `tblbetlists` a 
		LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
		LEFT JOIN `tblbettypes` c ON a.betType = c.id 
		LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
		LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
		LEFT JOIN `tblevents` ev ON b.eventID = ev.id
		WHERE ev.id = '".$eventID."' AND a.isCancelled = '0' ");

		if($query4->num_rows > 0){
			while($row4 = $query4->fetch_assoc()){
				if($row4['isBetting'] == 5){ //getting the total cancelled bets
					$ttotalCancelled += $row4['betAmount'];
					
					if($row4['isReturned'] == 0){ //getting the cancelled bets unpaid
						$ttotalCancelledUnpaid += $row4['betAmount'];
						$ttotalCancelledDrawUnpaid += $row4['betAmount'];
					}
				}
				
				
				if($row4['isBetting'] == 6 AND $row4['isWinner'] == 3 AND $row4['isReturned'] == 0){ //getting the total draw unpaid
					$ttotalDrawUnpaid += $row4['betAmount'];
					$ttotalCancelledDrawUnpaid += $row4['betAmount'];

				}
				
				// getting the payout unclaimed
				if($row4['isBetting'] == 6 AND ($row4['betType'] == $row4['isWinner']) AND $row4['isClaim'] == 0){
					$ttotalPayoutUnclaimed += $row4['betAmount'];
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
					<title>PRINT EOD SUMMARY </title>
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
										<span style="font-size:10px; font-weight:bold;">END OF THE DAY REPORT</span><br/>
										<br/>
										
										<span style="font-size:10px;">Cash In: '.number_format($tcashin,2).'</span><br/>
										<span style="font-size:10px;">Total bets: '.number_format($tbets,2).'</span><br/>
										<span style="font-size:10px;">Total APP deposit: '.number_format($tmobileDeposit,2).'</span><br/>
										<br/>
										<span style="font-size:10px;">Cash Out: '.number_format($cashout,2).'</span><br/>
										<span style="font-size:10px;">Total payout paid: '.number_format($ttotalPayoutPaid,2).'</span><br/>
										<span style="font-size:10px;">Total cancelled paid: '.number_format($ttotalCancelledPaid,2).'</span><br/>
										<span style="font-size:10px;">Total cancelled bet: '.number_format($betCancelled,2).'</span><br/>
										<span style="font-size:10px;">Total draw paid: '.number_format($ttotalDrawPaid,2).'</span><br/>
										<span style="font-size:10px;">Total APP withdraw: '.number_format($tmobileWithdraw,2).'</span><br/>
										<br/>
										<span style="font-size:10px; font-weight:bold; ">Money on hand: </span><span style="font-size:12px; font-weight:bold; ">'.number_format($ttotalMoneyonhand,2).'</span><br/>
										<br/>
										
										<span style="font-size:10px;">OTHER INFORMATIONS</span><br/>
										<span style="font-size:10px;">-------------------------------------------</span><br/>
										
										
										<span style="font-size:10px;">Total Cancelled bets: '.number_format($ttotalCancelled,2).'</span><br/>
										<span style="font-size:10px;">Total cancelled unpaid: '.number_format($ttotalCancelledDrawUnpaid,2).'</span><br/>
										<span style="font-size:10px;">Draw Unclaimed: '.number_format($ttotalDrawUnpaid,2).'</span><br/>
										<span style="font-size:10px;">Total payout unclaimed: '.number_format($ttotalPayoutUnclaimed,2).'</span><br/>
										<br/>
						
										<span style="font-size:10px;">Total APP deposit: '.number_format($totalDepositPoints,2).'</span><br/>
										<span style="font-size:10px;">Total APP withdraw: '.number_format($totalWithdrawnPoints,2).'</span><br/>
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
}else{
	header('location: ../../index.php');
}
?>
		
		
		
		
		
		
		
        