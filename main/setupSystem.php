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
	 <script src="https://cdn.tailwindcss.com"></script>
	  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

</head>

<body id="page-top">
	
<style>

::-webkit-scrollbar {
  width: 0;
}
#userDropdown, #btnRefreshPage{
	outline:none
}
</style>

<div id="content" class="flex-1 flex flex-col overflow-hidden gap-2 bg-white ">
	<header class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7 ">
		
		<div class="text-base font-mdium text-gray-700 flex items-center gap-2 ">
			<p class=" md:flex md:gap-2">Welcome, <span class="capitalize text-black font-semibold"><?php echo $_SESSION['username']; ?></span></p>
			<img src="./assets/images/waving.png" class="w-[50px]  md:flex" />
		</div>
		
		<button class="md:outline-none noneOutlineBtn" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class='bx bx-menu-alt-right text-3xl' ></i>
		</button>
		<!-- Dropdown - User Information -->
		<div class="dropdown-menu dropdown-menu-right shadow mt-2" aria-labelledby="userDropdown">
			<a class="dropdown-item" id = "changePassword">
				<i class="fa fa-lock mr-2 text-gray-400"></i>
				Change Password
			</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="includes/logout.php">
				<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
				Logout
			</a>
		</div>

	</header>




	<main class="flex-1 overflow-x-auto overflow-y-auto p-3 h-[90vh] w-screen">
		<div class="flex items-center justify-between mr-4">
			<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
			<p>Setup/ </p>
			<span class="font-semibold text-blue-500">System-Module </span>
			</div>
		</div>
		
	
		<form class="mt-14 max-w-[400px] w-full py-12 px-6 rounded-xl bg-white border mx-auto flex flex-col gap-3">
			<p class="text-xl text-black font-bold mb-3">System Setup Module</p>
			<input type="text" id="txtUsername" placeholder="Username" aria-describedby="emailHelp" class="text-sm text-black  w-full rounded-xl form-control bg-white py-6"  />
			<input type="password" id="txtPassword" placeholder="Password" class="text-sm text-black  w-full rounded-xl form-control bg-white py-6" />
			<input type="button"  id = "btnLogin" value = "Login" class="text-base btn-user btn-block text-white  font-semibold  w-full h-12 rounded-full tracking-wide form-control bg-blue-500" />
		</form>
		
	</main>
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