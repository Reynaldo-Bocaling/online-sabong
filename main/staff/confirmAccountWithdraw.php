<?php
session_start();
require('../includes/connection.php');
include '../includes/qrcode/phpqrcode/qrlib.php'; 
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['txtWithdrawBarcode'])){
		$result = 0;
		$barcode = $_POST['txtWithdrawBarcode'];
	}else{
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
	<!-- Bootstrap CSS-->
		<link rel="stylesheet" type="text/css" href="../plugins/bootstrap/dist/css/bootstrap.min.css">
	<!-- Fonts-->
		<link rel="stylesheet" type="text/css" href="../plugins/themify-icons/themify-icons.css">
	<!-- Primary Style-->
	
		<link rel="stylesheet" type="text/css" href="../build/css/second-layout.css">
		
	</style>
	</head>
	<body>
		<style type = "text/css">
		   @page { size:3.5in 4in; }
		</style>
<?php	
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
								$logAction = "Points withdrawn to ".$mobileNumber." amounting to " . number_format($transAmount,2) . "; Account Balance from ".number_format($currentBalance,2)." to ".number_format($newBalance,2)." using Barcode: ". $barcode;
								$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '2', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
									echo '
									<div class = "row">
										<div class="col-md-12">
											<h1 style = "font-weight:bold; text-align:center;">CONGRATULATIONS!!! <br/><br/> BARCODE: '.$barcode.' <br/><br/>WITHDRAWN AMOUNT: '.number_format($transAmount,2).' <br/>has been successfully withdrawn on your account.<br/><br/>Your Account New Balance: '.number_format($newBalance,2).'</h1>
										</div>
									</div><br/>';

							}else{
								echo '
								<div class = "row">
									<div class="col-md-12">
										<h1 style = "font-weight:bold; text-align:center;">ERROR! Unable to withdraw this barcode:  '.$barcode.'</h1>
									</div>
								</div><br/>';
							}
						}else{
							$u = $mysqli->query("UPDATE `tblnewbalance` SET `isProcess` = '5' WHERE accountID = '".$accountID."' AND transCode = '".$barcode."' AND id = '".$transID."' ");
							$logAction = "Request for Withdrawal of points amounting to " . number_format($transAmount,2) . " has been cancelled by the system due to insufficient points; Account Balance ".number_format($currentBalance,2)." using Barcode: ". $barcode;
							
							$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '2', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/> BARCODE: '.$barcode.' <br/><br/>The amount to be withdrawn is greater than your current balance. <br/>Kindly generate new withdrawal barcode. </h1><br/><br/>
									
									<h2 style = "font-weight:bold; text-align:center;">Request for Withdrawal of points amounting to ' . number_format($transAmount,2) . ' has been cancelled by the system due to insufficient points; Account Balance '.number_format($currentBalance,2).'</h2>
								</div>
							</div><br/>';
						}
					}else if($isProcess == 1){
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/> BARCODE: '.$barcode.' <br/> has been already use and withdrawn in your account. </h1>
							</div>
						</div><br/>';
					}else if($isProcess == 5){
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/> BARCODE: '.$barcode.' <br/> has been cancelled by the system due to insufficient points. </h1>
							</div>
						</div><br/>';
					}
				
			}
		}else{
			echo '
			<div class = "row">
				<div class="col-md-12">
					<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/> BARCODE: '.$barcode.' <br/> does not exist!</h1>
				</div>
			</div><br/>';
		}
		
		
?>
	<script type="text/javascript" src="../plugins/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="../plugins/bootstrap/dist/js/bootstrap.min.js"></script>
		
  </body>
</html>
