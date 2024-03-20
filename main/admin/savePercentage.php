<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['companyID'])){
	header('location: ../../index.php');
}else{
	if(isset($_REQUEST['newPercent'])){
		$result = 0;
		$newPercentage = $_REQUEST['newPercent'];
		$percentToLess = ($newPercentage / 100);
		
		$check = $mysqli->query("SELECT `id` FROM `tblfights` WHERE isBetting = '1' || isBetting = '2' || isBetting = '3' || isBetting = '4' ");
		if($check->num_rows > 0){
			$result = 3;
		}else{
			$insert = $mysqli->query("INSERT INTO `tblpercentless` (`id`,`percentToLess`) VALUES ('', '".$percentToLess."') ");
			$result = 1;
		}
		echo $result;
	}

}
?>