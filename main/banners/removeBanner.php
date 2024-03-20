<?php
	session_start();
	include("../includes/connection.php");
	include("../includes/functions.php");

	// M A I N
	if(isset($_REQUEST['bannerID'])){
		$bannerID = sanitize($_REQUEST['bannerID'], $mysqli);

		$query = $mysqli->query("SELECT * FROM `tblbanner` WHERE id = '".$bannerID."' AND isActive = '1' ");
		
		if($query->num_rows > 0){
			$remove = $mysqli->query("UPDATE `tblbanner` SET isActive = '0', `isDefault` = '0' WHERE id = '".$bannerID."' ");
			
			if($remove){
				$result = 1;
			}else{
				$result = 0;
			}
		}else{
			$result = 2;
		}
		echo $result;
	}
?>