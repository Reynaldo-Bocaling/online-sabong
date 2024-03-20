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
		$report4HiddenScope = (isset($_POST['report4HiddenScope'])) ? $_POST['report4HiddenScope'] : $_SESSION['report4HiddenScope']; 
		$report4HiddenYear = (isset($_POST['report4HiddenYear'])) ? $_POST['report4HiddenYear'] : $_SESSION['report4HiddenYear']; 
		$report4HiddenMonth = (isset($_POST['report4HiddenMonth'])) ? $_POST['report4HiddenMonth'] : $_SESSION['report4HiddenMonth']; 
		$_SESSION['report4HiddenScope'] = $report4HiddenScope;
		$_SESSION['report4HiddenYear'] = $report4HiddenYear;
		$_SESSION['report4HiddenMonth'] = $report4HiddenMonth;
		$q_scope = "";
		$columns = "";
		
		if($_SESSION["report4HiddenScope"] == "all"){
			$q_scope .= " WHERE ";
			$columns .= "Scope: All ";
		}else if($_SESSION["report4HiddenScope"] == "year"){
			$columns .= "Scope: Year ".$_SESSION["report4HiddenYear"];
			$q_scope .= " WHERE ev.eventDate LIKE '".$_SESSION["report4HiddenYear"]."%' AND ";
		}else if($_SESSION["report4HiddenScope"] == "today"){
			$date = date('Y-m-d');
			$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F d, Y', strtotime($date));
		}else if($_SESSION["report4HiddenScope"] == "this_month"){
			$date = date('Y-m');
			$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
			$columns .= "Scope: ".date('F Y', strtotime($date));
		}else if($_SESSION["report4HiddenScope"]== "monthly"){
			if($_SESSION["report4HiddenMonth"]== "all"){

				$columns .= "Scope: ".$_SESSION["report4HiddenYear"];
				$q_scope .= " WHERE ev.eventDate LIKE '".$_SESSION["report4HiddenYear"]."%' AND ";
			}else{
				$date = $_SESSION["report4HiddenYear"] ."-".$_SESSION["report4HiddenMonth"];
				$columns .= "Scope: ".date('F Y', strtotime($date));
				$q_scope .= " WHERE ev.eventDate LIKE '$date%' AND ";
			}
		}else if($_SESSION["report4HiddenScope"] == "range"){
			$dateFrom = $_POST['report4HiddenRangeFrom'];
			$dateTo = $_POST['report4HiddenRangeTo'];
			$q_scope .= " WHERE ev.eventDate >= '".$dateFrom."' AND ev.eventDate <= '".$dateTo."' AND ";
			$columns .= "Scope: From ".date('F d, Y', strtotime($dateFrom)) . " to ".date('F d, Y', strtotime($dateTo));
		}else if($_SESSION["report4HiddenScope"] == "currentfight"){
			$q_scope .= " WHERE ";
			$columns .= "Scope: All";
		}
		
		$queryString = $q_scope ;
		$query = $mysqli->query("SELECT * FROM `tblnewbalance` a LEFT JOIN `tblaccounts` b ON a.accountID = b.id 
LEFT JOIN `tblevents` ev ON a.eventID = ev.id		$queryString  a.transID = '1' AND a.isProcess = '1' ORDER BY a.id DESC");
		
				
			//end of getting the score per category of each survey as well as the overall rating per category
		$result .= '
		<br/>
		<table width="100%">
			<tr>
				<td style="text-align:center;">
					<div style="font-weight:bold; font-size:14px; font-family:coopbl;">
						'.$_SESSION['systemName'].'<br/> MOBILEDEPOSIT HISTORY
					</div>
				</td>
			</tr>
		</table><br/><br/>';
		$result .= '<div>Filters: <strong>'.$columns.'</strong></div><br>';		
		$result .='
		<table width="100%"border="1" cellpadding="2">
			<tr style="font-size:11px;">
				<td width="4%" align="center" style="font-weight:bold;">#</td>
				<td width="24%" align="center">Date</td>
				<td width="24%" align="center" style="font-weight:bold;">Account</td>
				<td width="24%" align="center">Transaction Code</td>
				<td width="24%" align="center">Transaction Amount</td>	
			</tr>
			<tbody>';
			
			if($query->num_rows > 0){
				$count = 1;
				$result .= '
					<tr>
					<td colspan = "5"></td>
				</tr>';
				$grandTotal = 0;
				while($row = $query->fetch_assoc()){
						$result .= '
						<tr>
							<td style="text-align:center;">'.$count.'</td>
							<td width="24%" align="center">'.date('M d, Y h:iA',strtotime($row['transDate'])).'</td>
							<td width="24%" align="center" style="font-weight:bold;">'.$row['mobileNumber'].'</td>
							<td width="24%" align="center">'.$row['transCode'].'</td>
							<td width="24%" align="right">'.number_format($row['transAmount'],2).'&nbsp;&nbsp;</td>
							
						</tr>';
						$count++;
						$grandTotal += $row['transAmount'];
				}
				
				$result .= '
					<tr>
						<td colspan="4">&nbsp;&nbsp;GRAND TOTAL</td>
						<td style="text-align:right; font-weight:bold;">'.number_format($grandTotal,2).'&nbsp;&nbsp;</td>
					</tr>';
			}else{
				$result .= '
				<tr>
					<td colspan = "5" style="text-align:center;"> NO DATA TO DISPLAY</td>
				</tr>';
			}
		$result .='
			</tbody>
		</table>';
			
	$pdf->AddPage();
	$pdf->writeHTML($result, true, false, true, false, '');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Deposit History.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
}else{
	header("location: ../../index.php");
}
