<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 5){
	
	
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

	<title>SABONG</title>

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
						<br/>	<?php echo $_SESSION['cname'] ?>
					</li>
					<div class="topbar-divider d-none d-sm-block"></div>
					<li class="nav-item dropdown no-arrow" style="text-align:center;">    
						<br/>
						<a class="dropdown-item" id = "changePassword">
							<i class="fa fa-lock mr-2 text-gray-400"></i>
							Change Password
						</a>
					</li>
					<div class="topbar-divider d-none d-sm-block"></div>
					<li class="nav-item dropdown no-arrow" style="text-align:center;">    
						<br/>
						<a class="dropdown-item" href="includes/logout.php">
							<i class="fas fa-sign-out-alt  mr-2 text-gray-400"></i>
							Logout
						</a>
					</li>

				</ul>
			</nav>
			<div class="container-fluid">
			  <!-- DataTales Example -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">SYSTEM SETUP MODULE</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<form class="user">
								<div class="form-group">
								  <input type="text" id="txtUsername" class="form-control form-control-user" style="font-size:15px; text-align:center;" aria-describedby="emailHelp" placeholder="Username">
								</div>
								<div class="form-group">
								  <input type="password" id="txtPassword" class="form-control form-control-user" style="font-size:20px; text-align:center;" placeholder="Password">
								</div>
								<input type='button' id = "btnLogin" value = "Login" class="btn btn-primary btn-user btn-block"/>
							</form>
						</div>
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
  
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});

		function caps(element){
			element.value = element.value.toUpperCase();
		}
		function reloadPage(){ 
			location.reload();
		}
		$(document).ready(function(){
			$("#btnAddAdminAccount").click(function(){
				$("#modal_addAdminAccount").modal("show");
			});
			
			$("#btnAddStaffAccount").click(function(){
				$("#modal_addStaffAccount").modal("show");
			});
			
			$(".actStaffReset").click(function(){
				
				accountIDVal = $(this).val();
				$.post("admin/actStaffReset.php", {accountID:accountIDVal}, function(res){

					if(res == 1){
						swal({
							title: "Account Password Reset Successfully!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							reloadPage();
						});
					}else{
						swal("unable to reset user account password! Refresh the page and try again!","","error");
					}
				});
			});
			
			$(".actStaffStatus").click(function(){
				accountIDVal = $(this).val();
				swal({
					title: "DEACTIVATE USER ACCOUNT",
					text: "Are you sure to deactivate this account?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: false
				 },
				 function(isConfirm){
					if (isConfirm){
						$.post("admin/actStaffStatus.php", {accountID:accountIDVal}, function(res){
							if(res == 1){
								swal({
									title: "Account Deactivated Successfully!",
									text: "",
									type: "success",
									confirmButtonClass: "btn-success",
									confirmButtonText: "OK",
									closeOnConfirm: true
								},
								function(){
									reloadPage();
								});	
							}else{
								swal("Unable to deactivate user account password! Refresh the page and try again!","","error");
							}
						});
					} else {
						swal("Cancelled", "Deactivation of user staff account has been cancelled!", "error");
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