<?php
session_start();
require('../includes/connection.php');
	if(!isset($_SESSION['companyID'])){
		header('location: ../../index.php');
	}
	$result = "";
	$columns = "";
	$scope = $_REQUEST['scope'];
	$year = $_REQUEST['year'];
	$month = $_REQUEST['month']; 
	$daily = $_REQUEST['daily'];
	$teller = $_REQUEST['teller'];
	$transactionType = $_REQUEST['rtype'];
	
	$q_scope = "";
	$q_transactionType = "";
	$q_teller = "";
	$q_table = 0; // 0 means for tblbetlists, 1 means for tbetliststemp
	
	if($scope == "all"){
		$q_scope .= " WHERE ";
	}elseif($scope == "year"){
		$date = $year;
		$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
	}elseif($scope == "today"){
		$date = date('Y-m-d');
		$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
	}elseif($scope == "this_month"){
		$date = date('Y-m');
		$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
	
	}elseif($scope == "monthly"){
		
		if($month == "All"){
			$date = $year;
			$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
		}else{
			$date = $year ."-".$month;
			$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
		}
	}else if($scope == "daily"){
		$year = $_REQUEST['year'];
		$month = $_REQUEST['month']; 
		$daily = $_REQUEST['daily'];
		if($month == "All"){
			$q_scope .= " WHERE ev.eventDate LIKE '$year-%' AND ";
			$q_scope .= " WHERE b.obrDateReceived LIKE '$year-%' AND ";
		}else if($daily == "All"){
			$q_scope .= " WHERE ev.eventDate LIKE '$year-$month-%' AND ";
		}else{
			$q_scope .= " WHERE ev.eventDate LIKE '$year-$month-$daily%' AND ";
		}
	}else if($scope == "today"){
		
	}
	
	if($transactionType == "all"){
		$q_transactionType .= "";
	}else{
		$q_transactionType .= " a.transactionID = '".$transactionType."' AND ";
	}
	
	
	if($teller == "all"){
		$q_teller .= "";
	}else{
		$q_teller .= " a.userID = '".$teller."' AND ";
	}

	
	$queryString = $q_scope . $q_teller . $q_transactionType;
	
	$_SESSION['queryString'] = $queryString;
	$query = $mysqli->query("SELECT a.`transactionID`, b.`transaction`, a.`amount` as totalAmount, a.`transactionCode`, ev.`eventDate` FROM `tblusertransactions` a
							LEFT JOIN `tblusertransactionsstatus` b ON a.transactionID = b.id 
							LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
							$queryString  a.id > 0 AND a.statusID = '0' ORDER BY a.id ");
																			
																		if($query->num_rows > 0){
																			$x = 1;
																			$count = 1;
																			$totalBetAmount = 0;
																			while($row = $query->fetch_assoc()){
																			echo '
																				<tr>
																					<td style="text-align:center;">'.$count.'</td>
																					<td style="text-align:center;">'.$row['transaction'].'</td>
																					<td style="text-align:right;">'.number_format($row['totalAmount'],2).'</td>
																					<td style="text-align:center;">'.$row['transactionCode'].'</td>
																					<td style="text-align:center;">'.DATE("M d, Y", strtotime($row['eventDate'])).'</td>
																				</tr>';	
																				
																				$transactionID = $row['transactionID'];
																				if($transactionID == 1){ //1 cash in
																					$cashin = $row['totalAmount'];
																					$totalBetAmount += $row['totalAmount'];
																				}
																				
																				if($transactionID == 2){ // 2 bets
																					$bets = $row['totalAmount'];
																					$totalBetAmount += $row['totalAmount'];
																				}
																				
																				if($transactionID == 3){ // 3 payout
																					$totalPayoutPaid = $row['totalAmount'];
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				
																				if($transactionID == 4){ // 4 refund cancelled
																					$cancelledPaid = $row['totalAmount'];
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				
																				if($transactionID == 5){ // refund draw
																					$drawPaid = $row['totalAmount'];
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				
																				if($transactionID == 6){ // mobile deposit
																					$mobileDeposit = $row['totalAmount'];
																					$totalBetAmount += $row['totalAmount'];
																				}
																				
																				if($transactionID == 7){ // mobile withdraw
																					$mobileWithdraw = $row['totalAmount'];
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				if($transactionID == 8){ //cash out
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				
																				if($transactionID == 9){ //betCanclled
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				$count++;
																			 }
																			echo '
																				<tr>
																					<td colspan = "2" style="font-weight:bold;">TOTAL:</td>
																					<td style="font-weight:bold; text-align:right;">'.number_format($totalBetAmount,2).'</td>
																					<td colspan = "2" style="font-weight:bold; text-align:right;"></td>
																				</tr>';
																		}
																		
	echo $result;
?>
