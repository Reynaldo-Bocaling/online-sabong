<?php
	session_start();
	include("../includes/connection.php");
	include("../includes/functions.php");

	// M A I N
	if(isset($_REQUEST['bannerID'])){
		$bannerID = sanitize($_REQUEST['bannerID'], $mysqli);

		$query = $mysqli->query("SELECT * FROM `tblbanner` WHERE id = '".$bannerID."' ");
		
		if($query->num_rows > 0){
			$update = $mysqli->query("UPDATE `tblbanner` SET isDefault = '0' WHERE id > 0");
			if($update){
				$update1 = $mysqli->query("UPDATE `tblbanner` SET `isDefault` = '1' WHERE id = '".$bannerID."' ");
				if($update1){
					$result = 1;
				}else{
					$result = 0;
				}
			}else{
				$result = 0;
			}
		}else{
			$result = 2;
		}
		echo $result;
	}
?>