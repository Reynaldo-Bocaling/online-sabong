<?php
	session_start();
	require('../includes/connection.php');
	
	if(isset($_REQUEST['firstname'])){
		$fname = mysqli_real_escape_string($mysqli, $_POST['firstname']);
		$lname = mysqli_real_escape_string($mysqli, $_POST['lastname']);
		$mobileNumber = mysqli_real_escape_string($mysqli, $_POST['mobilenumber']);
		$pass = mysqli_real_escape_string($mysqli, $_POST['password']);
		$repeatpass = mysqli_real_escape_string($mysqli, $_POST['repeatpassword']);
		
		$q = $mysqli->query("SELECT * FROM `tblaccounts` WHERE `mobileNumber` = '".$mobileNumber."' LIMIT 1 ");
		if($q->num_rows > 0){
			$result = 2; // means already registered. registration will fail.
		}else{
			$insert = $mysqli->query("INSERT INTO `tblaccounts`(`id`, `mobileNumber`, `mobilePassword`, `firstname`, `lastname`, `roleID`, `registrationDate`) VALUES ('', '".$mobileNumber."', '".md5($pass)."', '".strtoupper($fname)."', '".strtoupper($lname)."', '3', NOW()) ");
			//roleID 3 means FOR CLIENT USER ACCOUNTS
			if($insert){
				$result = 1; // means registered successfully
				$logAction = "REGISTERED ACCOUNT: MOBILE NUMBER - " . $mobileNumber;
				$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '5', (SELECT MAX(ID) FROM tblaccounts), '', '3', '".$logAction."', NOW());");
				
							
							
			}else{
				$result = 0; // error occured during registration. 
			}
		}
		echo $result;
	}
?>






