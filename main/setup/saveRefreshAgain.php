<?php
session_start();
require('../includes/connection.php');
require('../includes/functions.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_POST['refreshPassword'])){
		$result = 0;
		$refreshPassword = sanitize($_REQUEST['refreshPassword'], $mysqli);
		$query = $mysqli->query("SELECT `id` FROM `tblusers` WHERE roleID = '9' AND id = '".$_SESSION['companyID']."' AND isActive = '1' AND password = '".md5($refreshPassword)."' ");
	
		if($query->num_rows > 0){
				$zeroBalance = $mysqli->query("UPDATE `tblaccounts` SET `balance` = '0.00' WHERE id > 0 ");
				if($zeroBalance){
					$truncateEvents = $mysqli->query("TRUNCATE TABLE `tblevents`; ");
					if($truncateEvents){
						$insertEvent = $mysqli->query("INSERT INTO `tblevents`(`id`, `eventDate`, `eventStatus`, `userAccessStatus`) VALUES('', NOW(), '0', '0') ");
						
						$truncate = $mysqli->multi_query("TRUNCATE TABLE `tblbetlists`; TRUNCATE TABLE `tblbetliststemp`; TRUNCATE TABLE `tblfights`; TRUNCATE TABLE `tblfightsreport`; TRUNCATE TABLE `tblnewbalance`; TRUNCATE TABLE `tbltransactionlogs`; TRUNCATE TABLE `tblusertransactions`; ");
		
						if($truncate){
							$result = 1;
						}else{
							$result = 0;
						}
					}
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