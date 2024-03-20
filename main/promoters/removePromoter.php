<?php
	session_start();
	include("../includes/connection.php");
	include("../includes/functions.php");

	// M A I N
	if(isset($_REQUEST['promoterID'])){
		$promoterID = sanitize($_REQUEST['promoterID'], $mysqli);

		$query = $mysqli->query("SELECT * FROM `tblpromoters` WHERE id = '".$promoterID."' AND isActive = '1' ");
		
		if($query->num_rows > 0){
			$remove = $mysqli->query("UPDATE `tblpromoters` SET isActive = '0', `isDefault` = '0' WHERE id = '".$promoterID."' ");
			
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