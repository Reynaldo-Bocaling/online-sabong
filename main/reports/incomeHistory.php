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
	$query = $mysqli->query("SELECT ev.`eventDate`, a.`fightNumber`, a.`fightWinner`, a.`betMeron`, a.`betWala`, a.`totalBets`, a.`fightIncome` FROM `tblfightsreport` a 
		LEFT JOIN `tblevents` ev ON a.eventID = ev.id
		ORDER BY a.id ASC");
				
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
		$result .='
		<table width="100%"border="1" cellpadding="2">
			<tr style="font-size:11px;">
				<td width="5%" align="center" style="font-weight:bold;">#</td>
				<td width="15%" align="center" style="font-weight:bold;">Date</td>
				<td width="10%" align="center">Fight #</td>
				<td width="10%" align="center">Fight Result</td>
				<td width="15%" align="center">Bets for Meron</td>
				<td width="15%" align="center">Bets for Wala</td>
				<td width="15%" align="center">Total Bets</td>
				<td width="15%" align="center">Income</td>
			</tr>
			<tbody>';
			
			if($query->num_rows > 0){
				$count = 1;
				$result .= '
					<tr>
					<td colspan = "8"></td>
				</tr>';
				$grandTotal = 0;
				$grandTotalMeron = 0;
				$grandTotalWala = 0;
				$grandIncome = 0;
				while($row = $query->fetch_assoc()){

					$fightDate = $row['eventDate'];
					$fightNumber = $row['fightNumber'];
					$fightWinner = $row['fightWinner'];
					$meronTotalBetAmount= $row['betMeron'];
					$walaTotalBetAmount = $row['betWala'];
					$totalBetAmount = $row['totalBets'];
					$totalIncome = $row['fightIncome'];

					$result .= '
						<tr>
							<td style="text-align:center;">'.$count.'</td>
							<td style="text-align:center;">'.$fightDate.'</td>
							<td style="text-align:center;">'.$fightNumber.'</td>
							<td style="text-align:center;">'.$fightWinner.'</td>
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
						<td colspan="4">&nbsp;&nbsp;GRAND TOTAL</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotalMeron,2).'&nbsp;&nbsp;</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotalWala,2).'&nbsp;&nbsp;</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotal,2).'&nbsp;&nbsp;</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandIncome,2).'&nbsp;&nbsp;</td>
					</tr>';
			}else{
				$result .= '
				<tr>
					<td colspan = "8" style="text-align:center;"> NO DATA TO DISPLAY</td>
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

