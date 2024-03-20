<?php
	session_start();
	require('main/includes/connection.php');
	
	if(isset($_REQUEST['systemName'])){
		$result = 0;
		$systemName = mysqli_real_escape_string($mysqli, $_REQUEST['systemName']);
		$systemLocation = mysqli_real_escape_string($mysqli, $_REQUEST['systemLocation']);
		$f3 = mysqli_real_escape_string($mysqli, $_REQUEST['adminFullname']);
		$f4 = mysqli_real_escape_string($mysqli, $_REQUEST['adminUsername']);
		$f5 = mysqli_real_escape_string($mysqli, $_REQUEST['adminPassword']);
		$f6 = mysqli_real_escape_string($mysqli, $_REQUEST['walaFullname']);
		$f7 = mysqli_real_escape_string($mysqli, $_REQUEST['walaUsername']);
		$f8 = mysqli_real_escape_string($mysqli, $_REQUEST['walaPassword']);
		$f9 = mysqli_real_escape_string($mysqli, $_REQUEST['meronFullname']);
		$f10 = mysqli_real_escape_string($mysqli, $_REQUEST['meronUsername']);
		$f11 = mysqli_real_escape_string($mysqli, $_REQUEST['meronPassword']);
		$f12 = mysqli_real_escape_string($mysqli, $_REQUEST['controllerFullname']);
		$f13 = mysqli_real_escape_string($mysqli, $_REQUEST['controllerUsername']);
		$f14 = mysqli_real_escape_string($mysqli, $_REQUEST['controllerPassword']);
		$password = mysqli_real_escape_string($mysqli, $_REQUEST['password']);
		
		
		
		
		if($f3 == "" AND $f4 == "" AND $f5 == ""){
			$systemAdministrator = 0;
		}else{
			$systemAdministrator = 1;
		}
		
		if($f6 == "" AND $f7 == "" AND $f8 == ""){
			$tellerWala = 0;
		}else{
			$tellerWala = 1;
		}
		
		if($f9 == "" AND $f10 == "" AND $f11 == ""){
			$tellerMeron = 0;
		}else{
			$tellerMeron = 1;
		}
		
		if($f12 == "" AND $f13 == "" AND $f14 == ""){
			$fightController = 0;
		}else{
			$fightController = 1;
		}
		
		$query = $mysqli->query("SELECT `password` FROM `tblusers` WHERE roleID = '5' AND isActive = '1' ");
		if($query->num_rows > 0){
			$row = $query->fetch_assoc();
			
			$dbpassword = $row['password'];
			
			if($dbpassword === md5($password)){
				$insert = $mysqli->query("INSERT INTO `tblsystem`(`id`, `systemName`, `systemLocation`, `systemAdministrator`, `tellerWala`, `tellerMeron`, `fightController`) VALUES ('', '".STRTOUPPER($systemName)."', '".STRTOUPPER($systemLocation)."', '".$systemAdministrator."', '".$tellerWala."', '".$tellerMeron."', '".$fightController."' )");
		
				if($insert){
					$insertUser = $mysqli->query("INSERT INTO `tblusers`(`id`, `username`, `password`, `cname`, `roleID`, `isActive`, `betTypeID`) 
					VALUES ('', '".$f4."', '".md5($f5)."', '".STRTOUPPER($f3)."', '1', '1', '0') "); 
					
					$insertUser2 = $mysqli->query("INSERT INTO `tblusers`(`id`, `username`, `password`, `cname`, `roleID`, `isActive`, `betTypeID`) 
					VALUES ('', '".$f7."', '".md5($f8)."', '".STRTOUPPER($f6)."', '2', '1', '2') ");

					$insertUser3 = $mysqli->query("INSERT INTO `tblusers`(`id`, `username`, `password`, `cname`, `roleID`, `isActive`, `betTypeID`) 
					VALUES ('', '".$f10."', '".md5($f11)."', '".STRTOUPPER($f9)."', '2', '1', '1') "); 
						
					$insertUser4 = $mysqli->query("INSERT INTO `tblusers`(`id`, `username`, `password`, `cname`, `roleID`, `isActive`, `betTypeID`) 
					VALUES ('', '".$f13."', '".md5($f14)."', '".STRTOUPPER($f12)."', '6', '1', '0') "); 
					$result = 1;
				}
				
			}else{
				$result = 2; // invalid setup password
			}
		}
		echo $result;
	}
?>






