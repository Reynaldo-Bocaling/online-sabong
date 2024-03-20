 <?php
session_start();
require('../includes/connection.php');
if($_SESSION['roleID'] == 2){ // 2 = STAFF
	if(isset($_POST['txtWithdrawBarcode'])){
	$result = 0;
	$error = 0;
	$userID = $_SESSION['companyID'];
	$barcode = $_POST['txtWithdrawBarcode'];
	
	$q = $mysqli->query("SELECT CURRENT_TIMESTAMP() as curdatetime;");
	$r = $q->fetch_assoc();
	$curdatetime = $r['curdatetime'];
	$qe = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1; ");
		if($qe->num_rows > 0){
			$re =  $qe->fetch_assoc();
			$eventID = $re['id'];
			$curdate = $re['eventDate'];
			if($re['eventStatus'] == 0){
				$query = $mysqli->query("SELECT a.`id`, a.`accountID`, a.`transAmount`, a.`isProcess`, b.`mobileNumber`, b.`balance`, (b.balance - a.transAmount) as newbalance FROM `tblnewbalance` a LEFT JOIN `tblaccounts` b ON a.accountID = b.id WHERE a.`transCode` = '".$barcode."' AND a.`transID` = '2' ORDER BY a.id LIMIT 1");
		
				if($query->num_rows > 0){
					while($row = $query->fetch_assoc()){
						$isProcess = $row['isProcess'];
						$accountID = $row['accountID'];
						$mobileNumber = $row['mobileNumber'];
						$transAmount = $row['transAmount'];
						$currentBalance = $row['balance'];
						$newBalance = $row['newbalance'];
						$transID = $row['id'];
						if($isProcess == 0){
							if($transAmount < $currentBalance){
									
								$u = $mysqli->query("UPDATE `tblnewbalance` a INNER JOIN `tblaccounts` b ON a.accountID = b.id SET a.`isProcess` = '1', b.balance = (b.`balance` - $transAmount) WHERE a.accountID = '".$accountID."' AND a.transCode = '".$barcode."' AND a.id = '".$transID."'");

								if($u){
									$insertTrans = $mysqli->query("INSERT INTO `tblusertransactions`(`id`, `eventID`, `userID`, `transactionID`, `transactionCode`, `amount`, `transDate`) VALUES (0, '".$eventID."', '".$userID."', '7', '".$barcode."', '".$transAmount."', NOW() ) ");
										//transactionID 7  is for MOBILE WITHDRAW
									$logAction = "Points withdrawn to ".$mobileNumber." amounting to " . number_format($transAmount,2) . "; Account Balance from ".number_format($currentBalance,2)." to ".number_format($newBalance,2)." using Barcode: ". $barcode;
									$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '2', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
									echo '
										<!DOCTYPE html>
										<html lang="en">
											<head>
												<meta charset="UTF-8">
												<meta name="viewport" content="width=device-width, initial-scale=1.0">
												<meta http-equiv="X-UA-Compatible" content="ie=edge">
												<link rel="stylesheet" href="style.css">
												<title>PRINT MOBILE WITHDRAW</title>
												<style type="text/css">
													html, body{
														width: 2in;
														height: 1in;
														font-size: 8px;
														font-family: Arial, Helvetica, sans-serif; 
													}
													@media print{
														@page {
															size: 2in 1in;
															size: portrait;
														}
													}
												</style>
											<script type="text/javascript" src="jquery.min.js"></script>
											<script type="text/javascript" src="qrcode.js"></script>												
											</head>
											<body onload = "window.print()" style="padding:3px;">
												<div class="ticket">
													<input type = "hidden" value = "'.$barcode.'" id = "hiddennewqrcode" />
												<center><div id="qrcode" style="width:100px; height:100px; text-align:center;"></div></center>
													<p class="centered" style="margin-top:-10px;">
														<center><span style="font-weight:bold; font-size:15px;">'. $barcode.'</span></center>
														<center><span style="font-size:7px;">'.DATE("M d, Y H:i:s A", strtotime($curdatetime)). '</span></center>
														<center><span style="font-size:7px;">'.$_SESSION['systemName'].'</span></center>
														<center><span style="font-size:7px;">Cashier: '.$_SESSION['username'].'</span></center>
														<center><span style="font-size:9px;">POINTS WITHDRAWN</span></center></center>
														<br/>
														<center><span style="font-size:8px;">ACCOUNT: </span><span style="font-weight:bold; font-size:12px;">'.$mobileNumber.'</span></center>
														<center><span style="font-size:8px;">CURRENT POINTS: </span><span style="font-weight:bold; font-size:12px;">'. number_format($currentBalance).'</span></center>												
														<center><span style="font-size:8px;">WITHDRAW AMOUNT: </span><span style="font-weight:bold; font-size:12px;">'. number_format($transAmount).'</span></center>
														<center><span style="font-size:8px;">AVAILABLE POINTS: </span><span style="font-weight:bold; font-size:12px;">'. number_format($newBalance).'</span></center>
													</p>		
												</div>
												<script type="text/javascript">
														var qrcode = new QRCode(document.getElementById("qrcode"), {
															width : 100,
															height : 100
														});
														function makeCode () {		
															var elText = document.getElementById("hiddennewqrcode");
															qrcode.makeCode(elText.value);
														}
														makeCode();
													</script>
											</body>
										</html>';
								}
							}else{
								$u = $mysqli->query("UPDATE `tblnewbalance` SET `isProcess` = '5' WHERE accountID = '".$accountID."' AND transCode = '".$barcode."' AND id = '".$transID."' ");
								$logAction = "Request for Withdrawal of points amounting to " . number_format($transAmount,2) . " has been cancelled by the system due to insufficient points; Account Balance ".number_format($currentBalance,2)." using Barcode: ". $barcode;
									
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES (0, '2', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
								$result = 2;
								$error++;
							}									
						}else if($isProcess == 1){
							$result = 3;
							$error++;						
						}else if($isProcess == 5){
							$result = 4;
							$error++;						
						}
					} // end of WHILE LOOP
				}else{
					$error++;
					$result = 5; // barcode does not exist
				}
			}else{
				$error++;
				$result = 7; // event already closed
			}
		}else{
			$error++;
			$result = 8; // no event
		}
		if($error > 0){	
			echo '
			<!DOCTYPE html>
			<html lang="en">
			  <head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>PRINT MOBILE DEPOSIT</title>
					<link rel="stylesheet" type="text/css" href="../plugins/bootstrap/dist/css/bootstrap.min.css">
					<link rel="stylesheet" type="text/css" href="../build/css/second-layout.css">

				</head>
				<body>';
			
				if($result == 2){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! The amount to be withdrawn is greater than your current balance. Kindly generate new withdrawal barcode. </h1><br/>
							<h2 style = "font-weight:bold; text-align:center;">Request for Withdrawal of points amounting to ' . number_format($transAmount,2) . ' has been cancelled by the system due to insufficient points; Account Balance '.number_format($currentBalance,2).'</h2>
						</div>
					</div><br/>';
				}else if($result == 3){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! Barcode has been already use and withdrawn in your account. </h1>
						</div>
					</div><br/>';
				}else if($result = 4){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! Barcode has been cancelled by the system due to insufficient points. </h1>
						</div>
					</div><br/>';
				}else if($result = 4){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! Barcode does not exist. </h1>
						</div>
					</div><br/>';
				}else if($result == 7){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! Event is already closed!. </h1>
						</div>
					</div>';
				}else if($result == 8){
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! No event for today. </h1>

						</div>
					</div>';
				}else{
					echo '
					<div class = "row">
						<div class="col-md-12">
						<h1 style = "font-weight:bold; text-align:center;">ERROR! Please refresh the page and try again! </h1>
						</div>
					</div>';
				}	
				echo'
				</body>
			</html>';
		}
	}else{
		exit;
	}
}else{
	header('location: ../../index.php');
}
?>
