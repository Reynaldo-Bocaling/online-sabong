<?php	
error_reporting(0);
	$DB_HOST = 'localhost'; // database host
	$DB_USER = 'root'; // database user
	$DB_PASS = 'admin071720'; // database password
	$DB_NAME = 'dbmendez'; // database name
	
	$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, 3306);
	
	if(mysqli_connect_errno()){
		printf("<center>Unable to login at the moment. Please Contact System Developer. Thank you<center>"); 
		exit();
	}else{
		
	}
?>