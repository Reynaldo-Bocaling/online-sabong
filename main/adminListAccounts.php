<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
	
	$qaccounts = $mysqli->query("SELECT * FROM `tblaccounts` ORDER BY lastname ASC, firstname ASC, balance DESC ");
	
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

	<!-- Custom fonts for this template-->
	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<script src="design/dist/sweetalert.js"></script>

	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	 <!-- Custom styles for this page -->
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
						<h6 class="m-0 font-weight-bold text-primary">Accounts</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<input type = "hidden" id = "hiddenMobileNumber" />
							<input type = "hidden" id = "hiddenAccountID" />
							<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th style="text-align:center;">#</th>
										<th style="text-align:center;">Mobile Number</th>
										<th style="text-align:center;">Name</th>
										<th style="text-align:right;">Balance</th>
										<th style="text-align:center;">Actions</th>
									</tr>
								</thead>
								<tbody>
								<?php
					   
							   $qaccounts = $mysqli->query("SELECT `id`, `mobileNumber`, `firstname`, `lastname`, `balance` FROM `tblaccounts` ");
							   if($qaccounts->num_rows > 0){
								   $count = 1;
								   while($raccounts = $qaccounts->fetch_assoc()){
									  echo '
									 <tr>
										<td style="text-align:center;">'.$count.'</td>
										<td style="text-align:center;">'.$raccounts['mobileNumber'].'</td>
										<td style="text-align:center;">'.$raccounts['lastname'].', '.$raccounts['firstname'].'</td>
										<td style="text-align:right;">'.number_format($raccounts['balance'],2).'</td>
										<td style="text-align:left;">
											<button class="btn btn-danger adminAccountReset" value = "'.$raccounts['id'].'" data-mobileNumber = "'.$raccounts['mobileNumber'].'">RESET PASSWORD</button>
										</td>
									</tr>';
									$count++;
									
									/*<td style="text-align:center;">
											<button class="btn btn-md btn-primary btnAddBalance" data-accountID = "'.$raccounts['id'].'" data-fname = "'.$raccounts['firstname'].'" data-lname = "'.$raccounts['lastname'].'" data-balance = "'.number_format($raccounts['balance'],2).'" value = "'.$raccounts['mobileNumber'].'" ><i class="fas fa-plus"></i> Add Balance</button>
											
											<button class="btn btn-md btn-success btnWithdrawBalance"  data-accountID = "'.$raccounts['id'].'" data-fname = "'.$raccounts['firstname'].'" data-lname = "'.$raccounts['lastname'].'" data-balance = "'.number_format($raccounts['balance'],2).'" value = "'.$raccounts['mobileNumber'].'"><i class="fas fa-minus"></i> Withdraw Balance</button>
										</td>
									*/
								   }
							   }
							   ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
  
    <!-- Bootstrap core JavaScript-->
  <script src="design/vendor/jquery/jquery.min.js"></script>
  <script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

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
		function reloadPage(){ 
			location.reload();
		}
		$(document).ready(function(){
			$(".adminAccountReset").click(function(){
				accountIDVal = $(this).val();
				mobileNumberVal = $(this).attr("data-mobileNumber");
				swal({
					title: "RESET ACCOUNT PASSWOORD!",
					text: "Are you sure?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm){

					if(isConfirm){
						$.post("admin/clientAccountReset.php", {accountID:accountIDVal, mobileNumber:mobileNumberVal}, function(res){
							if(res == 1){
								swal({
									html: true,
									title: "Account password has been reset successfully!",
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
								swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
							}
						});

					}else{
						swal("Cancelled", "Account password reset ahs been cancelled!", "error");
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