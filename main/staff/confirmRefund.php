<?php
session_start();
require('../includes/connection.php');
include '../includes/qrcode/phpqrcode/qrlib.php'; 
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['txtRefundBarcode'])){
		$result = 0;
		$barcode = $_POST['txtRefundBarcode'];
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
		$query = $mysqli->query("SELECT a.`id`, a.`isReturned`, a.`betAmount`, b.`fightCode` FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightID = b.id WHERE a.`betCode` = '".$barcode."' AND (b.`isBetting` = '5' || b.`isWinner` = '3') AND a.accountID = '0' "); // isBetting =5 means fight CANCELLED
		
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$betListID = $row['id'];
				$isReturned = $row['isReturned'];
				$betAmount = $row['betAmount'];
				$dbfightCode = $row['fightCode'];
				if($isReturned == 0){
					$update = $mysqli->query("UPDATE `tblbetlists` SET `isReturned` = '1' WHERE id = '".$betListID."' ");
					if($update){
						$logAction = $dbfightCode .": BET amounting to " . number_format($betAmount,2) . " has been refunded due to cancelled fight using Transaction Code: " . $barcode;
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '4', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center;"> 
									BARCODE Verified Successfully!<br/>You are eligible for refund amounting to: '.number_format($betAmount,2).'
								</h1>
								<center><img alt="testing" src="barcode.php?codetype=Code128b&size=40&text='.$barcode.'&print=true"/><br /> '.$barcode.' <center>
							</div>
						</div><br/>';
					}else{
						echo '
						<div class = "row">
							<div class="col-md-12">
								<h1 style = "font-weight:bold; text-align:center;">ERROR! Please contact system administrator. Unable to process your request for refund for this barcode</h1>
								<center><img alt="testing" src="barcode.php?codetype=Code128b&size=40&text='.$barcode.'&print=true"/><br /> '.$barcode.' <center>
							</div>
						</div><br/>';
					}
				}else{
					echo '
					<div class = "row">
						<div class="col-md-12">
							<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/>The bet for this barcode has been returned/refunded already!  </h1>
							<center><img alt="testing" src="barcode.php?codetype=Code128b&size=40&text='.$barcode.'&print=true"/><br /> '.$barcode.' <center>
						</div>
					</div><br/>';
					
				}

			}
		}else{
			echo '
			<div class = "row">
				<div class="col-md-12">
					<h1 style = "font-weight:bold; text-align:center;">ERROR! <br/><br/> Barcode does not exist or fight was not cancelled!</h1>
					<center><img alt="testing" src="barcode.php?codetype=Code128b&size=40&text='.$barcode.'&print=true"/><br /> '.$barcode.' <center>
				</div>
			</div><br/>';
		}	
?>
	<script type="text/javascript" src="../plugins/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="../plugins/bootstrap/dist/js/bootstrap.min.js"></script>
		
  </body>
</html>
