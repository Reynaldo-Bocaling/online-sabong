<?php
session_start();
require('../includes/connection.php');
if(!$_SESSION['roleID'] == 1 || !$_SESSION['roleID'] == 4){
	header('location: ../../index.php');
}else{	
	if(isset($_POST['eventID'])){
		$eventID = $_POST['eventID'];
	
	$q = $mysqli->query("SELECT * FROM `tblusers` WHERE roleID = '2' AND isActive = '1' AND NOT IN (SELECT * FROM `tbltellercashin` WHERE eventID = '".$eventID."')");
	
	if($q->num_rows > 0){
		while($r = $q->fetch_assoc()){
			
		}
	}

	}
}
?>