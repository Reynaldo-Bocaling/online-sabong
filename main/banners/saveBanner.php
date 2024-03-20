<?php
	session_start();
	include("../includes/connection.php");
	include("../includes/functions.php");

	// M A I N
	if(isset($_REQUEST['banner'])){
		$banner = sanitize($_REQUEST['banner'], $mysqli);
		
		if($banner == "" || strlen($banner)< 3){
			$result = 3 ;
		}else{
			$query = $mysqli->query("SELECT * FROM `tblbanner` WHERE eventName = '".$banner."' AND isActive = '1' LIMIT 1");
			
			if($query->num_rows > 0){
				$result = 2;	
			}else{
				$queryD = $mysqli->query("SELECT * FROM `tblbanner` WHERE isActive = '1' AND isDefault = '1' LIMIT 1 ");
				if($queryD->num_rows > 0 ){
					$insert = $mysqli->query("INSERT INTO `tblbanner`(`id`, `eventName`, `isDefault`, `isActive`) VALUES ('',  '".strtoupper($banner)."', '0', '1') ");
					if($insert){
						$result = 1;
					}else{
						$result = 0;
					}
				}else{
					$insert = $mysqli->query("INSERT INTO `tblbanner`(`id`, `eventName`, `isDefault`, `isActive`) VALUES ('',  '".strtoupper($banner)."', '1', '1') ");
					if($insert){
						$result = 1;
					}else{
						$result = 0;
					}
				}
			}
		}
		echo $result;
	}
?>