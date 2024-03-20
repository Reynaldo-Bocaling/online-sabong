<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['cashinID'])){
		$result = 0;
		$cashinID = $_POST['cashinID'];
		
		$query = $mysqli->query("SELECT a.`transactionCode`, b.`username` FROM `tblusertransactions` a LEFT JOIN `tblusers` b ON a.userID = b.id WHERE a.id = '".$cashinID."' AND a.transactionID = '1' ");
		
		if($query->num_rows > 0){
			$row = $query->fetch_assoc();
			$transactionCode = $row['transactionCode'];
			$username = $row['username'];
			$update = $mysqli->query("UPDATE `tblusertransactions` SET statusID = '1' WHERE id = '".$cashinID."' AND transactionID = '1'  ");
			
			if($update){
				$logAction = "Cancelled the Cash IN transaction from teller" . $username . " with transaction Code: ".$transactionCode;
				$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '0', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
				$result = 1;
			}else{
				$result = 0;
			}
		}else{
			$result = 2;
		}
		
		echo $result;
	}

}
?>