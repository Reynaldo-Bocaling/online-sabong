<?php
session_start();
require('../includes/connection.php');
include '../includes/qrcode/phpqrcode/qrlib.php'; 
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['txtDepositBarcode'])){
		$result = 0;
		$barcode = $_POST['txtDepositBarcode'];
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
		$query = $mysqli->query("SELECT a.`accountID`, a.`transCode`, a.`transAmount`, a.`isProcess`, b.`mobileNumber`, b.`balance` FROM `tblnewbalance` a LEFT JOIN `tblaccounts` b ON a.accountID = b.id WHERE a.`transCode` = '".$barcode."' AND a.`transID` = '1'  ");
		
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$isProcess = $row['isProcess'];
				$accountID = $row['accountID'];
				$mobileNumber = $row['mobileNumber'];
				$transCode = $row['transCode'];
				$transAmount = $row['transAmount'];
				$currentBalance = $row['balance'];
				if($isProcess == 0){
					$update = $mysqli->query("UPDATE `tblaccounts` SET `balance` = (balance + $transAmount) WHERE id = '".$accountID."' ");
					if($update){
						$update1 = $mysqli->query("UPDATE `tblnewbalance` SET `isProcess` = '1' WHERE `transCode` = '".$barcode."' AND accountID = '".$accountID."' ");
						if($update1){
							$newBalance = $currentBalance + $transAmount;
							$logAction = "Points added to ".$mobileNumber." amounting to " . number_format($transAmount,2) . "; Account Balance from ".number_format($currentBalance,2)." to " . number_format($newBalance,2)." using Barcode: ". $barcode;
							$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '1', '".$accountID."', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							
							
							echo '
							<div class = "row">
								<div class="col-md-12">
									<h1 style = "font-weight:bold; text-align:center;">CONGRATULATIONS!!! <br/><br/> BARCODE: '.$barcode.' <br/><br/>DEPOSITED AMOUNT: '.number_format($transAmount,2).' <br/>has been successfully deposited on your account.<br/><br/>Your Account New Balance: '.number_format($newBalance,2).'</h1>
								</div>
							</div><br/>';
						}else{
							$update2 = $mysqli->query("UPDATE `tblaccounts` SET `balance` = (balance - $transAmount) WHERE id = '".$accountID."' ");
								echo '
								<div class = "row">
									<div class="col-md-12">
										<h1 style = "font-weight:bold; text-align:center;">ERROR! Unable to deposit this barcode:  '.$barcode.'</h1>
									</div>
								</div><br/>';
						}
					}else{
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center;">ERROR! Unable to deposit this barcode:  '.$barcode.'</h1>
							</div>
						</div><br/>';
					}
					
				}else{
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/> BARCODE: '.$barcode.' <br/> has been already use and deposited in your account. </h1>
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
