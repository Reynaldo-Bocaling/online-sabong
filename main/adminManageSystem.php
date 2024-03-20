<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
	
	$query = $mysqli->query("SELECT a.`id`, a.`username`, a.`cname`, a.`roleID`, a.`payoutSettings`, b.`role`, c.`betType`, a.`specialTeller`  FROM `tblusers` a 
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
							<a href="adminManageSystem.php" class=""><button class="btn btn-lg btn-primary"><i class="fas fa-book"></i> System Users</button></a>
							<a href="adminSystemSettings.php" class=""><button class="btn btn-md btn-success"><i class="fas fa-book"> System Settings</i></button></a>';
						?>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="table-responsive">
									<input type = "hidden" id = "hiddenAccountID" />	
									<?php
									echo '
										<button class="btn btn-sm btn-primary" id = "btnAddStaffAccount"><i class="fas fa-plus"></i> Add Staff Account</button>
										<button class="btn btn-sm btn-danger" id = "btnAddTicketAccount"><i class="fas fa-plus"></i> Add Ticket Handler Account</button>
										<button class="btn btn-sm btn-success" id = "btnAddFightControllerAccount"><i class="fas fa-plus"></i> Add Fight Controller</button>
										<button class="btn btn-sm btn-warning" id = "btnAddCashoutHandler"><i class="fas fa-plus"></i> Add Cash Handler</button>
										<button class="btn btn-sm btn-info" id = "btnAddReportSupervisorAccount"><i class="fas fa-plus"></i> Add Report Supervisor Account</button><br/><br/>';
									?>
									
									<table class="table table-bordered" id="example" width="100%" cellspacing="0">
										<thead>
											<tr>
												<th style="text-align:center;">#</th>
												<th style="text-align:left;">Role</th>
												<th style="text-align:left;">Username</th>
												<th style="text-align:left;">Fullname</th>
												<th style="text-align:left;">Handled Bet Type</th>
												<th style="text-align:left;">Payout Only</th>
												<th style="text-align:left;">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if($query->num_rows > 0){
												$x = 1;
												while($row = $query->fetch_assoc()){
													$payoutSettings = $row['payoutSettings'];
													$specialTeller = $row['specialTeller'];
													if($row['id'] == $_SESSION['companyID']){
													}else{
														echo '
														<tr class="row_example">
															<td style="text-align:center;">'.$x.'</td>
															<td style="text-align:left;">'.$row['role'].'</td>
															<td style="text-align:left;">'.$row['username'].'</td>
															<td style="text-align:left;">'.$row['cname'].'</td>';
															
															if($row['specialTeller'] == 1){
																echo '<td style="text-align:left;">MERON AND WALA</td>';
															}else{
																echo '<td style="text-align:left;">'.$row['betType'].'</td>';
															}
															echo'
															
															<td style="text-align:left;" class="sel_example">';
															
															if($payoutSettings == 1 AND $row['role'] == "STAFF"){
																echo '
																<label class="radio-inline btn btn-primary">
																  <input type="radio" name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="1" checked> YES
																</label>&nbsp;&nbsp;
																<label class="radio-inline btn btn-danger">
																	 <input type="radio"  name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="0" > NO
																</label>';
															}else{
																if($row['role'] == "STAFF"){
																	echo '
																	<label class="radio-inline  btn btn-primary">
																	  <input type="radio" name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="1"> YES
																	</label>&nbsp;&nbsp;
																	<label class="radio-inline btn btn-danger">
																		 <input type="radio"  name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="0" checked> NO
																	</label>';
																}
															}
															echo '
															</td>
															<td style="text-align:left;">';
															if($row['roleID'] == 1 OR $row['roleID'] == 4 OR $row['roleID'] == 5  OR $row['roleID'] == 8 OR $row['roleID'] == 9 OR $row['roleID'] == 11){
															}else{
																echo	
																'<button class="btn btn-primary actStaffStatus"  value = "'.$row['id'].'">DEACTIVATE</button>
																<button class="btn btn-danger actStaffReset" value = "'.$row['id'].'">RESET PASSWORD</button>';
																
																if($specialTeller == 1){
																	
																}else{
																	if($row['roleID'] == 2){
																	echo '
																	<button class="btn btn-success actStaffSpecialTeller" value = "'.$row['id'].'">ASSIGN AS SPECIAL TELLER</button>';
																	}else{
																		
																	}
																}
															}
															echo'
															</td>
														</tr>';
													}
													$x++;
												}
											}else{
													
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
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});
		function caps(element){
			element.value = element.value.toUpperCase();
		}
		
		$(document).ready(function(){
			$('#example').DataTable( {
			});		
			$("#btnAddTicketAccount").click(function(){
				$("#modal_addTicketAccount").modal("show");
			});
			$('#modal_addAdminAccount').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#addAdminUsername').focus();
				}, 10);
			});
			
			$("#btnAddStaffAccount").click(function(){
				$("#modal_addStaffAccount").modal("show");
			});
			$('#modal_addStaffAccount').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#addStaffUsername').focus();
				}, 10);
			});
			
			$("#btnAddFightControllerAccount").click(function(){
				$("#modal_addFightControllerAccount").modal("show");
			});
			$('#modal_addFightControllerAccount').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#addFightControllerUsername').focus();
				}, 10);
			});	
			
			$("#btnAddCashoutHandler").click(function(){
				$("#modal_addCashoutHandler").modal("show");
			});
			
			$('#modal_addCashoutHandler').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#addCashoutHandlerFullname').val("").focus();
					$('#addCashoutHandlerUsername').val("").focus();	
				}, 1);
			});	
			
			$("#btnAddReportSupervisorAccount").click(function(){
				$("#modal_addReportSupervisorAccount").modal("show");
			});
			
			$('#modal_addReportSupervisorAccount').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#addReportSupervisorUsername').focus();
				}, 10);
			});	
			
			$('table#example tbody').on('click', 'tr td .payoutClick', function(){		
				tellerSettingsID = $(this).val();
				tellerID = $(this).attr('data-tellerID');
				
				$.post("admin/savePayoutTellerSettings.php", {accountID:tellerID, payoutSettings:tellerSettingsID}, function(res){
					if(res == 1){
						swal({
							title: "Teller Payout Settings Updated Successfully!",
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
						swal("unable to modify Payout settings for this teller! Refresh the page and try again!","","error");
					}
					
				});
				
			});
		
			$('table#example tbody').on('click', 'tr td .actStaffReset', function(){	
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
							location.reload();
						});
					}else{
						swal("unable to reset user account password! Refresh the page and try again!","","error");
					}
				});
			});
			$('table#example tbody').on('click', 'tr td .actStaffStatus', function(){	
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
									location.reload();
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
			$('table#example tbody').on('click', 'tr td .actStaffSpecialTeller', function(){	
				accountIDVal = $(this).val();
				swal({
					title: "ASSIGN AS SPECIAL TELLER",
					text: "Are you sure you?",
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
						$.post("admin/assignSpecialTeller.php", {accountID:accountIDVal}, function(res){
							if(res == 1){
								swal({
									title: "Successfully Assigned as Special Teller!",
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
								swal("Unable to assign as special teller! Refresh the page and try again!","","error");
							}
						});
					} else {
						swal("Cancelled", "Special Teller has been cancelled!", "error");
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