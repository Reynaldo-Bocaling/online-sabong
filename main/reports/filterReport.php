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
	$bettorType = $_REQUEST['bettorType'];
	$bettingStatus = $_REQUEST['bettingStatus'];
	$bettingUnder = $_REQUEST['bettingUnder'];
	
	$q_scope = "";
	$q_bettorType = "";
	$q_bettingStatus = "";
	$q_bettingUnder= "";
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
	}else if($scope == "currentfight"){
		$q_scope .= " WHERE ";
		$q_table = 1;
	}
	
	if($bettorType == "all"){
		$q_bettorType .= "";
	}else if($bettorType == 0){
		$q_bettorType .= " a.betRoleID = '0' AND ";
	}else if($bettorType == 1){
		$q_bettorType .= " a.betRoleID = '3' AND ";
	}
	
	
	if($bettingStatus == "all"){
		$q_bettingStatus .= "";
	}else{
		$q_bettingStatus .= " b.isBetting = '".$bettingStatus."' AND ";
	}
	if($bettingUnder == "all"){
		$q_bettingUnder .= "";
	}else{
		$q_bettingUnder .= " a.betType = '".$bettingUnder."' AND ";
	}
	
	$queryString = $q_scope . $q_bettorType . $q_bettingStatus .$q_bettingUnder;
	
	$_SESSION['queryString'] = $queryString;
	if($q_table ==0){
	 $qbets = $mysqli->query("SELECT ev.`eventDate`, a.`fightCode`, b.`fightNumber`, a.`betRoleID`, a.`betCode`, a.`isCancelled`, c.`betType` as betTypeStatus, d.`isBetting` as bettingStatus, e.`winner`, a.`betAmount`, a.`isClaim`, b.`isWinner`, b.`isBetting`, f.`mobileNumber` FROM `tblbetlists` a 
		LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
		LEFT JOIN `tblbettypes` c ON a.betType = c.id 
		LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
		LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
		LEFT JOIN `tblaccounts` f ON a.accountID = f.id 
		LEFT JOIN `tblevents` ev ON b.eventID = ev.id
		$queryString a.id > 0 ORDER BY a.id DESC ");
	}else if($q_table == 1){
		$qbets = $mysqli->query("SELECT ev.`eventDate`, a.`fightCode`, b.`fightNumber`, a.`betRoleID`, a.`betCode`, a.`isCancelled`, c.`betType` as betTypeStatus, d.`isBetting` as bettingStatus, e.`winner`, a.`betAmount`, a.`isClaim`, b.`isWinner`, b.`isBetting`, f.`mobileNumber` FROM `tblbetliststemp` a 
		LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
		LEFT JOIN `tblbettypes` c ON a.betType = c.id 
		LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
		LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
		LEFT JOIN `tblaccounts` f ON a.accountID = f.id 
		LEFT JOIN `tblevents` ev ON b.eventID = ev.id
		$queryString a.id > 0 ORDER BY a.id DESC ");
		
	}
	if($qbets->num_rows > 0){
		$x = 1;
		$count = 1;
		$totalBetAmount = 0;
		while($rbets = $qbets->fetch_assoc()){
			$isCancelled = $rbets['isCancelled'];
			$result .= '
			<tr>
				<td style="text-align:center;">'.$count.'</td>
				<td style="text-align:center;">'.$rbets['eventDate'].'</td>
				<td style="text-align:center;">'.$rbets['fightCode'].'</td>
				<td style="text-align:center;">'.$rbets['fightNumber'].'</td>';
				if($rbets['betRoleID'] == 3){
					$result .='
					<td style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
				}else{
					$result .='
					<td style="text-align:center;">TICKET</td>';
				}
					$result .='
					<td>'.$rbets['betCode'].'</td>
					<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
					<td style="text-align:center;">'.$rbets['bettingStatus'].'</td>';
					if($isCancelled == 0){
						if($rbets['isWinner'] == 0){
							if($rbets['bettingStatus'] == "CANCELLED"){
								$result .='
								<td style="text-align:center;">CANCELLED</td>';
							}else{
								$result .='
								<td style="text-align:center;">UNSETTLED</td>';
							}	
						}else if($rbets['isWinner'] == 3){
							$result .='
							<td style="text-align:center;">'.$rbets['winner'].'</td>';
						}else{ 
							if($rbets['betTypeStatus'] == $rbets['winner']){
								$result .='
								<td style="text-align:center;">WIN</td>';
							}else{
								$result .='
								<td style="text-align:center;">LOST</td>';
							}
						}
						$result .= '
						<td style="text-align:right;">'.number_format($rbets['betAmount'],2).'</td>';
						$totalBetAmount += $rbets['betAmount'];
					}else{
						$result .='
						<td style="text-align:center;">BET CANCELLED</td>
						<td></td>';	
					}
						$result .='
						</tr>';
						$count++;
		}
			$result .='
			<tr>
				<td colspan= "9" style="font-weight:bold;">TOTAL:</td>
				<td style="font-weight:bold; text-align:right;">'.number_format($totalBetAmount,2).'</td>
			</tr>';
	}
	echo $result;
?>
