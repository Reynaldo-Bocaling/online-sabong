<?php
	session_start();
	include("../includes/connection.php");
	include("../includes/functions.php");

	// M A I N
	if(isset($_REQUEST['promoter'])){
		$promoter = sanitize($_REQUEST['promoter'], $mysqli);
		
		if($promoter == "" || strlen($promoter)< 3){
			$result = 3 ;
		}else{
			$query = $mysqli->query("SELECT * FROM `tblpromoters` WHERE promoterName = '".$promoter."' AND isActive = '1' LIMIT 1");
			
			if($query->num_rows > 0){
				$result = 2;	
			}else{
				$queryD = $mysqli->query("SELECT * FROM `tblpromoters` WHERE isActive = '1' AND isDefault = '1' LIMIT 1 ");
				if($queryD->num_rows > 0 ){
					$insert = $mysqli->query("INSERT INTO `tblpromoters`(`id`, `promoterName`, `isDefault`, `isActive`) VALUES ('',  '".strtoupper($promoter)."', '0', '1') ");
					if($insert){
						$result = 1;
					}else{
						$result = 0;
					}
				}else{
					$insert = $mysqli->query("INSERT INTO `tblpromoters`(`id`, `promoterName`, `isDefault`, `isActive`) VALUES ('',  '".strtoupper($promoter)."', '1', '1') ");
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