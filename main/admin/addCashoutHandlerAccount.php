<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['cashoutUsername'])){
		$cashoutUsername = mysqli_real_escape_string($mysqli, $_POST['cashoutUsername']);
		$cashoutFullname = mysqli_real_escape_string($mysqli, $_POST['cashoutFullname']);
		
		$query = $mysqli->query("SELECT `id` FROM `tblusers` WHERE username = '".$cashoutUsername."' ");
		if($query->num_rows > 0){
			$result = 2;
		}else{
			$insert = $mysqli->query("INSERT INTO `tblusers`(`id`, `username`, `password`, `cname`, `roleID`, `isActive`) VALUES ('', '".$cashoutUsername."', '".md5($cashoutUsername)."', '".strtoupper($cashoutFullname)."', '13', '1')");
			
			if($insert){
				$logAction = "Added cashout handler account, username: ".$cashoutUsername;
				$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
				$result = 1;
			}else{
				$result = 0;
			}
		}
		echo $result;
	}
}
?>