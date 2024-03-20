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
	$dateFrom = $_REQUEST['rangeFrom'];
	$dateTo = $_REQUEST['rangeTo'];
	$transactionType = $_REQUEST['transactionType'];
	
	$q_scope = "";
	$q_transactionType = "";
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
	}elseif($scope == "range"){
		$q_scope .= " WHERE ev.eventDate >= '".$dateFrom."' AND ev.eventDate<= '".$dateTo."' AND ";
	}
	
	if($transactionType == "all"){
		$q_transactionType .= "";
	}else{
		$q_transactionType .= " a.transactionID = '".$transactionType."' AND ";
	}
	
	$queryString = $q_scope . $q_transactionType;
	$_SESSION['queryString'] = $queryString;
	
	$query = $mysqli->query("SELECT a.`transactionID`, b.`transaction`, a.`amount` as totalAmount, a.`transactionCode`, ev.`eventDate` FROM `tblusertransactions` a
			LEFT JOIN `tblusertransactionsstatus` b ON a.transactionID = b.id 
			LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
			$queryString a.statusID = '0' AND a.userID = '".$_SESSION['companyID']."' ORDER BY a.id ");
	if($query->num_rows > 0){
		$x = 1;
		$count = 1;
		$totalBetAmount = 0;
		while($row = $query->fetch_assoc()){
		$result .= '
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
			if($transactionID == 8){ // mobile withdraw
				$totalBetAmount -= $row['totalAmount'];
			}
			if($transactionID == 9){ // mobile withdraw
				$totalBetAmount -= $row['totalAmount'];
			}
			$count++;
		 }
		$result .='
			<tr>
				<td colspan = "2+" style="font-weight:bold;">TOTAL:</td>
				<td style="font-weight:bold; text-align:right;">'.number_format($totalBetAmount,2).'</td>
				<td colspan = "2" style="font-weight:bold; text-align:right;"></td>
			</tr>';
	}
	echo $result;
?>
