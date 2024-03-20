<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['username'])){
		$username = mysqli_real_escape_string($mysqli, $_POST['username']);
		$systemname = mysqli_real_escape_string($mysqli, $_POST['systemname']);
		
		$query = $mysqli->query("SELECT `id` FROM `tblusers` WHERE username = '".$username."' ");
		if($query->num_rows > 0){
			$result = 2;
		}else{
			$insert = $mysqli->query("INSERT INTO `tblusers`(`id`, `username`, `password`, `cname`, `roleID`, `isActive`) VALUES ('', '".$username."', '".md5($username)."', '".strtoupper($systemname)."', '6', '1')");
			
			if($insert){
				$logAction = "Added fight controller account, username: ".$username;
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