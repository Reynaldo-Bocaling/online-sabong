<?php
session_start();
require('../includes/connection.php');
if($_SESSION['roleID'] == 1 OR $_SESSION['roleID'] == 12){
	require('../assets/tcpdf/tcpdf.php');
	require('../assets/tcpdf/config/lang/eng.php');
	
	class MYPDF extends TCPDF {
		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			// Set font
			$this->SetFont('arial', 'R', 8);
			// Page number

			$this->Cell(0, 10, $_SESSION["systemName"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
		}
	}

	// create new PDF document
	//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF("LANDSCAPE", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	//$pdf->setPrintFooter(false);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set font
	$fontCooper = $pdf->addTTFfont('../assets/tcpdf/fonts/COOPBL.ttf', 'TrueTypeUnicode', '', 11);
	$fontArial = $pdf->addTTFfont('../assets/tcpdf/fonts/arial.ttf', 'TrueTypeUnicode', '', 11);

	$pdf->SetFont('COOPBL', 'BI', 8,'', 'false');
	$pdf->SetFont('arial', 'BI', 8,'', 'false');

	//set margins
	//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetMargins(10, 10, 10);

	//set auto page breaks
	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetAutoPageBreak(TRUE, 10);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);

	// ---------------------------------------------------------

	$result = "";
	$report2HiddenScope = (isset($_POST['report2HiddenScope'])) ? $_POST['report2HiddenScope'] : $_SESSION['report2HiddenScope']; 
	$_SESSION['report2HiddenScope'] = $report2HiddenScope;
	if($_SESSION['report2HiddenScope'] == ""){
		$result .= '
		<br/>
		<table width="100%">
			<tr>
				<td style="text-align:center;">
					<div style="font-weight:bold; font-size:14px; font-family:coopbl;">
						'.$_SESSION['systemName'].'<br/>BET HISTORY
					</div>
				</td>
			</tr>
		</table><br/><br/>
		<div>Filters: <br>	

		<table width="100%"border="1" cellpadding="2">
			<tr style="font-size:11px;">
				<td width="4%" align="center" style="font-weight:bold;">#</td>
				<td width="7%" align="center" style="font-weight:bold;">Date</td>
				<td width="7%" align="center">Fight #</td>
				<td width="9%" align="center">Status</td>
				<td width="9%" align="center">Bettor</td>
				<td width="16%" align="center">Bet Code</td>
				<td width="8%" align="center">Bet Under</td>
				<td width="8%" align="center">Result</td>
				<td width="8%" align="center">Amount</td>			
				<td width="8%" align="center">Winning Amount</td>		
				<td width="8%" align="center">is Claimed?</td>
				<td width="8%" align="center">is Returned?</td>
			</tr>
			<tbody>';
				$result .='
				<tr>
					<td colspan = "12" style="text-align:center;">PLEASE REGENERATE THE REPORT TO VIEW CURRENT RESULTS!</td>
				</tr>

			</tbody>
		</table>';
		$pdf->AddPage();
		$pdf->writeHTML($result, true, false, true, false, '');
	}else{
		$report2HiddenScope = (isset($_POST['report2HiddenScope'])) ? $_POST['report2HiddenScope'] : $_SESSION['report2HiddenScope']; 
		$report2HiddenYear = (isset($_POST['report2HiddenYear'])) ? $_POST['report2HiddenYear'] : $_SESSION['report2HiddenYear']; 
		$report2HiddenMonth = (isset($_POST['report2HiddenMonth'])) ? $_POST['report2HiddenMonth'] : $_SESSION['report2HiddenMonth']; 
		$report2HiddenBettorType = (isset($_POST['report2HiddenBettorType'])) ? $_POST['report2HiddenBettorType'] : $_SESSION['report2HiddenBettorType']; 
		$report2HiddenStatus = (isset($_POST['report2HiddenStatus'])) ? $_POST['report2HiddenStatus'] : $_SESSION['report2HiddenStatus']; 
		$report2HiddenBetUnder = (isset($_POST['report2HiddenBetUnder'])) ? $_POST['report2HiddenBetUnder'] : $_SESSION['report2HiddenBetUnder']; 

		
		$_SESSION['report2HiddenScope'] = $report2HiddenScope;
		$_SESSION['report2HiddenYear'] = $report2HiddenYear;
		$_SESSION['report2HiddenMonth'] = $report2HiddenMonth;
		$_SESSION['report2HiddenBettorType'] = $report2HiddenBettorType;
		$_SESSION['report2HiddenStatus'] = $report2HiddenStatus;
		$_SESSION['report2HiddenBetUnder'] = $report2HiddenBetUnder;
		$queryString = "";
		$q_scope = "";
		$q_status = "";
		$q_bettorType = "";
		$q_betunder = "";
		$columns = "";
		
		if($report2HiddenScope == "all"){
			$q_scope .= " WHERE ";
			$columns .= "Scope: All ";
		}else if($report2HiddenScope == "year"){
			$columns .= "Scope: Year ".$report2HiddenYear;
			$q_scope .= " WHERE ev.eventDate LIKE '$report2HiddenYear%' AND ";
		}else if($report2HiddenScope == "today"){
			$date = date('Y-m-d');
			$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F d, Y', strtotime($date));
		}else if($report2HiddenScope == "this_month"){
			$date = date('Y-m');
			$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F Y', strtotime($date));
		}else if($report2HiddenScope== "monthly"){
			if($report2HiddenMonth== "all"){

				$columns .= "Scope: ".$report2HiddenYear;
				$q_scope .= " WHERE ev.eventDate LIKE '$report2HiddenYear%' AND ";
			}else{
				$date = $report2HiddenYear ."-".$report2HiddenMonth;
				$columns .= "Scope: ".date('F Y', strtotime($date));
				$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
			}
		}else if($report2HiddenScope == "range"){
			$dateFrom = $_REQUEST['report2HiddenRangeFrom'];
			$dateTo = $_REQUEST['report2HiddenRangeTo'];
			$q_scope .= " WHERE ev.eventDate >= '".$dateFrom."' AND ev.eventDate <= '".$dateTo."' AND ";
			$columns .= "Scope: From ".date('F d, Y', strtotime($dateFrom)) . " to ".date('F d, Y', strtotime($dateTo));
		}else if($report2HiddenScope == "currentfight"){
			$q_scope .= " WHERE a.fightCode = (SELECT `fightCode` FROM `tblfights` ORDER BY id DESC LIMIT 1) AND ";
			$columns .= "Scope: CURRENT FIGHT ONLY";
		}
		
		if($report2HiddenStatus == "all"){
			$q_status .= " ";
		}else{
			$q_status .= " ";
		}
		
		if($report2HiddenBettorType == "all"){
			$q_bettorType .= "  ";
		}else if($report2HiddenBettorType == "0"){
			$q_bettorType .= " a.betRoleID = '".$report2HiddenBettorType."' AND ";
		}else if($report2HiddenBettorType == "1"){
			$q_bettorType .= " a.betRoleID > '0' AND ";
		}else{
			$q_bettorType .= "  ";
		}
		if($report2HiddenBetUnder == "all"){
			$q_betunder .= " ";
		}else{
			$q_betunder .= " ";
		}
		
		$queryString = $q_scope . $q_status . $q_bettorType . $q_betunder;
		if($report2HiddenScope == "currentfight"){
			$qbets = $mysqli->query("SELECT a.`betCode`, a.`betType` as bettorBetType,  a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`,  a.`isCancelled`, b.`fightNumber`, b.`isWinner`, c.`betType` as betTypeStatus, b.`payoutMeron`, b.`payoutWala`, d.`isBetting`, e.`winner`, f.`mobileNumber`, ev.`eventDate` FROM `tblbetliststemp` a 
							   LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
							   LEFT JOIN `tblbettypes` c ON a.betType = c.id 
							   LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
							   LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
							   LEFT JOIN `tblaccounts` f ON a.accountID = f.id 
							   LEFT JOIN `tblevents` ev ON b.eventID = ev.id
							   $queryString a.id > 0
							   ORDER BY a.id DESC ");
		}else{
		$qbets = $mysqli->query("SELECT a.`betCode`, a.`betType` as bettorBetType,  a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, a.`isCancelled`, b.`fightNumber`, b.`isWinner`, c.`betType` as betTypeStatus, b.`payoutMeron`, b.`payoutWala`, d.`isBetting`, e.`winner`, f.`mobileNumber`, ev.`eventDate` FROM `tblbetlists` a 
							   LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
							   LEFT JOIN `tblbettypes` c ON a.betType = c.id 
							   LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
							   LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
							   LEFT JOIN `tblaccounts` f ON a.accountID = f.id 
							   LEFT JOIN `tblevents` ev ON b.eventID = ev.id
							   $queryString a.id > 0  
							   ORDER BY a.id DESC ");
		}
				
			//end of getting the score per category of each survey as well as the overall rating per category
		$result .= '
		<br/>
		<table width="100%">
			<tr>
				<td style="text-align:center;">
					<div style="font-weight:bold; font-size:14px; font-family:coopbl;">
						'.$_SESSION['systemName'].'<br/>BET HISTORY
					</div>
				</td>
			</tr>
		</table><br/>';
		$result .= '<div>Filters: <strong>'.$columns.'</strong></div><br>';		
		$result .='
		<table width="100%"border="1" cellpadding="2">
			<tr style="font-size:11px;">
				<td width="4%" align="center" style="font-weight:bold;">#</td>
				<td width="7%" align="center" style="font-weight:bold;">Date</td>
				<td width="5%" align="center">Fight #</td>
				<td width="9%" align="center">Status</td>
				<td width="9%" align="center">Bettor</td>
				<td width="18%" align="center">Bet Code</td>
				<td width="8%" align="center">Bet Under</td>
				<td width="8%" align="center">Bet Amount</td>
				<td width="8%" align="center">Result</td>			
				<td width="8%" align="center">Winning Amount</td>		
				<td width="8%" align="center">is Claimed?</td>
				<td width="8%" align="center">is Returned?</td>
			</tr>
			<tbody>';
			
			if($qbets->num_rows > 0){
				$count = 1;
				$result .= '
					<tr>
					<td colspan = "12"></td>
				</tr>';
				$grandTotal = 0;
				$resultPayoutMeron = 0;
				$resultPayoutWala = 0;
				$grandTotalPayout= 0;
				while($rbets = $qbets->fetch_assoc()){
					if($rbets['isCancelled'] == 1){
						$betAmount = 0;
					}else{
						$betAmount = $rbets['betAmount'];
					}
					
					if($count % 30 == 0){
						if($rbets['bettorBetType'] == 1){
							$resultPayoutMeron = ($rbets['betAmount'] / 100) * $rbets['payoutMeron'];
						}else{
							$resultPayoutWala = ($rbets['betAmount'] / 100) * $rbets['payoutWala'];
						}
						$result .= '
							<tr>
								<td style="text-align:center;">'.$count.'</td>
								<td style="text-align:center;">'.$rbets['eventDate'].'</td>
								<td style="text-align:center;">'.$rbets['fightNumber'].'</td>
								<td style="text-align:center;">'.$rbets['isBetting'].'</td>
								<td style="text-align:center;">';
								
								if($rbets['betRoleID'] == 0){
									$result .= "TICKET";
								}else{
									$result .= $rbets['mobileNumber'];
								}
								$result .='
								</td>
								<td>'.$rbets['betCode'].'</td>
								<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>';
								$result .= '
									<td style="text-align:right;">'.number_format($rbets['betAmount'],2).'</td>
								';
								
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
										<td style="text-align:center;">'.$rbets['winner'].'</td>';
									
								}else{ 
									if($rbets['betTypeStatus'] == $rbets['winner']){
										$result .= '
										<td style="text-align:center;">WIN</td>';
									}else{
										$result .= '
											<td style="text-align:center;">LOST</td>';
									}
								}
								
								if($rbets['betTypeStatus'] == $rbets['winner']){
									if($rbets['isWinner'] == 1){
										$result .= '
										<td style="text-align:right;">'.number_format($resultPayoutMeron,2).'</td>';
										$grandTotalPayout += $resultPayoutMeron;
									}else{
										$result .= '
										<td style="text-align:right;">'.number_format($resultPayoutWala,2).'</td>';
										$grandTotalPayout += $resultPayoutWala;
									}
								}else{
									$result .= '
											<td style="text-align:right;"></td>';
								}
				
								if($rbets['isBetting'] == "CANCELLED"){
									$result .= '
										<td style="text-align:center;"></td>';
									/*if($rbets['isReturned'] == 1){
										$result .= '
										<td style="text-align:center;">RETURNED</td>';
									}else{
										$result .= '
										<td style="text-align:center;">FOR REFUND</td>';
									}
									*/
									if($rbets['isReturned'] == 1){
										$result .= '
										<td style="text-align:center;">RETURNED</td>';
									}else{
										$result .= '
										<td style="text-align:center;">FOR REFUND</td>';
									}
								}else{
									if($rbets['isWinner'] == 3){
										$result .= '
										<td style="text-align:center;"></td>';
										if($rbets['isReturned'] == 1){
											$result .= '
											<td style="text-align:center;">RETURNED</td>';
										}else{
											$result .= '
											<td style="text-align:center;">FOR REFUND</td>';
										}
									}else{
										if($rbets['isClaim'] == 0){
											if($rbets['isCancelled'] == 1){
												$result .= '
												<td style="text-align:center;">CANCELLED</td>';
											}else{
												$result .= '
												<td style="text-align:center;">NO</td>';
											}
											
										}else{
											if($rbets['isCancelled'] == 1){
												$result .= '
												<td style="text-align:center;">CANCELLED</td>';
											}else{
												$result .= '
												<td style="text-align:center;">YES</td>';
											}
											
										}
										$result .= '
										<td style="text-align:center;"></td>';
									}
								}

								$result .= '
							</tr>';
							$count++;
							$grandTotal += $betAmount;
					$result .='
			</tbody>
		</table>';	
					$pdf->AddPage();
					$pdf->writeHTML($result, true, false, true, false, '');
					$result = "";
					$result .='
					<table width="100%"border="1" cellpadding="2">
						<tr style="font-size:11px;">
							<td width="4%" align="center" style="font-weight:bold;">#</td>
							<td width="7%" align="center" style="font-weight:bold;">Date</td>
							<td width="5%" align="center">Fight #</td>
							<td width="9%" align="center">Status</td>
							<td width="9%" align="center">Bettor</td>
							<td width="18%" align="center">Bet Code</td>
							<td width="8%" align="center">Bet Under</td>
							<td width="8%" align="center">Bet Amount</td>
							<td width="8%" align="center">Result</td>			
							<td width="8%" align="center">Winning Amount</td>		
							<td width="8%" align="center">is Claimed?</td>
							<td width="8%" align="center">is Returned?</td>
						</tr>
						<tbody>';
					}else{
						if($rbets['isCancelled'] == 1){
							$betAmount = 0;
						}else{
							$betAmount = $rbets['betAmount'];
						}
						if($rbets['bettorBetType'] == 1){
							$resultPayoutMeron = ($rbets['betAmount'] / 100) * $rbets['payoutMeron'];
						}else{
							$resultPayoutWala = ($rbets['betAmount'] / 100) * $rbets['payoutWala'];
						}
					  $result .= '
						<tr>
								<td style="text-align:center;">'.$count.'</td>
								<td style="text-align:center;">'.$rbets['eventDate'].'</td>
								<td style="text-align:center;">'.$rbets['fightNumber'].'</td>
								<td style="text-align:center;">'.$rbets['isBetting'].'</td>
								<td style="text-align:center;">';
								
								if($rbets['betRoleID'] == 0){
									$result .= "TICKET";
								}else{
									$result .= $rbets['mobileNumber'];
								}
								$result .='
								</td>
								<td>'.$rbets['betCode'].'</td>
								<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>';
								$result .= '
									<td style="text-align:right;">'.number_format($rbets['betAmount'],2).'</td>
								';
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
										<td style="text-align:center;">'.$rbets['winner'].'</td>';
									
								}else{ 
									if($rbets['betTypeStatus'] == $rbets['winner']){
										$result .= '
										<td style="text-align:center;">WIN</td>';
									}else{
										$result .= '
											<td style="text-align:center;">LOST</td>';
									}
								}
								
								if($rbets['betTypeStatus'] == $rbets['winner']){
									if($rbets['isWinner'] == 1){
										$result .= '
										<td style="text-align:right;">'.number_format($resultPayoutMeron,2).'</td>';
										$grandTotalPayout += $resultPayoutMeron;
									}else{
										$result .= '
											<td style="text-align:right;">'.number_format($resultPayoutWala,2).'</td>';
											$grandTotalPayout += $resultPayoutWala;
									}
								}else{
									$result .= '
										<td style="text-align:right;"></td>';
								}
								if($rbets['isBetting'] == "CANCELLED"){
									$result .= '
										<td style="text-align:center;"></td>';
									/*if($rbets['isReturned'] == 1){
										$result .= '
										<td style="text-align:center;">RETURNED</td>';
									}else{
										$result .= '
										<td style="text-align:center;">FOR REFUND</td>';
									}
									*/
									if($rbets['isReturned'] == 1){
										$result .= '
										<td style="text-align:center;">RETURNED</td>';
									}else{
										$result .= '
										<td style="text-align:center;">FOR REFUND</td>';
									}
								}else{
									if($rbets['isWinner'] == 3){
										$result .= '
										<td style="text-align:center;"></td>';
										if($rbets['isReturned'] == 1){
											$result .= '
											<td style="text-align:center;">RETURNED</td>';
										}else{
											$result .= '
											<td style="text-align:center;">FOR REFUND</td>';
										}
									}else{
										if($rbets['isClaim'] == 0){
											if($rbets['isCancelled'] == 1){
												$result .= '
												<td style="text-align:center;">CANCELLED</td>';
											}else{
												$result .= '
												<td style="text-align:center;">NO</td>';
											}
										}else{
											if($rbets['isCancelled'] == 1){
												$result .= '
												<td style="text-align:center;">CANCELLED</td>';
											}else{
												$result .= '
												<td style="text-align:center;">YES</td>';
											}
										}
										$result .= '
										<td style="text-align:center;"></td>';
									}
								}
								$result .= '
							</tr>';
						$count++;
					}
					$grandTotal += $betAmount;
				}
				$result .= '
					<tr>
						<td colspan="7">&nbsp;&nbsp;GRAND TOTAL</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotal,2).'</td>
						<td colspan="2" style="text-align:right;">'.number_format($grandTotalPayout,2).'</td>
						<td style="text-align:right;"></td>
						<td style="text-align:right;"></td>
					</tr>';
			
			}else{
				$result .= '
				<tr>
					<td colspan = "12" style="text-align:center;"> NO DATA TO DISPLAY</td>
				</tr>';
			}
		$result .='
			</tbody>
		</table>';
			
		$pdf->AddPage();
		$pdf->writeHTML($result, true, false, true, false, '');
	}	

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Bet History.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
}else{
	header("location: ../../index.php");
}
