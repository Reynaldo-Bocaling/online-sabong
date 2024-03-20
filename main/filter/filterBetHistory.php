<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	$result = "";
	$columns = "";
	$fightcode = $_REQUEST['fightcode'];
	$teller = $_REQUEST['teller'];
	$betcode = $_REQUEST['betcode'];
	$bettype = $_REQUEST['bettype'];
	$fightresult = $_REQUEST['fightresult'];
	$claimstatus = $_REQUEST['claimstatus'];

	
	$q_fightcode = "";
	$q_teller = "";
	$q_betcode = "";
	$q_bettype = "";
	$q_fightresult = "";
	$q_claimstatus = "";
	
	
	$q_fightcode .= " WHERE a.fightCode = '".$fightcode."' AND ";

	
	
	if($teller == "ALL"){
		$q_teller .= "";
	}else{
		$q_teller .= " a.userID = '".$teller."' AND betRoleID = '0' AND ";
	}

	if($betcode == ""){
		$q_betcode .= "";
	}else{
		$q_betcode .= " a.betCode = '".$betcode."' AND ";
	}
	
	if($bettype == "ALL"){
		$q_bettype .= "";
	}else{
		$q_bettype .= " a.betType = '".$bettype."' AND ";
	}
	
	if($fightresult == "ALL"){
		$q_fightresult .= "";
	}else{
		$q_fightresult .= " b.isWinner = '".$fightresult."' AND ";
	}
	if($claimstatus == "ALL"){
		$q_claimstatus .= "";
	}else{
		$q_claimstatus .= " a.isClaim = '".$claimstatus."' AND ";
	}
	
	
	$queryString = $q_fightcode . $q_teller . $q_betcode . $q_bettype . $q_fightresult . $q_claimstatus;
	$_SESSION['queryString'] = $queryString;
	
	$qbets = $mysqli->query("SELECT a.`betCode`, a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, a.`isCancelled`, b.`fightNumber`, ev.`eventDate`, b.`isWinner`, c.`betType` as betTypeStatus, d.`isBetting`, e.`winner`, f.`mobileNumber`, u.`username` FROM `tblbetlists` a 
	LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
	LEFT JOIN `tblbettypes` c ON a.betType = c.id 
	LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
	LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
	LEFT JOIN tblaccounts f ON a.accountID = f.id 
	LEFT JOIN `tblevents` ev ON b.eventID = ev.id
	LEFT JOIN tblusers u ON a.userID = u.id
	$queryString a.id > 0 ORDER BY a.id DESC");
	if($qbets->num_rows > 0){
		$count = 1;
		while($rbets = $qbets->fetch_assoc()){
			$isCancelled = $rbets['isCancelled'];
			if($isCancelled == 0){
				$result .= '
				<tr>
					<td style="text-align:center;">'.$count.'</td>
					<td style="text-align:center;">'.$rbets['fightNumber'].'</td>';
					if($rbets['betRoleID'] == 3){
					$result .= '
					<td style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
					}else{
					$result .= '
					<td style="text-align:center;">TICKET - '.$rbets['username'].'</td>';
					}
					$result .= '
					<td>'.$rbets['betCode'].'</td>
					<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
					<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
					<td style="text-align:center;">'.$rbets['isBetting'].'</td>';
					if($rbets['isWinner'] == 0){
						if($rbets['isBetting'] == "CANCELLED"){
							$result .= '
							<td style="text-align:center;">CANCELLED</td>';
						}else{
							$result .= '
							<td style="text-align:center;">UNSETTLED</td>';
						}	
					}else if($rbets['isWinner'] == 3){
						$result .= '
							<td style="text-align:center;">DRAW</td>';
					}else{ 
						if($rbets['betTypeStatus'] == $rbets['winner']){
							$result .= '
							<td style="text-align:center;">WIN</td>';
						}else{
							$result .= '
							<td style="text-align:center;">LOST</td>';
						}
					}
					if($rbets['isBetting'] == "CANCELLED"){
						if($rbets['isReturned'] == 1){
																		$result .= '
																		<td style="text-align:center;">RETURNED</td>';
																	}else{
																		$result .= '
																		<td style="text-align:center;">FOR REFUND</td>';
																	}
																}else{
																	if($rbets['isClaim'] == 0){
																	$result .= '
																	<td style="text-align:center;">NO</td>';
																	}else{
																	$result .= '
																	<td style="text-align:center;">YES</td>';
																	}
																}
																if($rbets['isBetting'] == "CANCELLED" || $rbets['isWinner'] == 3){
																	if($rbets['isReturned'] == 1){
																		$result .= '
																		<td style="text-align:center;">RETURNED</td>';
																	}else{
																		$result .= '
																		<td style="text-align:center;">FOR REFUND</td>';
																	}
																}else{
																	$result .= '
																	<td style="text-align:center;"></td>';
																}
														
																$result .= '
															</tr>';
														}else{
														   $result .= '
															 <tr>
																<td style="text-align:center;">'.$count.'</td>
																<td style="text-align:center;">'.$rbets['eventDate'].'</td>
																<td style="text-align:center;">'.$rbets['fightNumber'].'</td>';
																if($rbets['betRoleID'] == 3){
																	$result .= '<td style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
																}else{
																	$result .= '
																	<td style="text-align:center;">TICKET - '.$rbets['username'].'</td>';
																}
																$result .= '
																<td>'.$rbets['betCode'].'</td>
																<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
																<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
																<td style="text-align:center;">'.$rbets['isBetting'].'</td>
																<td style="text-align:center;">BET CANCELLED</td>
																<td style="text-align:center;">-</td>
																<td style="text-align:center;">-</td>
															</tr>';
														}
													$count++;
												   }
											   }
																		
	echo $result;
}
?>
