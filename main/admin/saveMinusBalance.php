<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['mobileNumber'])){
		$mobileNumber = $_POST['mobileNumber'];
		$accountID = $_POST['accountID'];
		$points = $_POST['points'];
		
		
		$qcheck = $mysqli->query("SELECT `balance` FROM `tblaccounts` WHERE  id = '".$accountID."' && mobileNumber = '".$mobileNumber."' && balance >= '".$points."' ");
		if($qcheck->num_rows > 0){
			while($row = $qcheck->fetch_assoc()){
				$currentBalance = $row['balance'];
				 $newBalance = ($currentBalance - $points);
			}
			$mysqli->begin_transaction();
			try {
				$transactionDetails = "Current Balance: ".number_format($currentBalance,2).", Amount to Withdraw: " . number_format($points,2) . ", Account New Balance: " . number_format($newBalance,2);
			   $mysqli->query("UPDATE `tblaccounts` SET `balance`  = (`balance` - $points) WHERE id = '".$accountID."' && mobileNumber = '".$mobileNumber."'; 
							INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `transactionDetails`) VALUES ('', '2', '".$accountID."', '".$transactionDetails."');  
							");
			   if(!true) {
				   throw new Exception("Something went wrong");
			   }
				$mysqli->commit();
			   $result = 1;
			}
			catch (Exception $e) {
			   $mysqli->rollback();
			   $result = 0;
			}
		}else{
			$result = 2;
		}
		
		echo $result;
	}

}
?>