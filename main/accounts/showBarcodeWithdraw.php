<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['accountID'])){
	header('location: ../../index.php');
}else{
	$sessionAccountID = $_SESSION['accountID'];
	
	if(isset($_POST['accountID'])){
		$accountID = $_POST['accountID'];
		$transCode = $_POST['transCode'];
		$id = $_POST['id'];
		
		if($sessionAccountID == $accountID){
			$query = $mysqli->query("SELECT `id` FROM `tblnewbalance` WHERE id = '".$id."' AND transCode = '".$transCode."' ");
			if($query->num_rows > 0){
				$result = '<span style="font-weight:bold;">Withdrawal Transaction Code:</span><br/><span style="font-size:40px; font-weight:bold; color:blue;">' . $transCode.'</span><br/>';
			}else{
				$result = 3;
			}
		}else{
			$result = 2;
		}
		
	}else{
		$result = 0;
	}
	echo $result;
}
?>