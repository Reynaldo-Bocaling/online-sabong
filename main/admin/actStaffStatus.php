<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['accountID'])){
		$accountID = $_POST['accountID'];
		
		$query = $mysqli->query("SELECT `username` FROM `tblusers` WHERE id = '".$accountID."'");
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$dbusername = $row['username'];
				$update = $mysqli->query("UPDATE `tblusers` SET `isActive` = '0' WHERE id = '".$accountID."' ");
				if($update){
					$logAction = "User account deactivated, username: ".$dbusername;
					$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					$result = 1;
				}
			}
			
			
		}else{
			$result = 0;

		}
		echo $result;
	}
}
?>