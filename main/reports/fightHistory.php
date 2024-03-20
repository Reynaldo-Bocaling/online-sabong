<?php
session_start();
require('../includes/connection.php');
//error_reporting(-1);
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
		$report1HiddenScope = (isset($_POST['report1HiddenScope'])) ? $_POST['report1HiddenScope'] : $_SESSION['report1HiddenScope']; 
		$report1HiddenYear = (isset($_POST['report1HiddenYear'])) ? $_POST['report1HiddenYear'] : $_SESSION['report1HiddenYear']; 
		$report1HiddenMonth = (isset($_POST['report1HiddenMonth'])) ? $_POST['report1HiddenMonth'] : $_SESSION['report1HiddenMonth']; 
		$report1HiddenStatus = (isset($_POST['report1HiddenStatus'])) ? $_POST['report1HiddenStatus'] : $_SESSION['report1HiddenStatus']; 
		$_SESSION['report1HiddenScope'] = $report1HiddenScope;
		$_SESSION['report1HiddenYear'] = $report1HiddenYear;
		$_SESSION['report1HiddenMonth'] = $report1HiddenMonth;
		$_SESSION['report1HiddenStatus'] = $report1HiddenStatus;
		$queryString = "";
		$q_scope = "";
		$q_status = "";
		$columns = "";

		
		if($report1HiddenScope == "all"){
			$q_scope .= " WHERE ";
			$columns .= "Scope: All ";
		}else if($report1HiddenScope == "year"){
			$columns .= "Scope: Year ".$report1HiddenYear;
			$q_scope .= " WHERE ev.`eventDate` LIKE '$report1HiddenYear%' AND ";
		}else if($report1HiddenScope == "today"){
			$date = date('Y-m-d');
			$q_scope .= " WHERE ev.`eventDate` LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F d, Y', strtotime($date));
		}else if($report1HiddenScope == "this_month"){
			$date = date('Y-m');
			$q_scope .= " WHERE ev.`eventDate` LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F Y', strtotime($date));
		}else if($report1HiddenScope== "monthly"){
			if($report1HiddenMonth== "all"){

				$columns .= "Scope: ".$report1HiddenYear;
				$q_scope .= " WHERE ev.`eventDate` LIKE '$report1HiddenYear%' AND ";
			}else{
				$date = $report1HiddenYear ."-".$report1HiddenMonth;
				$columns .= "Scope: ".date('F Y', strtotime($date));
				$q_scope .= " WHERE ev.`eventDate` LIKE '$date%' AND ";
			}
		}else if($report1HiddenScope == "range"){
			$dateFrom = $_REQUEST['report1HiddenRangeFrom'];
			$dateTo = $_REQUEST['report1HiddenRangeTo'];
			$q_scope .= " WHERE ev.`eventDate` >= '".$dateFrom."' AND ev.`eventDate` <= '".$dateTo."' AND ";
			$columns .= "Scope: From ".date('F d, Y', strtotime($dateFrom)) . " to ".date('F d, Y', strtotime($dateTo));
		}else if($report1HiddenScope == "currentfight"){
			$q_scope .= " WHERE a.id = (SELECT MAX(id) FROM `tblfights` ) AND ";
			$columns .= "Scope: CURRENT FIGHT ONLY";
		}
		
		if($report1HiddenStatus == "all"){
			$q_status .= " ";
		}else{
			$q_status .= " a.isBetting = '".$report1HiddenStatus."' AND ";
		}

		$queryString = $q_scope . $q_status ;
		$query = $mysqli->query("SELECT a.`id`, a.`fightCode`, ev.`eventDate`, a.`fightNumber`, a.`isBetting`, b.`isBetting` as isBettingText, c.`winner`  FROM `tblfights` a 
		LEFT JOIN `tblevents` ev ON a.eventID = ev.id
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
		LEFT JOIN `tblwinner` c ON a.isWinner = c.id
		$queryString a.id > 0
		ORDER BY a.id ASC ");
		$queryPercent = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
		$rowPercent = $queryPercent->fetch_assoc();	
		$percentToLess = $rowPercent['percentToLess'];	
		$meronTotalBetAmount = 0;
			$walaTotalBetAmount = 0;
			$totalBetAmount = 0;
			$threePercent = 0;
			$totalAmountLessThreePercent = 0;
			$totalAmountIfMeronWins = 0;
			$totalAmountIfWalaWins = 0;
			$pesoEquivalentIfMeronWins = 0;
			$pesoEquivalentIfWalaWins = 0;
			$payoutMeron = 0;
			$payoutWala = 0;			
			//end of getting the score per category of each survey as well as the overall rating per category
		$result .= '
		<br/>
		<table width="100%">
			<tr>
				<td style="text-align:center;">
					<div style="font-weight:bold; font-size:14px; font-family:coopbl;">
						'.$_SESSION["systemName"].'<br/>FIGHT SUMMARY
					</div>
				</td>
			</tr>
		</table><br/><br/>';
		$result .= '<div>Filters: <strong>'.$columns.'</strong></div><br>';		
		$result .='
		<table width="100%"border="1" cellpadding="2">
			<tr style="font-size:11px;">
				<td width="4%" align="center" style="font-weight:bold;">#</td>
				<td width="11%" align="center" style="font-weight:bold;">Date</td>
				<td width="7%" align="center">Fight #</td>
				<td width="15%" align="center">Status</td>
				<td width="11%" align="center">Winner</td>
				<td width="10%" align="center">Bets for Meron</td>
				<td width="10%" align="center">Bets for Wala</td>
				<td width="10%" align="center">Total Bets</td>
				<td width="11%" align="center">Payout Meron</td>
				<td width="11%" align="center">Payout Wala</td>
			</tr>
			<tbody>';
			
			if($query->num_rows > 0){
				$count = 1;
				$result .= '
					<tr>
					<td colspan = "9"></td>
				</tr>';
				$grandTotal = 0;
				$grandTotalMeron = 0;
				$grandTotalWala = 0;

				while($row = $query->fetch_assoc()){
					$fightID = $row['id'];
					$fightCode = $row['fightCode'];
					$fightDate = $row['eventDate'];
					$fightNumber = $row['fightNumber'];
					$isBettingText = $row['isBettingText'];
					$isBetting = $row['isBetting'];
					$winner = $row['winner'];
					$meronTotalBetAmount = 0;
					$walaTotalBetAmount = 0;
					$totalBetAmount = 0;
						if($isBetting == 1 || $isBetting == 2 || $isBetting == 4){
							$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets, b.isBetting FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$fightCode."' and a.isCancelled = '0' GROUP BY betType");
						}else if($isBetting == 3 || $isBetting == 5 || $isBetting == 6){
							$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets, b.isBetting FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$fightCode."' and a.isCancelled = '0' GROUP BY betType");
						}
						if($qbets->num_rows > 0){
							while($rbets = $qbets->fetch_assoc()){
								$betType = $rbets['betType'];
								if($rbets['isBetting'] == 5){ // cancelled
									$totalBetAmount += 0;
									$meronTotalBetAmount = 0;
									$walaTotalBetAmount = 0;
								}else{
									if($betType == 1){
										$totalBetAmount += $rbets['bets'];
										$meronTotalBetAmount = $rbets['bets'];
									}else{
										$totalBetAmount += $rbets['bets'];
										$walaTotalBetAmount = $rbets['bets'];
									}
								}
								
							}
						$threePercent = ($totalBetAmount * $percentToLess);
						$totalAmountLessThreePercent = ($totalBetAmount - $threePercent);
						$totalAmountIfMeronWins = ($totalAmountLessThreePercent - $meronTotalBetAmount);
						$pesoEquivalentIfMeronWins = ($totalAmountIfMeronWins / $meronTotalBetAmount);
						$payoutMeron = (($pesoEquivalentIfMeronWins * 100 ) + 100);
											
						$totalAmountIfWalaWins = ($totalAmountLessThreePercent - $walaTotalBetAmount);
						$pesoEquivalentIfWalaWins = ($totalAmountIfWalaWins / $walaTotalBetAmount);
						$payoutWala = (($pesoEquivalentIfWalaWins *100 ) +100);
						}
				
						
						$result .= '
						<tr>
							<td style="text-align:center;">'.$count.'</td>
							<td style="text-align:center;">'.$fightDate.'</td>
							<td style="text-align:center;">'.$fightNumber.'</td>
							<td style="text-align:center;">'.$isBettingText.'</td>
							<td style="text-align:center;">'.$winner.'</td>
							<td style="text-align:right;">'.number_format($meronTotalBetAmount,2).'</td>
							<td style="text-align:right;">'.number_format($walaTotalBetAmount,2).'</td>
							<td style="text-align:right;">'.number_format($totalBetAmount,2).'</td>
							<td style="text-align:right;">'.number_format($payoutMeron,2).'</td>
							<td style="text-align:right;">'.number_format($payoutWala,2).'</td>
						</tr>
						';
						$count++;
						$grandTotalMeron += $meronTotalBetAmount;
						$grandTotalWala += $walaTotalBetAmount;
						$grandTotal += $totalBetAmount;

				}
				
				$result .= '
					<tr>
						<td colspan="5">&nbsp;&nbsp;GRAND TOTAL</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotalMeron,2).'</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotalWala,2).'</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotal,2).'</td>
						<td style="text-align:right;"></td>
						<td style="text-align:right;"></td>
					</tr>';
			}else{
				$result .= '
				<tr>
					<td colspan = "10" style="text-align:center;"> NO DATA TO DISPLAY</td>
				</tr>';
			}
		$result .='
			</tbody>
		</table>';
			
		$pdf->AddPage();
		$pdf->writeHTML($result, true, false, true, false, '');		

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Fight History.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
}else{
	header("location: ../../index.php");
}
