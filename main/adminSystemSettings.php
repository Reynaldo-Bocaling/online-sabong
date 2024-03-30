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
                    <span class="font-semibold text-blue-500">System-Settings</span>
                    </div>
                    <small><?php echo $currentDate ?></small>
                </div>

				<p class="text-xl text-black font-bold mb-4">System Settings</p>	
				



				<div class="flex items-center justify-start  w-full">
					<div class="flex items-center  gap-7 ">
						<a href="adminManageSystem.php" class="relative text-sm  font-semibold "> System Users</a>
						<a href="adminSystemSettings.php" class="relative text-sm px-2 text-blue-500 after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-2 after:left-0 font-semibold ">System Settings</a>
					</div>
				</div>

					
				<div class="flex items-start ml-4 gap-20">
					<div class="mt-12">
						<span>Print 2 Copies of Payout Receipt?</span>
						<div class="flex items-center gap-3">
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
						</div>
					</div>
					<form method="POST" target="_blank" action="print/printCancelTicketBetAdmin.php" id="frmCancelTicketBet" class="w-[450px] bg-white px-4 pt-4 pb-8 rounded-xl shadow-md shadow-slate-100 mt-6 flex flex-col gap-4  border">
						<p class="text-lg text-black font-bold mb-2">Change System Refresh Password</p>						
						<input type="password" id="txtRefreshOldPassword" class="form-control form-control-user w-full h-[50px]" placeholder="Enter Old Password"/>
						<input type="password" id="txtRefreshNewPassword" class="form-control form-control-user w-full h-[50px]" placeholder="Enter New Password"/>
						<input type="password" id="txtRefreshConfirmNewPassword" class="form-control form-control-user w-full h-[50px]" placeholder="Enter New Password"/>

						<!-- biutton -->
						<input type = "button" id = "btnUpdateRefreshPassword" class=" font-semibold text-white py-3 bg-blue-500 rounded-full" value="Update Password" />
					</form>
				</div>

			</main>
		</div>
	</div>

</div>	

















<!-- script -->
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