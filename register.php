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


  <title><?php echo $_SESSION['systemName']; ?></title>

  <!-- Custom fonts for this template-->
  <link href="main/design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


  <!-- Custom styles for this template-->
  <link rel="stylesheet" type="text/css" href="main/design/dist/sweetalert.css">
  <link href="main/design/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="main/assets/styles/register.css" rel="stylesheet">

</head>

<body class="regitration_container relative flex items-center justify-center h-screen z-10 overflow-hidden">




<div class="absolute top-2 max-w-[80%] w-full flex items-center justify-between">
		<div class=" text-xl text-black font-bold tracking-tight">
			<span class="text-2xl text-red-600">$</span>ABONG
		</div>
		<div class="flex items-center gap-4">
			<span class=" text-sm text-white font-medium"><?php echo $systemName ?></span>
			<img src="./main/assets/images/facebook.png" class="w-[20px]" />
			<img src="./main/assets/images/gmail.png" class="w-[20px]" />
		</div>
	</div>

	<div class="testtt container flex items-center justify-start bg-red- w-full pl-24 mt-5">
		<div class="w-[400px] relative z-10 -ml-8">
			<div>
				<h1 class="text-black text-xl font-extrabold ">Welcome Back!</h1>
				<small class="text-sm text-gray-600 tracking-wide" >Registration form</small>
			</div>
			<form class="user my-4 ">
				<div class="form-group pr-4">
					<input type="text" id="addFirstname" class="text-sm  form-control fieldChecker rounded-xl py-6 shadow-md" onKeyUp="caps(this);" placeholder="First Name"  >
				</div>
				<div class="form-group pr-4">
					<input type="text" id="addLastname" class="text-sm  form-control fieldChecker rounded-xl py-6 shadow-md" onKeyUp="caps(this);" placeholder="Lastname"  >
				</div>
				<div class="form-group pr-4">
					<input type="text" id="addMobileNum" class="text-sm  form-control fieldChecker rounded-xl py-6 shadow-md" placeholder="Mobile Number / Username"  >
				</div>

				<div class="form-group pr-4 flex gap-3">
					<input type="password" id="addPassword" class="text-sm  form-control rounded-xl py-6  shadow-md"  placeholder="Password">
					<input type="password" id="addRepeatPassword" class="text-sm  form-control rounded-xl py-6  shadow-md"  placeholder="Password">
				</div>
				<div class="flex items-center gap-3 mt-6">
					<input type='button' id = "btnRegister" value = "Register" class="text-sm text-white font-medium rounded-xl py-[12px] bg-gradient-to-r from-violet-600 to-indigo-600 w-1/3"/>

					<div class="w-1/1 text-sm flex items-center">
					<p>Already have an account?</p>
					</div>
					<a href="index.php" class="text-blue-500 font-medium -ml-2">
						  Login
					</a>
				</div>
			</form>

		</div>
		<div class=" relative z-10">








  <!-- Bootstrap core JavaScript-->
  <script src="main/design/vendor/jquery/jquery.min.js"></script>
  <script src="main/design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="main/design/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
	<script src="main/design/js/sb-admin-2.min.js"></script>
	<script type="text/javascript" src="main/design/dist/sweetalert.js"></script>
	<!-- inline scripts related to this page -->
	<script type="text/javascript">
		function caps(element){
			element.value = element.value.toUpperCase();
		}
		$(document).ready(function(){
			$("#addFirstname").focus();
			var input = document.getElementById("addRepeatPassword");

			// Execute a function when the user releases a key on the keyboard
			input.addEventListener("keyup", function(event) {
			  // Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
				// Cancel the default action, if needed
					event.preventDefault();
				// Trigger the button element with a click
					document.getElementById("btnRegister").click();
				}
			});
			$("#btnRegister").click(function(){
				fname = $("#addFirstname").val();
				lname = $("#addLastname").val();
				mobileNum = $("#addMobileNum").val();
				pass = $("#addPassword").val();
				repeatpass = $("#addRepeatPassword").val();
				if(fname == ""){
					$("#addFirstname").focus();
					swal("Firstname is empty!","","error");
				}else if(lname == ""){
					$("#addLastname").focus();
					swal("Lastname is requried!","","error");
				}else if(mobileNum == ""){
					$("#addMobileNum").focus();
					swal("Mobile Number is required!","","error");
				}else if(pass == ""){
					$("#addPassword").focus();
					swal("Password is empty!","","error");
				}else if(repeatpass == ""){
					$("#addRepeatPassword").focus();
					swal("Repeat Password must not be blank!","","error");
				}else if (pass != repeatpass){
					$("#addPassword").focus();
					swal("Password and repeat password is not the same!","","error");
				}else{
					showModal();
					$("#loader").show();
					$.post("main/registration/saveRegisterClient.php", {firstname:fname, lastname:lname, mobilenumber:mobileNum, password:pass, repeatpassword:repeatpass}, function(a){
						hideModal();
						$("#loader").hide();
						if(a == 1){
							swal({
								html: true,
								title: "REGISTERED SUCCESSFULLY! LOGIN YOUR ACCOUNT NOW!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								document.location.href='index.php';
							});
						}else if(a == 2){
							swal({
								html: true,
								title: "MOBILE NUMBER IS NOT AVAILABLE! AN ACCOUNT IS ALREADY REGISTERED USING THIS MOBILE NUMBER!",
								text: "",
								type: "error",
								confirmButtonClass: "btn-danger",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
						}else{
							swal({
								html: true,
								title: "UNABLE TO REGISTER AN ACCOUNT! CONTACT OUR STAFF FOR ASSISTANCE!",
								text: "",
								type: "error",
								confirmButtonClass: "btn-danger",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
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
