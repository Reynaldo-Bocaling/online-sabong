<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_summaryreport'])){
		$result = 0;
		$userID = $_SESSION['companyID'];
		
		$qtime = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
		$rtime = $qtime->fetch_assoc();
		$curdatetime = $rtime['curdatetime'];
		
		$query = $mysqli->query("SELECT a.`transactionID`, b.`transaction`, SUM(a.`amount`) as totalAmount,  a.`transactionCode`, ev.`eventDate` FROM `tblusertransactions` a
							LEFT JOIN `tblusertransactionsstatus` b ON a.transactionID = b.id 
							LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
							WHERE ev.id = (SELECT max(id) FROM `tblevents`) AND a.statusID = '0' AND userID = '".$userID."'  GROUP BY a.transactionID ORDER BY a.transactionID ");
		$cashin = 0;			//done
		$cashout = 0;
		$bets = 0;				//done
		$totalMoneyonhand =  0;
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$transactionID = $row['transactionID'];
				if($transactionID == 1){ //1 cash in
					$cashin = $row['totalAmount'];
				}
				
				if($transactionID == 2){ // 2 bets
					$bets = $row['totalAmount'];
				}
				if($transactionID == 8){ //1 cash in
					$cashout = $row['totalAmount'];
				}
			}
			$totalMoneyonhand = (($cashin + $bets) -  $cashout) ;
			
			
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
								<div class="ticket">
									<p style="margin-top:-10px; margin-left:10px;">									
										<br/>
										<span style="font-weight:bold; font-size:9px;">SUMMARY REPORT(Money on Hand)</span><br/>
										<span style="font-weight:bold; font-size:10px;">'.$_SESSION['systemName'].'</span><br/>
										<span style="font-size:10px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span><br/>
										<span style="font-size:10px; font-weight:bold;">Username: '.$_SESSION['username'].'</span><br/>
										<br/>
										
										<span style="font-size:10px;">Total bets: '.number_format($bets,2).'</span><br/>
										<span style="font-size:10px;">Cash In: '.number_format($cashin,2).'</span><br/>
										<br/>
										<span style="font-size:10px;">Cash Out: '.number_format($cashout,2).'</span><br/>
										<br/>
										<span style="font-size:10px; font-weight:bold; ">Money on hand: </span><span style="font-size:12px; font-weight:bold; ">'.number_format($totalMoneyonhand,2).'</span><br/>

										<br/>
									</p>		
								</div>
				</body>
			</html>';
			
	}
}
?>
		
		
		
		
		
		
		
        