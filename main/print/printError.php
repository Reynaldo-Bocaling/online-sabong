<?php
	session_start();
	$report_error = (isset($_POST['txtError'])) ? $_POST['txtError'] : $_SESSION['txtError']; 
	$_SESSION['txtError'] = $report_error;

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>ERROR</title>
		<link rel="stylesheet" href="../assets/dist/paper.css">
		<style>@page { size: legal landscape}</style>
		
		<style>body{color:#000;body.A4.landscape	}</style>
	</head>
	<body class="legal landscape">
	<?php
	echo'
		<section class="sheet padding-10mm">
			<article>';
						if($report_error == 5){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to placed a BET. Please refresh the page.</h1>
								</div>
							</div>';
						}else if($report_error == 6){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to placed a BET. Betting is already CLOSED! Please refresh the page.</h1>
								</div>
							</div>';
						}else if($report_error == 7){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Event is already closed!. </h1>
								</div>
							</div>';
						}else if($report_error == 8){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! No event for today. </h1>
								</div>
							</div>';
						}else if($report_error == 11){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Fight Bettings for MERON is temporarily closed. </h1>	
								</div>
							</div>';
						}else if($report_error == 12){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Fight Bettings for WALA is temporarily closed. </h1>
								</div>
							</div>';
						}else if($report_error == 13){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Cash Out amount is greater than the Teller\'s Money On Hand</h1>
								</div>
							</div>';
						}else if($report_error == 14){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Invalid Cash Out Password</h1>
								</div>
							</div>';
						}else if($report_error == 15){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to Cash Out. Please contact system administrator</h1>
								</div>
							</div>';
						}else if($report_error == 16){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Ticket Barcode does not exist or Fight Winner is not yet declared.</h1>
								</div>
							</div>';
						}else if($report_error == 17){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Ticket Barcode was cancelled by the System Administrator.</h1>
								</div>
							</div>';
						}else if($report_error == 18){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Ticket Barcode was already Claimed.</h1>
								</div>
							</div>';
						}else if($report_error == 19){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Winner is not yet declared. Please wait for winner to be declared.</h1>
								</div>
							</div>';
						}else if($report_error == 20){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Payout was not yet released. Please wait for the payout to be released.</h1>
								</div>
							</div>';
						}else if($report_error == 21){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Ticket Barcode is not a winning barcode for this fight.</h1>
								</div>
							</div>';
						}else if($report_error == 22){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">PAYOUT ERROR! Payout unsuccessful.</h1>
								</div>
							</div>';
						}else if($report_error == 23){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Ticket Barcode amount already returned.</h1>
								</div>
							</div>';
						}else if($report_error == 24){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Ticket Barcode returned unsuccessfully.</h1>
								</div>
							</div>';
						}else if($report_error == 25){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Ticket Barcode with Draw Fight Result was already returned.</h1>
								</div>
							</div>';
						}else if($report_error == 26){
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Ticket Barcode wtih Draw Fight Result returned unsuccessfully.</h1>
								</div>
							</div>';
						}else{
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center; font-size:80px;">ERROR! Unable to placed a BET! Please refresh the page.</h1>
								</div>
							</div>';
						}
			echo '
			</article>
		</section>';		
	
	?>	
  </body>
</html>