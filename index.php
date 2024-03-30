<?php
session_start();
require 'main/includes/connection.php';
$qcs = $mysqli->query("CALL checkSystem(); ");
$count = $qcs->num_rows;
if ($count > 0) {
    while ($rcs = $qcs->fetch_assoc()) {
        $systemName = $rcs['systemName'];
        $systemLocation = $rcs['systemLocation'];
        $systemAdministrator = $rcs['systemAdministrator'];
        $tellerWala = $rcs['tellerWala'];
        $tellerMeron = $rcs['tellerMeron'];

        if (($systemName == "") || ($systemLocation == "") || ($systemAdministrator == 0) || ($tellerWala == 0) || ($tellerMeron == 0)) {
            header('location: systemMessage.php');
        } else {
            $_SESSION['systemName'] = $systemName;
            $_SESSION['systemLocation'] = $systemLocation;
            $_SESSION['systemAdministrator'] = $systemAdministrator;
            $_SESSION['tellerWala'] = $tellerWala;
            $_SESSION['tellerMeron'] = $tellerMeron;
        }
    }
} else {
    header('location: systemMessage.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<script src="https://cdn.tailwindcss.com"></script>
	<title>
		<!-- <?php
if ($systemName != "") {
    echo $systemName;
} else {
    echo "Sabong";
}
?> -->
	</title>
	<link href="main/design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="main/design/dist/sweetalert.css">
	<link href="main/design/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="main/assets/styles/main.css" rel="stylesheet">
	<link href="main/assets/styles/login.css" rel="stylesheet">
</head>
<body class="login_background relative flex flex-col h-screen z-10 overflow-x-hidden">
	<div class="mx-auto mt-4 max-w-[80%] w-full flex items-start justify-between">
		<div class=" text-xl text-black font-bold tracking-tight">
			<span class="text-2xl text-red-600">$</span>ABONG
		</div>
		<div class="flex flex-col md:flex-row md:items-center gap-4">
			<span class=" text-sm text-black font-medium"><?php echo $systemName ?></span>
			<div class="flex items-center gap-3"><img src="./main/assets/images/facebook.png" class="w-[20px]" />
			<img src="./main/assets/images/gmail.png" class="w-[20px]" /></div>
		</div>
	</div>

	<div>
<div class="testtt container flex flex-col md:flex-row items-center justify-around bg-red-5d00 w-full mt-5 overflow-y-auto overflow-x-hidden  px-6">
		<div class="w-[370px] relative z-10 ml-6">
			<div>
				<h1 class="text-black text-xl font-extrabold ">Welcome Back!</h1>
				<small class="text-sm text-gray-600 tracking-wide" >Login to continue</small>
			</div>
			<form class="user my-4 ">
				<div class="form-group pr-4">
					<input type="text" id="txtMobileUser" class="text-sm form-control fieldChecker rounded-xl py-6 shadow-md" placeholder="Mobile Number / Username"  maxlength="32" onpaste="return false;" onCopy="return false" onCut ="return false" onDrag="return false" onDrop="return false" >
				</div>
				<div class="form-group pr-4">
					<input type="password" id="txtPassword" class="text-sm form-control rounded-xl py-6  shadow-md" onpaste="return false;" onCopy="return false" onCut ="return false" onDrag="return false" onDrop="return false"  placeholder="Password">
				</div>
				<div class="flex items-center gap-5 mt-6">
					<input type='button' id = "btnLogin" value = "Login" class="text-sm text-white font-medium rounded-xl py-[12px] bg-gradient-to-r from-violet-600 to-indigo-600 w-1/2"/>

					<a href="register.php" class="w-1/2">
						 Create an Account!
					</a>
				</div>
			</form>
			<p class="text-sm text-gray-800 font-normall tracking-wider mt-5">
				Experience the trill of online cockfighting from the comfort of your home, Bet, watch, and win with our exciting platform
			</p>
		</div>
		<div class=" relative z-10">
			<img src='./main/assets/images/login_model.png' class=" testt w-[420px] mt-10 z-50"/>
		</div>

		<!-- floating icon -->
		<img src='./main/assets/images/transparent_loginImg.png' class="absolute -top-16 left-7  testt w-[520px] opacity-5 z-"/>


	</div>
</div>

	<script src="main/design/vendor/jquery/jquery.min.js"></script>
	<script src="main/design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="main/design/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="main/design/js/sb-admin-2.min.js"></script>
	<script type="text/javascript" src="main/design/dist/sweetalert.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#txtMobileUser").focus();
			input = document.getElementById("txtPassword");
			// Execute a function when the user releases a key on the keyboard
			input.addEventListener("keyup", function(event) {
			  // Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
				// Cancel the default action, if needed
					event.preventDefault();
				// Trigger the button element with a click
					document.getElementById("btnLogin").click();
				}
			});

			$('.fieldChecker').keypress(function (e) {
				var regex = new RegExp("^[a-zA-Z0-9]+$");
				var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
				if (regex.test(str)) {
					return true;
				}

				e.preventDefault();
				return false;
			});

			$("#btnLogin").click(function(){
				mobileNum = $("#txtMobileUser").val();
				pass = $("#txtPassword").val();
				if(mobileNum == ""){
					$("#txtMobileUser").focus();
					swal("Mobile Number is empty!","","error");
				}else if(pass == ""){
					$("#txtPassword").focus();
					swal("Password is empty!","","error");
				}else{
					showModal();
					$("#loader").show();
					$.post("checkLogin.php", {mobileUser:mobileNum, password:pass}, function(res){
						hideModal();
						$("#loader").hide();
						if(res == 1){
							document.location.href='main/index.php';
						}else if (res == 2){
							document.location.href='redirect.php';
						}else if(res == 6){
							$("#txtMobileUser").val("").focus();
							$("#txtPassword").val("");
							swal("System Access is not yet available at this time!","","error");
						}else if(res == 8){
							$("#txtMobileUser").val("").focus();
							$("#txtPassword").val("");
							swal("Error! No event for today. Access to system is limited.","","error");
						}else if(res == 10){
							$("#txtMobileUser").val("").focus();
							$("#txtPassword").val("");
							swal("ERROR","","error");
						}else{
							$("#txtMobileUser").val("").focus();
							$("#txtPassword").val("");
							swal("Login Credentials is Invalid!","","error");
						}
					});
				}
			});
		});
	</script>
	<?php
include "modalboxes.php";
?>
</body>
</html>
