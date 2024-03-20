<?php
	session_start();
	require('main/includes/connection.php');
	
	if(isset($_REQUEST['mobileUser'])){
		$mobileUser = mysqli_real_escape_string($mysqli, $_POST['mobileUser']);
		$password = mysqli_real_escape_string($mysqli, $_POST['password']);
		
		$checkUser = is_numeric($mobileUser);
		if($checkUser == 1){
			$qclient = $mysqli->query("SELECT * FROM `tblaccounts` WHERE mobileNumber = '".$mobileUser."' && mobilePassword = '".md5($password)."' ");
			if($qclient->num_rows > 0){
				$qsystem = $mysqli->query("SELECT `systemNumber` FROM `tblsystem`");
				if($qsystem->num_rows > 0){
					$rsystem = $qsystem->fetch_assoc();
					$_SESSION['systemNumber'] = $rsystem['systemNumber'];
					$_SESSION['systemVar'] = $rsystem['systemVar'];
					$qAccess = $mysqli->query("SELECT * FROM tblevents ORDER BY id DESC LIMIT 1");
					if($qAccess->num_rows > 0){
						$rAccess = $qAccess->fetch_assoc();
						$userAccessStatus = $rAccess['userAccessStatus'];
						if($userAccessStatus == 1){
							$result = 6; // user access is prohibited	
						}else{
							while($rclient = $qclient->fetch_array()){
								$dbMobileNumber = $rclient['mobileNumber'];
								$dbPassword = $rclient['mobilePassword'];
								if($dbMobileNumber === $mobileUser AND $dbPassword === md5($password)){
									$_SESSION['accountID'] = $rclient['id']; // userID is the variable name for the user who logged in
									$_SESSION['firstname'] = $rclient['firstname'];					
									$_SESSION['lastname'] = $rclient['lastname'];
									$_SESSION['roleID'] = $rclient['roleID'];
									$_SESSION['mobileNumber'] = $rclient['mobileNumber'];
									$logAction = "LOGIN";
									$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '".$_SESSION['accountID']."', '', '3', '".$logAction."', NOW());");
									$result = 1;
								} // end of if
							}
						}
					}else{
						$result = 8;
					}
				}else{
					$result = 0;
				}
			}else{
				$result = 0;
			}
		}else{
			$qcomp = $mysqli->query("SELECT a.`id`, a.`username`, a.`password`, a.`cname`, a.`roleID`, a.`betTypeID`, a.`oddsSettings` FROM `tblusers` a WHERE a.username = '".$mobileUser."' AND a.password = '".md5($password)."' AND a.isActive ='1' ");
			if($qcomp->num_rows > 0){
				while($rcomp = $qcomp->fetch_array()){
						$_SESSION['companyID'] = $rcomp['id']; // userID is the variable name for the user who logged in
						$_SESSION['cname'] = $rcomp['cname'];
						$_SESSION['username'] = $rcomp['username'];
						$_SESSION['roleID'] = $rcomp['roleID'];
						$_SESSION['staffFor'] = $rcomp['betTypeID'];
						$_SESSION['oddsSettings'] = $rcomp['oddsSettings'];
						$logAction = "LOGIN";
						
						$qsystem = $mysqli->query("SELECT `systemNumber`, `systemVar` FROM `tblsystem`");
						if($qsystem->num_rows > 0){
							$rsystem = $qsystem->fetch_assoc();
							$_SESSION['systemNumber'] = $rsystem['systemNumber'];
							$_SESSION['systemVar'] = $rsystem['systemVar'];
							$qAccess = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1");
							if($qAccess->num_rows > 0){
								$rAccess = $qAccess->fetch_assoc();
								$userAccessStatus = $rAccess['userAccessStatus'];
								if($userAccessStatus == 1){
									if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 8){
										$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
										$result = 2;
									}else{
										$result = 6;
									}
								}else{
									$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
									$result = 2;
								}
							}else{
								if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 8){
									$logs = $mysqli->query("INSERT INTO `tbltransactionlogs`(`id`, `transactionID`, `accountID`, `userID`, `roleID`, `transactionDetails`, `dt`) VALUES ('', '6', '', '".$_SESSION['companyID']."', '".$_SESSION['roleID']."', '".$logAction."', NOW());");
									$result = 2;
								}else{
									$result = 6;
								}
							}
						}else{
							$result = 0;
						}
				} // end of if
			}else{
				$result = 0;
			}	
		}
		echo $result;
	}
?>






