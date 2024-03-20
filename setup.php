<?php
	session_start();
	require('main/includes/connection.php');	
	$qcs = $mysqli->query("CALL checkSystem(); ");
		$_SESSION['systemName'] = "";
		$_SESSION['systemLocation'] = "";
		$_SESSION['systemAdministrator'] = "";
		$_SESSION['tellerWala'] = "";	
		$_SESSION['tellerMeron'] = "";
	if($qcs->num_rows > 0){
		$rcs = $qcs->fetch_assoc();
		
		$systemName = $rcs['systemName'];
		$systemLocation = $rcs['systemLocation'];
		$systemAdministrator = $rcs['systemLocation'];
		$tellerWala = $rcs['tellerWala'];
		$tellerMeron = $rcs['tellerMeron'];
		
		if($systemName == "" AND $systemLocation == "" AND $systemAdministrator== 0 AND $tellerWala == 0 AND $tellerMeron == 0){
			
		}else{
			header('location: index.php');
		}
	}else{
		
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
	<title>SYSTEM SETUP</title>

  <!-- Custom fonts for this template-->
  <link href="main/design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link rel="stylesheet" type="text/css" href="main/design/dist/sweetalert.css">
  <link href="main/design/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

	<div class="container">
    <!-- Outer Row -->
		<div class="row justify-content-center">

			<div class="col-xl-10 col-lg-12 col-md-9">

				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-8">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4" style="font-weight:bold;">
											SYSTEM SETUP INFORATION
											
										</h1>
									</div>
									<form class="user">
										<div class="form-group">
										  <input type="text" id="txtSystemName" class="form-control form-control-user" style="font-size:15px; text-align:center;" onKeyUp="caps(this);" placeholder="SYSTEM NAME HERE">
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										<div class="form-group">
										  <input type="text" id="txtSystemLocation" class="form-control form-control-user" style="font-size:15px; text-align:center;" onKeyUp="caps(this);" placeholder="SYSTEM LOCATION">
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										<div class="form-group">
										  <input type="text" id="txtAdministratorFullname" class="form-control form-control-user" style="font-size:15px; text-align:center;" onKeyUp="caps(this);" placeholder="ADMINISTRATOR'S FULLNAME">
										</div>
										<div class="form-group">
										  <input type="text" id="txtAdministratorUsername" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" maxlength="20" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="USERNAME">
										</div>
										<div class="form-group">
										  <input type="password" id="txtAdministratorPassword" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" placeholder="PASSWORD">
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										
										<div class="form-group">
										  <input type="text" id="txtTellerWalaFullname" class="form-control form-control-user" style="font-size:15px; text-align:center;" onKeyUp="caps(this);" placeholder="TELLER FOR WALA FULLNAME">
										</div>
										<div class="form-group">
										  <input type="text" id="txtTellerWalaUsername" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" maxlength="20" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="TELLER FOR WALA USERNAME">
										</div>
										<div class="form-group">
										  <input type="password" id="txtTellerWalaPassword" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;"  placeholder="TELE FOR WALA PASSWORD">
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										
										<div class="form-group">
										  <input type="text" id="txtTellerMeronFullname" class="form-control form-control-user" style="font-size:15px; text-align:center;" onKeyUp="caps(this);" placeholder="TELLER FOR MERON FULLNAME">
										</div>
										<div class="form-group">
										  <input type="text" id="txtTellerMeronUsername" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" maxlength="20" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="TELLER FOR MERON USERNAME">
										</div>
										<div class="form-group">
										  <input type="password" id="txtTellerMeronPassword" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" placeholder="TELLER FOR MERON PASSWORD">
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										
										<div class="form-group">
										  <input type="text" id="txtFightControllerFullname" class="form-control form-control-user" style="font-size:15px; text-align:center;" onKeyUp="caps(this);" placeholder="FIGHT CONTROLLER FULLNAME">
										</div>
										<div class="form-group">
										  <input type="text" id="txtFightControllerUsername" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" maxlength="20" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="FIGHT CONTROLLER USERNAME">
										</div>
										<div class="form-group">
										  <input type="password" id="txtFightControllerPassword" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" placeholder="FIGHT CONTROLLER PASSWORD">
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										
										<div class="form-group">
										  <input type="password" id="txtPassword" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" placeholder="SET UP PAGE PASSWORD!">
										</div>
										<input type='button' id = "btnLogin" value = "SAVE SETUP" class="btn btn-primary  btn-block" style="font-size:25px;"/>
									</form>
									<hr>

								</div>
							</div>
							<div class="col-lg-2"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

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
			$('.fieldChecker').keypress(function (e) {
				var regex = new RegExp("^[a-zA-Z0-9]+$");
				var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
				if (regex.test(str)) {
					return true;
				}

				e.preventDefault();
				return false;
			});
			
			$("#txtSystemName").focus();
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
			
			$("#btnLogin").click(function(){
				f1 = $("#txtSystemName").val();
				f2 = $("#txtSystemLocation").val();
				f3 = $("#txtAdministratorFullname").val();
				f4 = $("#txtAdministratorUsername").val();
				f5 = $("#txtAdministratorPassword").val();
				
				f6 = $("#txtTellerWalaFullname").val();
				f7 = $("#txtTellerWalaUsername").val();
				f8 = $("#txtTellerWalaPassword").val();
				
				f9 = $("#txtTellerMeronFullname").val();
				f10 = $("#txtTellerMeronUsername").val();
				f11 = $("#txtTellerMeronPassword").val();
				
				f12 = $("#txtFightControllerFullname").val();
				f13 = $("#txtFightControllerUsername").val();
				f14 = $("#txtFightControllerPassword").val();
				
				pass = $("#txtPassword").val();
				
				if(f1 == ""){
					$("#txtSystemName").focus();
					swal("System Name is empty!","","error");
				}else if(f2 == ""){
					$("#txtSystemLocation").focus();
					swal(" System Location is empty!","","error");
				}else if(f3 == ""){
					$("#txtAdministratorFullname").focus();
					swal("Administrator's fullname is empty!","","error");
				}else if(f4 == ""){
					$("#txtAdministratorUsername").focus();
					swal("Administrator username is empty!","","error");
				}else if(f5 == ""){
					$("#txtAdministratorPassword").focus();
					swal("Administrator password is empty!","","error");
				}else if(f6 == ""){
					$("#txtTellerWalaFullname").focus();
					swal("Teller for Wala fullname is empty!","","error");
				}else if(f7 == ""){
					$("#txtTellerWalaUsername").focus();
					swal("Teller for Wala username is empty!","","error");
				}else if(f8 == ""){
					$("#txtTellerWalaPassword").focus();
					swal("Teller for Wala password is empty!","","error");
				}else if(f9 == ""){
					$("#txtTellerMeronFullname").focus();
					swal("Teller for Meron fullname is empty!","","error");
				}else if(f10 == ""){
					$("#txtTellerMeronUsername").focus();
					swal("Teller for Meron username  is empty!","","error");
				}else if(f11 == ""){
					$("#txtTellerMeronPassword").focus();
					swal("Teller for Meron password is empty!","","error");
				}else if(f12 == ""){
					$("#txtFightControllerFullname").focus();
					swal("Fight Controller Fullname is empty!","","error");
				}else if(f13 == ""){
					$("#txtFightControllerUsername").focus();
					swal("Fight Controller Username is empty!","","error");
				}else if(f14 == ""){
					$("#txtFightControllerPassword").focus();
					swal("Fight Controller Password is empty!","","error");
				}else if(pass == ""){
					$("#txtPassword").focus();
					swal("Password is empty!","","error");
				}else{
					showModal();
					$("#loader").show();
					$.post("saveSetup.php", {systemName:f1, systemLocation:f2, adminFullname:f3, adminUsername:f4, adminPassword:f5, walaFullname:f6, walaUsername:f7, walaPassword:f8, meronFullname:f9, meronUsername:f10, meronPassword:f11, controllerFullname:f12, controllerUsername:f13, controllerPassword:f14, password:pass}, function(res){
						hideModal();
						$("#loader").hide();
						if(res == 1){
							swal({
								html: true,
								title: "System Setup Completed Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								window.location.replace("index.php");
							});	
						}else if(res == 2){
							$("#txtPassword").focus();
							swal("Invalid System Setup Password! Please contact system developer for assistance!","","error");
						}else{
							$("#txtSystemName").focus();
							swal("Unable to complete system setup! Please contact system developer for assistance!","","error");
						}
					});
				}
			});
		});
		</script>
		<?php
			include("modalboxes.php");
		?>

</body>

</html>
