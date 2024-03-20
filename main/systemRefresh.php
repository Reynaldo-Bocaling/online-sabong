<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 9){
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

	<!-- Custom fonts for this template-->
	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<script src="design/dist/sweetalert.js"></script>

	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
		<div id="content">

        <!-- Topbar -->
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
        <div class="container">
			<div class="row">
				
				<div class="col-xl-12 col-md-6 mb-4">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">SYSTEM REFRESH FORM</h6>
						</div>
						<div class="card-body">
							<div class="form-group">
							  <input type="password" id="txtPassword" class="form-control form-control-user fieldChecker" style="font-size:15px; text-align:center;" placeholder="ENTER REFRESH PASSWORD HERE...">
							</div>
							<hr style="height:5px;border-width:0;color:gray;background-color:gray">
							<input type='button' id = "btnRefresh" value = "REFRESH NOW" class="btn btn-primary  btn-block" style="font-size:25px;"/>
						</div>
					</div>
				</div>
				
			</div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->
  <!-- Bootstrap core JavaScript-->
  <script src="design/vendor/jquery/jquery.min.js"></script>
  <script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="design/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="design/js/sb-admin-2.min.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#txtPassword").focus();
			input = document.getElementById("txtPassword");
			// Execute a function when the user releases a key on the keyboard
			input.addEventListener("keyup", function(event) {
			  // Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
				// Cancel the default action, if needed
					event.preventDefault();
				// Trigger the button element with a click
					document.getElementById("btnRefresh").click();
				}
			});
			$("#btnRefresh").click(function(){
				pass = $("#txtPassword").val();
				if(pass == ""){
					$("#txtPassword").focus();
					swal("Input refresh Password","","error");
				}else{
					showModal();
					$("#loader").show();
					$.post("setup/saveRefresh.php", {refreshPassword:pass}, function(res){
						hideModal();
						$("#loader").hide();
						if(res == 1){
							swal({
								title: "System will Refresh!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								document.location.href="includes/logout.php";		
							});
						}else if(res == 2){
							$("#txtPassword").focus();
							swal("Invalid System Refresh Password","","error");
						}else if(res == 5){
							swal({
								title: "ERROR! MOBILE ACCOUNT HAS REMAINING BALANCE",
								text: "Are you sure to refresh the system?",
								type: "warning",
								showCancelButton: true,
								confirmButtonColor: '#DD6B55',
								confirmButtonText: 'Yes, I am sure!',
								cancelButtonText: "No, cancel it!",
								closeOnConfirm: false,
								closeOnCancel: true
							 },
							 function(isConfirm){

							   if (isConfirm){
									$.post("setup/saveRefreshAgain.php", {refreshPassword:pass}, function(res){
										hideModal();
										$("#loader").hide();
										if(res == 1){
											swal({
												title: "System will Refresh!",
												text: "",
												type: "success",
												confirmButtonClass: "btn-success",
												confirmButtonText: "OK",
												closeOnConfirm: true
											},
											function(){
												document.location.href="includes/logout.php";		
											});
										}else if(res == 2){
											$("#txtPassword").focus();
											swal("Invalid System Refresh Password","","error");
										}
									});
								}else {
									$("#txtPassword").focus().val("");	
								}
							 });
						}else{
							swal("Unable to refresh the system! Please contact system developer!","","error");
						}
					});
					
					
				}
			});
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>
	
	
	
<?php
}else{
	header("location: ../index.php");
}
?>
