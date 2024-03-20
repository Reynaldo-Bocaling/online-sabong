<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
	
	$query = $mysqli->query("SELECT a.`id`, a.`username`, a.`cname`, a.`roleID`,  b.`role`, c.betType  FROM `tblusers` a 
	LEFT JOIN `tblroles` b ON a.roleID = b.id
	LEFT JOIN `tblbettypes` c ON a.betTypeID = c.id
	WHERE a.isActive = '1' ORDER BY a.roleID ASC, a.username ASC");
	
	$qcs = $mysqli->query("SELECT * FROM `tblsystem`");
	$count = $qcs->num_rows;
	if($count > 0){
		while($rcs = $qcs->fetch_assoc()){
			$systemName = $rcs['systemName'];
			$systemLocation = $rcs['systemLocation'];
		}
	}
}else{
	header("location: ../index.php");
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

	<title><?php echo $_SESSION['systemName']; ?></title>

	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<script src="design/dist/sweetalert.js"></script>
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	 <!-- Custom styles for this page -->
	

</head>

<body id="page-top">
	<div id="wrapper">
		<div id="content-wrapper" class="d-flex flex-column">

			<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
				<!-- Topbar Navbar -->
				<ul class="navbar-nav ml-auto">		
					<li class="nav-item dropdown no-arrow mx-1" style="text-align:center;">    
						<br/>	<?php echo $_SESSION['cname']; ?>
					</li>
					<div class="topbar-divider d-none d-sm-block"></div>

					<!-- Nav Item - User Information -->
					<li class="nav-item dropdown no-arrow">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
							<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
								<i class="fa fa-star"></i><i class="fa fa-bars"></i><i class="fa fa-star"></i>
							</button>
						</a>
						<!-- Dropdown - User Information -->
						<?php
							include('includes/header.php');
						?>
					</li>
				</ul>
			</nav>
			<div class="container-fluid">
			  <!-- DataTales Example -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<?php
							echo '
							<a href="adminManageSystem.php" class=""><button class="btn btn-sm btn-primary" id = "btnAddStaffAccount"><i class="fas fa-book"></i> System Users</button></a>
							<a href="adminSystemSettings.php" class=""><button class="btn btn-lg btn-success" id = "btnAddFightControllerAccount"><i class="fas fa-book"> System Settings</i></button></a>';
						?>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-4">
								<div class="text-center">
									<h1 class="h4 text-gray-900 mb-4" style="font-weight:bold;">CHANGE SYSTEM REFRESH PASSWORD</h1>
								</div><br/>
								<form class="user">
										<div class="form-group">
										  <input type="password" id="txtRefreshOldPassword" class="form-control form-control-user" style="font-size:20px; font-weight:bold; text-align:center;" placeholder="SYSTEM REFRESH OLD PASSWORD">
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										<div class="form-group">
										  <input type="password" id="txtRefreshNewPassword" class="form-control form-control-user" style="font-size:20px; font-weight:bold; text-align:center;" placeholder="SYSTEM REFRESH NEW PASSWORD" >
										</div>
										<hr style="height:5px;border-width:0;color:gray;background-color:gray">
										<div class="form-group">
										  <input type="password" id="txtRefreshConfirmNewPassword" class="form-control form-control-user" style="font-size:20px; font-weight:bold; text-align:center;" placeholder="SYSTEM REFRESH CONFIRM NEW PASSWORD">
										</div>
										<input type='button' id = "btnUpdateRefreshPassword" value = "UPDATE REFRESH PASSWORD" class="btn btn-primary  btn-block" style="font-size:25px;"/>
								</form>
							</div>

							
							<div class="col-lg-1">
							</div>
							<div class="col-lg-3">
								<h1 class="h4 text-gray-900 mb-4" style="font-weight:bold;">PAYOUT PRINT SETTINGS</h1>
								<br/>
								<div class="row">
									<div class="col-md-12">
										<span>Print 2 Copies of Payout Receipt?</span>
										<p class="container">
										<?php
											$ppQuery = $mysqli->query("SELECT `systemPrint` FROM `tblsystem` ");
											if($ppQuery->num_rows > 0){
												$ppr = $ppQuery->fetch_assoc();
												$systemPrintVal = $ppr['systemPrint'];
												if($systemPrintVal == 1){
													echo '
													<label class="radio-inline">
													  <input type="radio" name="printSettingID" class="handleClick" value="1" checked> YES
													</label><br/>
													<label class="radio-inline">
														 <input type="radio"  name="printSettingID" class="handleClick" value="0" > NO
													</label>';
												}else{
													echo '
													<label class="radio-inline">
													  <input type="radio" name="printSettingID" class="handleClick" value="1"> YES
													</label><br/>
													<label class="radio-inline">
														 <input type="radio"  name="printSettingID" class="handleClick" value="0" checked> NO
													</label>';
												}
											}
										?>
										</p>
									</div>
								</div>
							</div>
							
						</div>
						<hr style="height:5px;border-width:0;color:gray;background-color:gray">
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- Bootstrap core JavaScript-->
  <script src="design/vendor/jquery/jquery.min.js"></script>
  <script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="design/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="design/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="design/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="design/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="design/js/demo/datatables-demo.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript">
		function caps(element){
			element.value = element.value.toUpperCase();
		}
		$(document).ready(function(){
			$("#btnUpdateRefreshPassword").click(function(){
				oldpass = $("#txtRefreshOldPassword").val();
				newpass = $("#txtRefreshNewPassword").val();
				conpass = $("#txtRefreshConfirmNewPassword").val();
				
				if(oldpass == ""){
					$("#txtRefreshOldPassword").focus();
					swal("Old Password is empty!","","error");
				}else if(newpass == ""){
					$("#txtRefreshNewPassword").focus();
					swal("New Password is empty!","","error");
				}else if(conpass == ""){
					$("#txtRefreshConfirmNewPassword").focus();
					swal("Confirm New Password is empty!","","error");
				}else if(newpass != conpass){
					$("#txtRefreshNewPassword").focus();
					swal("Passwords did not match!", "", "error");
				}else{
					showModal();
					$("#loader").show();
					$.post("admin/updateRefreshPassword.php", {oldPassword:oldpass, newPassword:newpass}, function(res){
						hideModal();
						$("#loader").hide();
						if(res == 1){
							swal({
								html: true,
								title: "Sucessfully Updated the System Refresh Password!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});	
						}else if(res == 2){
							$("#txtRefreshOldPassword").focus();
							swal("The system refresh old password is invalid!","","error");
						}else{
							$("#txtRefreshOldPassword").focus();
							swal("Unable to system refresh Password! Please contact system developer for assistance!","","error");
						}
					});
				}
			});
			
			$(".handleClick").click(function(){
				printSettingIDVal = $(this).val();			
				$.post("admin/savePayoutPrintSettings.php",{printsettingsID:printSettingIDVal}, function(res){
					if(res == 1){
						swal({
							html: true,
							title: "Successfully Updated the Payout Print Settings!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});	
					}else{
						swal("Unable to change the Payout Print Settings. Please refresh the page and try again.","", "error");	
					}
				});
			});
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>