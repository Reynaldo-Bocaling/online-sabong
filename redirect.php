-<?php
	session_start();
	require('main/includes/connection.php');
	
	if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 ){ // for administrator
		header("location: main/administrator.php");
	}else if($_SESSION['roleID'] == 2){ // for staff
		header("location: main/staffBets.php");	
	}else if($_SESSION['roleID'] == 6){ // for staff
		header("location: main/fightController.php");	
	}else if($_SESSION['roleID'] == 7){ // for staff
		header("location: main/betCollector.php");	
		$_SESSION['staffFor'] = "4";
	}else if($_SESSION['roleID'] == 8 ){
		header("location: main/dashboard.php");	
	}else if($_SESSION['roleID'] == 9 ){
		header("location: main/systemRefresh.php");	
	}else if($_SESSION['roleID'] == 10 ){
		header("location: main/adminTicketCancellation.php");	
	}else if($_SESSION['roleID'] == 12 ){
		header("location: main/adminReportsManagement.php");	
	}else if($_SESSION['roleID'] == 13 ){
		header("location: main/cashhandler.php");	
	}
?>






