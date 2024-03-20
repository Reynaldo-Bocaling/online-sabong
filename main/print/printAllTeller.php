<?php
session_start();
require('../includes/connection.php');
if($_SESSION['roleID'] == 1 OR $_SESSION['roleID'] == 12){
	if(isset($_POST['hiddenEODAllTellerID'])){
		$result = 0;
		$htmlBody = "";
		$eventID = $_POST['hiddenEODAllTellerID'];
		
		$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
		$rtime = $qtime->fetch_assoc();
		$curdatetime = $rtime['curdatetime'];
			$uquery = $mysqli->query("SELECT a.`userID`, u.`username`, ev.id FROM `tblusertransactions` a
								LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
								LEFT JOIN `tblusers` u ON a.userID = u.id
								WHERE ev.id = '".$eventID."' AND a.statusID = '0' AND u.roleID = '2' GROUP BY a.userID ORDER BY a.userID ");							
			if($uquery->num_rows > 0){
				while($urow = $uquery->fetch_assoc()){
					$cashin = 0;			//done
					$cashout = 0;
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
					$userID = $urow['userID'];
					$username = $urow['username'];
					$eventID = $urow['id'];
					
					
					$query = $mysqli->query("SELECT a.`transactionID`, b.`transaction`, SUM(a.`amount`) as totalAmount, a.`transactionCode`, ev.`eventDate` FROM `tblusertransactions` a
									LEFT JOIN `tblusertransactionsstatus` b ON a.transactionID = b.id 
									LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
									WHERE ev.id = '".$eventID."' AND a.statusID = '0' AND userID = '".$userID."' GROUP BY a.transactionID ORDER BY a.transactionID ");
					
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
							if($transactionID == 8){ //1 cash out
								$cashout = $row['totalAmount'];
							}
							if($transactionID == 9){ //1 bet cancelled
								$betCancelled = $row['totalAmount'];
							}
						
							$totalMoneyonhand = (($cashin + $bets + $mobileDeposit ) - ($totalPayoutPaid + $totalCancelledPaid + $totalDrawPaid + $mobileWithdraw + $cashout + $betCancelled)) ;
						}
			
						$totalCancelled= 0; 		// for total Cancelled bets
						$totalCancelledUnpaid = 0;   // for total Cancelled Bets Unpaid		
						$totalDrawUnpaid = 0;		//getting the total draw unpaid
						$totalCancelledDrawUnpaid = 0; // getting the total cancelled and total draw bets unpaid
						$totalPayoutUnclaimed = 0; 		// getting the payout unclaimed

						$query2 = $mysqli->query("SELECT a.`betType`, a.`betAmount`, a.`isClaim`, a.`userID`, a.`isReturned`, b.`isBetting`, b.`isWinner`
						FROM `tblbetlists` a 
						LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
						LEFT JOIN `tblbettypes` c ON a.betType = c.id 
						LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
						LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
						LEFT JOIN `tblevents` ev ON b.eventID = ev.id
						WHERE ev.id = (SELECT max(id) FROM `tblevents`) AND isCancelled = '0' ");

						if($query2->num_rows > 0){
							while($row2 = $query2->fetch_assoc()){
								if($row2['isBetting'] == 5 AND $row2['userID'] == $userID){ //getting the total cancelled bets
									$totalCancelled += $row2['betAmount'];
									
									if($row2['isReturned'] == 0){ //getting the cancelled bets unpaid
										$totalCancelledUnpaid += $row2['betAmount'];
										$totalCancelledDrawUnpaid += $row2['betAmount'];
									}
								}
								
								
								if($row2['isBetting'] == 6 AND $row2['isWinner'] == 3 AND $row2['userID'] == $userID AND $row2['isReturned'] == 0){ //getting the total draw unpaid
									$totalDrawUnpaid += $row2['betAmount'];
									$totalCancelledDrawUnpaid += $row2['betAmount'];

								}
								
								// getting the payout unclaimed
								if($row2['isBetting'] == 6 AND $row2['userID'] == $userID AND ($row2['betType'] == $row2['isWinner']) AND $row2['isClaim'] == 0){
									$totalPayoutUnclaimed += $row2['betAmount'];
								}
								
							}
						}
							
						$htmlBody  .= '
							<p style="margin-top:-10px; margin-left:10px;">									
								<br/>
								<span style="font-weight:bold; font-size:9px;">SUMMARY REPORT(Money on Hand)</span><br/>
								<span style="font-weight:bold; font-size:10px;">'.$_SESSION['systemName'].'</span><br/>
								<span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span><br/>
								<span style="font-size:10px; font-weight:bold;">Username: '.$username.'</span><br/>
								<br/>
								<span style="font-size:10px;">Cash In: '.number_format($cashin,2).'</span><br/>
								<span style="font-size:10px;">Total bets: '.number_format($bets,2).'</span><br/>
								<span style="font-size:10px;">Total mobile deposit: '.number_format($mobileDeposit,2).'</span><br/>
								<br/>
								<span style="font-size:10px;">Cash Out: '.number_format($cashout,2).'</span><br/>
								<span style="font-size:10px;">Total payout paid: '.number_format($totalPayoutPaid,2).'</span><br/>
								<span style="font-size:10px;">Total cancelled paid: '.number_format($totalCancelledPaid,2).'</span><br/>
								<span style="font-size:10px;">Total cancelled bet: '.number_format($betCancelled,2).'</span><br/>
								<span style="font-size:10px;">Total draw paid: '.number_format($totalDrawPaid,2).'</span><br/>
								<span style="font-size:10px;">Total mobile withdraw: '.number_format($mobileWithdraw,2).'</span><br/>
								<br/>
								<span style="font-size:10px; font-weight:bold; ">Money on hand: </span><span style="font-size:12px; font-weight:bold; ">'.number_format($totalMoneyonhand,2).'</span><br/>
								<br/>
										
								<span style="font-size:10px;">OTHER INFORMATIONS</span><br/>
								
										
										
								<span style="font-size:10px;">Total Cancelled bets: '.number_format($totalCancelled,2).'</span><br/>
								<span style="font-size:10px;">Total cancelled unpaid: '.number_format($totalCancelledDrawUnpaid,2).'</span><br/>
								<span style="font-size:10px;">Draw Unclaimed: '.number_format($totalDrawUnpaid,2).'</span><br/>
								<span style="font-size:10px;">Total payout unclaimed: '.number_format($totalPayoutUnclaimed,2).'</span><br/>
								<br/>
								<span style="font-size:10px;">-------------------------------------------</span><br/>
							</p>';
			
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
					<title>PRINT SUMMARY </title>
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
					<div class="ticket">';
					echo $htmlBody;
				echo '						
					</div>
				</body>
			</html>';
						
			
	}
	
}else{
	header('location: ../../index.php');	
}
?>
		
		
		
		
		
		
		
        