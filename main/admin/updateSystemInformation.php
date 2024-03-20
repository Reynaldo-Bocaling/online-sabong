<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_REQUEST['systemName'])){
		$result = 0;
		$systemName = mysqli_real_escape_string($mysqli, $_REQUEST['systemName']);
		$systemLocation = mysqli_real_escape_string($mysqli, $_REQUEST['systemLocation']);
		$password = mysqli_real_escape_string($mysqli, $_REQUEST['password']);
		
		$query = $mysqli->query("SELECT `password` FROM `tblusers` WHERE id = '".$_SESSION['companyID']."' AND roleID = '1' AND isActive = '1' LIMIT 1");
		if($query->num_rows > 0){
			$row = $query->fetch_assoc();
			
			$dbpassword = $row['password'];
			
			if($dbpassword === md5($password)){
				$update = $mysqli->query("UPDATE `tblsystem` SET `systemName`= '".STRTOUPPER($systemName)."', `systemLocation`= '".STRTOUPPER($systemLocation)."' WHERE id = 1 ");
				$_SESSION['systemName'] = $systemName;
				$_SESSION['systemLocation'] = $systemLocation;
				if($update){
					$logAction = " Updated system informations";
					$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '7', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
					$result = 1;
				}else{
					$result = 0;
				}
			}else{
				$result = 2; // invalid setup password
			}
			echo $result;
		}
	}

}
?>