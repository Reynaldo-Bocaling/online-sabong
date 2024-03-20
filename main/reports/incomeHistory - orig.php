<?php
session_start();
require('../includes/connection.php');
if($_SESSION['roleID'] == 1 OR $_SESSION['roleID'] == 12){
	require('../assets/tcpdf/tcpdf.php');
	require('../assets/tcpdf/config/lang/eng.php');
}else{
	header("location: ../../index.php");
}
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

	$report3HiddenScope = (isset($_POST['report3HiddenScope'])) ? $_POST['report3HiddenScope'] : $_SESSION['report3HiddenScope']; 
	$report3HiddenYear = (isset($_POST['report3HiddenYear'])) ? $_POST['report3HiddenYear'] : $_SESSION['report3HiddenYear']; 
	$report3HiddenMonth = (isset($_POST['report3HiddenMonth'])) ? $_POST['report3HiddenMonth'] : $_SESSION['report3HiddenMonth']; 
		
	$_SESSION['report3HiddenScope'] = $report3HiddenScope;
	$_SESSION['report3HiddenYear'] = $report3HiddenYear;
	$_SESSION['report3HiddenMonth'] = $report3HiddenMonth;
	$queryString = "";
	$q_scope = "";
	$columns = "";
		
		if($_SESSION["report3HiddenScope"] == "all"){
			$q_scope .= " WHERE ";
			$columns .= "Scope: All ";
		}else if($_SESSION["report3HiddenScope"] == "year"){
			$columns .= "Scope: Year ".$_SESSION["report3HiddenYear"];
			$q_scope .= " WHERE ev.`eventDate` LIKE '".$_SESSION["report3HiddenYear"]."%' AND ";
		}else if($_SESSION["report3HiddenScope"] == "today"){
			$date = date('Y-m-d');
			$q_scope .= " WHERE ev.`eventDate` LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F d, Y', strtotime($date));
		}else if($_SESSION["report3HiddenScope"] == "this_month"){
			$date = date('Y-m');
			$q_scope .= " WHERE ev.`eventDate` LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F Y', strtotime($date));
		}else if($_SESSION["report3HiddenScope"]== "monthly"){
			if($_SESSION["report3HiddenMonth"]== "all"){

				$columns .= "Scope: ".$_SESSION["report3HiddenYear"];
				$q_scope .= " WHERE ev.`eventDate` LIKE '".$_SESSION["report3HiddenYear"]."%' AND ";
			}else{
				$date = $_SESSION["report3HiddenYear"] ."-".$_SESSION["report3HiddenMonth"];
				$columns .= "Scope: ".date('F Y', strtotime($date));
				$q_scope .= " WHERE ev.`eventDate` LIKE '$date%' AND ";
			}
		}else if($_SESSION["report3HiddenScope"] == "range"){
			$dateFrom = $_REQUEST['report3HiddenRangeFrom'];
			$dateTo = $_REQUEST['report3HiddenRangeTo'];
			$q_scope .= " WHERE ev.`eventDate` >= '".$dateFrom."' AND ev.`eventDate` <= '".$dateTo."' AND ";
			$columns .= "Scope: From ".date('F d, Y', strtotime($dateFrom)) . " to ".date('F d, Y', strtotime($dateTo));
		}else if($_SESSION["report3HiddenScope"] == "currentfight"){
			$q_scope .= " WHERE a.id = (SELECT MAX(id) FROM `tblfights` ) AND ";
			$columns .= "Scope: CURRENT FIGHT ONLY";
		}
		$queryString = $q_scope ;
		$queryPercent = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
		$rowPercent = $queryPercent->fetch_assoc();	
		$percenttoless = $rowPercent['percentToLess'];
		
		$query = $mysqli->query("SELECT a.`id`, a.`fightCode`, ev.`eventDate`, a.`fightNumber`, a.`isBetting`, b.`isBetting` as isBettingText, c.`winner`, a.`payoutMeron`, a.`payoutWala`  FROM `tblfights` a 
		LEFT JOIN `tblevents` ev ON a.eventID = ev.id
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
		LEFT JOIN `tblwinner` c ON a.isWinner = c.id
		$queryString a.id > 0 AND (a.isBetting = '3' OR a.isBetting = '6')
		ORDER BY a.id ASC");
				
			//end of getting the score per category of each survey as well as the overall rating per category
		$result .= '
		<br/>
		<table width="100%">
			<tr>
				<td style="text-align:center;">
					<div style="font-weight:bold; font-size:14px; font-family:coopbl;">
						'.$_SESSION['systemName'].'<br/>SUMMARY OF INCOME
					</div>
				</td>
			</tr>
		</table><br/><br/>';
		$result .= '<div>Filters: <strong>'.$columns.'</strong></div><br>';		
		$result .='
		<table width="100%"border="1" cellpadding="2">
			<tr style="font-size:11px;">
				<td width="5%" align="center" style="font-weight:bold;">#</td>
				<td width="14%" align="center" style="font-weight:bold;">Date</td>
				<td width="8%" align="center">Fight #</td>
				<td width="8%" align="center">Fight Status</td>
				<td width="9%" align="center">Fight Result</td>
				<td width="14%" align="center">Bets for Meron</td>
				<td width="14%" align="center">Bets for Wala</td>
				<td width="14%" align="center">Total Bets</td>
				<td width="14%" align="center">Income</td>
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
				$grandIncome = 0;
				while($row = $query->fetch_assoc()){
					$fightID = $row['id'];
					$fightCode = $row['fightCode'];
					$fightDate = $row['eventDate'];
					$fightNumber = $row['fightNumber'];
					$isBetting = $row['isBetting'];
					$bettingStatus = $row['isBettingText'];
					$winner = $row['winner'];
					$payoutMeron = $row['payoutMeron'];
					$payoutWala = $row['payoutWala'];
						
					$meronTotalBetAmount = 0;
					$walaTotalBetAmount = 0;
					$totalBetAmount = 0;
					$totalIncome = 0;
						if($isBetting == 1 || $isBetting == 2 || $isBetting == 4){
							$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets, b.isWinner FROM `tblbetliststemp` a 
												LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
												WHERE b.fightCode = '".$fightCode."'  AND isCancelled = '0' GROUP BY a.betType");
						}else if($isBetting == 3 || $isBetting == 5 || $isBetting == 6){
							$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets, b.isWinner FROM `tblbetlists` a 
												LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
												WHERE b.fightCode = '".$fightCode."' AND isCancelled = '0' GROUP BY a.betType");
						}				
						if($qbets->num_rows > 0){	
							while($rbets = $qbets->fetch_assoc()){
								$betType = $rbets['betType'];
								$isWinner = $rbets['isWinner'];
								if($betType == 1){
									$totalBetAmount += $rbets['bets'];
									$meronTotalBetAmount = $rbets['bets'];
									
								}else{
									$totalBetAmount += $rbets['bets'];
									$walaTotalBetAmount = $rbets['bets'];
								}
							}
							if($winner == "DRAW"){
								$totalIncome = 0;
								$meronTotalBetAmount = 0;
								$walaTotalBetAmount = 0;
								$totalBetAmount = 0;
							}else{
								$totalIncome = ($totalBetAmount * $percenttoless);
							}
						}
						$result .= '
						<tr>
							<td style="text-align:center;">'.$count.'</td>
							<td style="text-align:center;">'.$fightDate.'</td>
							<td style="text-align:center;">'.$fightNumber.'</td>
							<td style="text-align:center;">'.$bettingStatus.'</td>
							<td style="text-align:center;">'.$winner.'</td>
							<td style="text-align:right;">'.number_format($meronTotalBetAmount,2).'&nbsp;&nbsp;</td>
							<td style="text-align:right;">'.number_format($walaTotalBetAmount,2).'&nbsp;&nbsp;</td>
							<td style="text-align:right;">'.number_format($totalBetAmount,2).'&nbsp;&nbsp;</td>
							<td style="text-align:right;">'.number_format($totalIncome, 2).'&nbsp;&nbsp;</td>
						</tr>
						';
						$count++;
						$grandTotalMeron += $meronTotalBetAmount;
						$grandTotalWala += $walaTotalBetAmount;
						$grandTotal += $totalBetAmount;
						$grandIncome += $totalIncome;
						
				}
				$result .='
					<tr>
						<td colspan="5">&nbsp;&nbsp;GRAND TOTAL</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotalMeron,2).'&nbsp;&nbsp;</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotalWala,2).'&nbsp;&nbsp;</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotal,2).'&nbsp;&nbsp;</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandIncome,2).'&nbsp;&nbsp;</td>
					</tr>';
			}else{
				$result .= '
				<tr>
					<td colspan = "9" style="text-align:center;"> NO DATA TO DISPLAY</td>
				</tr>';
			}
		$result .='
			</tbody>
		</table>';
			
		$pdf->AddPage();
		$pdf->writeHTML($result, true, false, true, false, '');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Income Summary.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

