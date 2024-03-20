<?php
	session_start();
	include("../includes/connection.php");
	include("../includes/functions.php");

	// M A I N
	if(isset($_REQUEST['promoterID'])){
		$promoterID = sanitize($_REQUEST['promoterID'], $mysqli);

		$query = $mysqli->query("SELECT * FROM `tblpromoters` WHERE id = '".$promoterID."' ");
		
		if($query->num_rows > 0){
			$update = $mysqli->query("UPDATE `tblpromoters` SET isDefault = '0' WHERE id > 0");
			if($update){
				$update1 = $mysqli->query("UPDATE `tblpromoters` SET `isDefault` = '1' WHERE id = '".$promoterID."' ");
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