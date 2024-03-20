<?php
session_start();
	
if($_SESSION['roleID']){ // 2 = STAFF
	require('../includes/connection.php');
	require('../includes/functions.php');
	if(isset($_REQUEST['newPassword'])){
		$newPassword = md5(sanitize($_REQUEST['newPassword'], $mysqli));
		$oldPassword = md5(sanitize($_REQUEST['oldPassword'], $mysqli));
		
		if($_SESSION['roleID'] == 3){
			$userID = $_SESSION['accountID'];
			$query = $mysqli->query("SELECT `mobilePassword` FROM `tblaccounts` WHERE id = '".$userID."' && mobilePassword = '".$oldPassword."'LIMIT 1");
		
			if($query->num_rows > 0){
				while($row = $query->fetch_assoc()){	
					$update = $mysqli->query("UPDATE `tblaccounts` SET `mobilePassword` = '".$newPassword."' WHERE id = '".$userID."' ");
					if($update){
						$qo = $mysqli->query("SELECT `mobileNumber` FROM `tblaccounts` WHERE id = '".$userID."' ");
						$ro = $qo->fetch_assoc();
						$mobileNumber = $ro['mobileNumber'];
									
						$logAction =  $mobileNumber.": Password has been changed.";
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '".$_SESSION['accountID']."', '0', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
							$result = 1;
					}else{
						$result = 0;
					}
				}
			}
		}else{
			$userID = $_SESSION['companyID'];
			$query = $mysqli->query("SELECT `password` FROM `tblusers`  WHERE id = '".$userID."' AND password = '".$oldPassword."'LIMIT 1");
		
			if($query->num_rows > 0){
				while($row = $query->fetch_assoc()){	
					$update = $mysqli->query("UPDATE `tblusers` SET `password` = '".$newPassword."' WHERE id = '".$userID."' ");
					if($update){
						$result = 1;
						$qo = $mysqli->query("SELECT `cname` FROM `tblusers` WHERE id = '".$userID."' ");
						$ro = $qo->fetch_assoc();
						$systemName = $ro['cname'];
							
						$logAction =  $systemName.": Password has been changed.";
						$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
						$result = 1;
					}else{
						$result = 0;
					}
				}
			}else{
				$result = 2;
			}
		}
		echo $result;
	}
}else{
	header('location: ../../index.php');
}	
	
	
	
?>
