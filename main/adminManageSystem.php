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
	 <script src="https://cdn.tailwindcss.com"></script>
	  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

</head>

<body id="page-top">

<style>

::-webkit-scrollbar {
  width: 0;
}

</style>	
<div id="wrapper" class="fixed top-0 left-0 w-screen h-screen overflow-y-auto">
    <div id="content-wrapper" class="flex h-screen overflow-hidden">

     <!-- sidebar for mobile -->
	 	<div id="sidebar" class="hide-scrollbar overflow-hidden fixed z-50  w-screen h-screen bg-[rgba(0,0,0,0.3)] hidden transition-all">
            <div class="relative h-screen bg-white border-r shadow-lg shadow-slate-100 px-[20px] py-10 transition-all w-[270px] overflow-y-auto">
                <button id="closeBtn" class="text-red-500 text-3xl absolute top-0 right-0 m-4">&times;</button>
                <span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
                <div class="flex flex-col  mt-9 px-2 overflow-y-auto">
				<?php
					$links = [
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ?
						'<a class="text-sm text-gray-600  p-3 font-normal" href="administrator.php"><i class="fas fa-home mr-2 text-gray-400"></i>Home</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="dashboard.php"><i class="bx bxs-plus-circle text-gray-400 mr-2"></i>Betting Odds Display</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminDashboardEvent.php"><i class="bx bxs-dashboard mr-2 text-gray-400" ></i>Dashboard Configuration</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Bettings Management</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm  text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 13) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="cashHandler.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Cash INs and OUTs</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
					];
					foreach ($links as $link) {
						if (!empty($link)) {
							echo $link;
						}
					}
					?>
                </div>
            </div>
        </div>

        <!-- sidebar for desktop size -->
        <div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-10 transition-all hidden md:flex md:flex-col max-w-[270px] w-full h-screen">
            <span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
            <div class="flex flex-col overflow-y-auto mt-9 px-2">
				<?php
					$links = [
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ?
						'<a class="text-sm text-gray-600  p-3 font-normal" href="administrator.php"><i class="fas fa-home mr-2 text-gray-400"></i>Home</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="dashboard.php"><i class="bx bxs-plus-circle text-gray-400 mr-2"></i>Betting Odds Display</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminDashboardEvent.php"><i class="bx bxs-dashboard mr-2 text-gray-400" ></i>Dashboard Configuration</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Bettings Management</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm  text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 13) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="cashHandler.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Cash INs and OUTs</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
					];
					foreach ($links as $link) {
						if (!empty($link)) {
							echo $link;
						}
					}
					?>
        	</div>
    	</div>


		<!-- main -->
		<div id="content" class="flex-1 flex flex-col overflow-hiddenw gap-2 bg-[#F6F8FA]">
			<header class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7 ">
				<button id="openBtn" class="w-[30px] flex flex-col gap-[5px] border-none focus:outline-none md:hidden py-[10px]">
					<div class="w-full h-[3px] rounded-full bg-black"></div>
					<div class="w-full h-[3px] rounded-full bg-black"></div>
				</button>
				<div class="text-base font-mdium text-gray-700 flex items-center gap-2 ">
					<p class="hidden md:flex">Welcome, User</p>
					<img src="./assets/images/waving.png" class="w-[50px] hidden md:flex" />
				</div>
				<span><i class='bx bx-calendar-star text-blue-500 text-lg'></i> <?php echo date('F j, Y') ?></span>

			</header>




			<main class="flex-1 overflow-x-auto overflow-y-auto p-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm mb-3 tracking-wide gap-1">
                    <p>ManagementSystem/ </p>
                    <span class="font-semibold text-blue-500">User-Management-System</span>
                    </div>
                    <small><?php echo $currentDate ?></small>
                </div>

				<p class="text-xl text-black font-bold mb-4">System Users</p>	
				



				<div class="flex items-center justify-start  w-full">
					<div class="flex items-center  gap-7 ">
						<a href="adminManageSystem.php" class="relative text-sm px-2 text-blue-500 after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-2 after:left-0 font-semibold "> System Users</a>
						<a href="adminSystemSettings.php" class="relative text-sm font-semibold ">System Settings</a>
					</div>
				</div>

					
				<div class="flex items-center justify-start gap-2 mt-4">
					<input type = "hidden" id = "hiddenAccountID" />	
					<?php
					echo '
						<button class="text-xs text-white px-3 py-2 rounded-full bg-blue-500 " id = "btnAddStaffAccount"><i class="fas fa-plus"></i> Add Staff Account</button>
						<button class="text-xs text-white px-3 py-2 rounded-full bg-blue-500" id = "btnAddTicketAccount"><i class="fas fa-plus"></i> Add Ticket Handler Account</button>
						<button class="text-xs text-white px-3 py-2 rounded-full bg-blue-500 " id = "btnAddFightControllerAccount"><i class="fas fa-plus"></i> Add Fight Controller</button>
						<button class="text-xs text-white px-3 py-2 rounded-full bg-blue-500" id = "btnAddCashoutHandler"><i class="fas fa-plus"></i> Add Cash Handler</button>
						<button class="text-xs text-white px-3 py-2 rounded-full bg-blue-500 " id = "btnAddReportSupervisorAccount"><i class="fas fa-plus"></i> Add Report Supervisor Account</button><br/><br/>';
					?>
				</div>

				<!-- table  -->
				<div class="p-3 rounded-lg border shadow-md shadow-slate-100 bg-white mt-3 overflow-x-auto  max-w-full">
					<table class="table table-bordered" id="example" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="text-sm" style="text-align:center;">#</th>
								<th class="text-sm" style="text-align:left;">Role</th>
								<th class="text-sm" style="text-align:left;">Username</th>
								<th class="text-sm" style="text-align:left;">Fullname</th>
								<th class="text-sm" style="text-align:left;">Handled Bet Type</th>
								<th class="text-sm" style="text-align:left;">Payout Only</th>
								<th class="text-sm" style="text-align:left;">Actions</th>
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
											<td class="text-sm" style="text-align:center;">'.$x.'</td>
											<td class="text-sm" style="text-align:left;">'.$row['role'].'</td>
											<td class="text-sm" style="text-align:left;">'.$row['username'].'</td>
											<td class="text-sm" style="text-align:left;">'.$row['cname'].'</td>';
											
											if($row['specialTeller'] == 1){
												echo '<td class="text-sm" style="text-align:left;">MERON AND WALA</td>';
											}else{
												echo '<td class="text-sm" style="text-align:left;">'.$row['betType'].'</td>';
											}
											echo'
											
											<td class="text-sm" style="text-align:left;" class="sel_example">';
											
											if($payoutSettings == 1 AND $row['role'] == "STAFF"){
												echo '
												<label class="">
													<input type="radio" name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="1" checked> 
													<small>YES</small>
												</label>&nbsp;&nbsp;

												<label class="">
														<input type="radio"  name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="0" > NO
												</label>';
											}else{
												if($row['role'] == "STAFF"){
													echo '
													<label class="">
														<input type="radio" name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="1"> 
														<small>YES</small>
													</label>&nbsp;&nbsp;
													<label class="">
															<input type="radio"  name="payoutSettingID{'.$row['id'].'}" class="payoutClick" data-tellerID = "'.$row['id'].'" value="0" checked> NO
													</label>';
												}
											}
											echo '
											</td>
											<td class="text-sm" style="text-align:left;">';
											if($row['roleID'] == 1 OR $row['roleID'] == 4 OR $row['roleID'] == 5  OR $row['roleID'] == 8 OR $row['roleID'] == 9 OR $row['roleID'] == 11){
											}else{
												echo	
												'<button class="text-xs text-white font-medium bg-blue-500 px-3 py-2 rounded-full actStaffStatus"  value = "'.$row['id'].'">DEACTIVATE</button>
												<button class="text-xs text-white font-medium bg-red-500 px-3 py-2 rounded-full actStaffReset" value = "'.$row['id'].'">RESET PASSWORD</button>';
												
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
				</di>

			</main>
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


		$(document).ready(function(){
    $('#openBtn').click(function(){
      $('#sidebar').toggleClass('hidden');
    });

    $('#closeBtn').click(function(){
      $('#sidebar').addClass('hidden');
    });


    $('#sidebar').click(function(e){
      if (e.target === this) {
        $(this).addClass('hidden');
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