<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['generate_barcode'])){
		$saveBetAmount = $_POST['txtBetAmountBarcode'];
		$saveBetType = $_POST['hiddenBetTypeBarcode'];
		$saveBetFightNumber = $_POST['hiddenBetFightNumberBarcode'];
		$saveBetFightID = $_POST['hiddenBetFightIDBarcode'];
		$userID = $_SESSION['companyID'];

		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$piv = substr(str_shuffle($chars), 0, 8);
		$betCode = $saveBetFightNumber. "W-".$saveBetType.'-'.$saveBetAmount.'-'.$piv; 
			
			
		$q = $mysqli->query("SELECT `fightCode` FROM `tblfights` ORDER BY id DESC ");
		$r = $q->fetch_assoc();
		$fightCode = $r['fightCode'];
		if($saveBetType == "MERON"){
			$betTypeID = 1;
		}else{
			$betTypeID = 2;
		}
			
			$query = $mysqli->query("SELECT `betCode` FROM `tblbetliststemp` WHERE betCode ='".$betCode."' ");
			if($query->num_rows > 0){
				$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				$piv = substr(str_shuffle($chars), 0, 8);
				$betCode = $saveBetFightNumber. "W-".$saveBetType.'-'.$saveBetAmount.'-'.$piv; 
			}else{
				
			}
			$insertBet = $mysqli->query("INSERT INTO `tblbetliststemp`(`id`, `fightCode`, `betCode`, `betType`, `betAmount`, `fightID`, `isClaim`, `userID`) VALUES ('', '".$fightCode."', '".$betCode."', '".$betTypeID."', '".$saveBetAmount."', '".$saveBetFightID."', '0', '".$userID."' )");
			if($insertBet){		
				$logAction = $fightCode.": Walk-In BET under ". $saveBetType. " amounting to " . number_format($saveBetAmount,2) . "; Generated Transaction Code: ". $betCode;
				$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '3', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
				 echo "
				 <html>
					<head>
						<style type='text/css'>
							html, body{
							 width: 1.5in;
							 height: 1in;
							}
						   @media print 
							{
							 @page {
								size: 1.5in 1in;
								size: portrait;
								}
							}
						</style>
					</head>
					<body onload = 'window.print()' style='border:dashed 2px #000; padding:3px; padding-top:10px;'>
						 <center><img alt='testing' src='barcode.php?codetype=Code128b&size=40&text=".$betCode."&print=true'/><br /> ".$betCode." <center>
					 </body>
				 <html/>";
			}else{
				echo $betCode;
			}
		

	}

}
?>