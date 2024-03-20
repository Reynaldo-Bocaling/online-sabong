<?php
session_start();	
if($_SESSION['roleID']){ // 2 = STAFF
	require('../includes/connection.php');
	require('../includes/functions.php');
	if($_SESSION['roleID'] == 3){
		$userID = $_SESSION['accountID'];
		$q = $mysqli->query("SELECT `password` FROM `tblaccounts` WHERE id = '".$userID."' LIMIT 1");
		if($q->num_rows > 0){
			while($r = $q->fetch_assoc()){
				$result = $r['password'];
			}
		}else{
			$result = "";
		}
	}else{
		$userID = $_SESSION['companyID'];
		$q = $mysqli->query("SELECT `password` FROM `tblusers` WHERE id = '".$userID."' LIMIT 1");
		if($q->num_rows > 0){
			while($r = $q->fetch_assoc()){
				$result = $r['password'];
			}
		}else{
			$result = "";
		}
	}	
	echo $result;
}else{
	header('location: ../../index.php');
}
?>
