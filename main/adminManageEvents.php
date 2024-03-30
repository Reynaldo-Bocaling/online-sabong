<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
	$checkEvent = $mysqli->query("SELECT * FROM tblevents WHERE eventStatus = '0' ORDER BY id DESC ");
	$countEvent = $checkEvent->num_rows;

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
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm  p-3 font-normal text-blue-500 bg-blue-50 rounded-lg" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 "></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
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
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm  p-3 font-normal text-blue-500 bg-blue-50 rounded-lg" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 "></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
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
                    <p>ReportsManagement/ </p>
                    <span class="font-semibold text-blue-500">Reports-Event</span>
                    </div>
                    <small><?php echo $currentDate ?></small>
                </div>

				<p class="text-xl text-black font-bold mb-2">Event And Teller Reports</p>		
				
				<div class="flex items-center justify-start  w-full my-3">
					<div class="flex items-center  gap-7">
						<a href="adminManageReports.php" class="relative text-sm  font-semibold">Bettings Reports</a>
						<a href="adminManageEvents.php" class="relative text-sm font-semibold px-2 text-blue-500 after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-2 after:left-0"> Event and Teller Reports</a>
					</div>	
				</div>


				<!-- table of current bet -->
				<div class="flex flex-col gap-5">
					<div class="p-3 rounded-lg border shadow-md shadow-slate-100 bg-white mt-3 overflow-x-auto  w-full">
						<div class="w-full flex items-center justify-between mb-4">
							<span class="text-xl text-black font-bold">Events Report</span>
							<button class="text-sm text-white font-semibold bg-blue-500 px-3 py-2 rounded-full" id = "btnAddEvent"><i class="fas fa-plus"></i> Add Event</button>
						</div>

						<!-- table -->
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" >
							<thead>
								<tr>
									<th class="text-sm" style="text-align:center;">#</th>
									<th class="text-sm" style="text-align:left;">Event Date</th>
									<th class="text-sm" style="text-align:left;">Event Status</th>
									<th class="text-sm" style="text-align:left;">User System Access Status</th>
									<th class="text-sm" style="text-align:left;">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$queryEvent = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC ");
								if($queryEvent->num_rows > 0){
									$x = 1;
									while($rowEvent = $queryEvent->fetch_assoc()){
									echo '
										<tr>
											<td class="text-sm" style="text-align:center;">'.$x.'</td>
											<td class="text-sm" style="text-align:left;">'.DATE("M d, Y", strtotime($rowEvent['eventDate'])).'</td>';
											
											if($rowEvent['eventStatus'] == 0){
												echo '<td class="text-sm" style="text-align:left;">OPEN</td>';
											}else{
												echo '<td class="text-sm" style="text-align:left;">CLOSE</td>';
											}
											
											if($rowEvent['userAccessStatus'] == 0){
												echo '<td class="text-sm" style="text-align:left;">OPEN</td>';
											}else{
												echo '<td class="text-sm" style="text-align:left;">CLOSE</td>';
											}
											
											echo '
											<td class="text-sm" style="text-align:left;">';
											
												if($rowEvent['eventStatus'] == 0){
													echo '<button class="text-xs text-white font-medium bg-red-500 px-3 py-2 rounded-full my-1 btnCloseEvent" value = "'.$rowEvent['id'].'">CLOSE EVENT</button>&nbsp;';
												}else{
													
												}
												if($rowEvent['userAccessStatus'] == 0){
													echo '<button class="text-xs text-white font-medium bg-red-500 px-3 py-2 rounded-full my-1 btnCloseSystem" value = "'.$rowEvent['id'].'">CLOSE SYSTEM ACCESS</button>&nbsp;';
												}else{
													echo '<button class="text-xs text-white font-medium bg-blue-500 px-3 py-2 rounded-full btnOpenSystem" value = "'.$rowEvent['id'].'"> OPEN SYSTEM ACCESS</button><br/>';
												}
												echo '<button class="text-xs text-white font-medium bg-green-500 px-3 py-2 rounded-full my-1 btnEODTickets" value = "'.$rowEvent['id'].'">GENERATE TICKETING SUMMARY REPORT</button><br/>';
												echo '<button class="text-xs text-white font-medium bg-green-500 px-3 py-2 rounded-full my-1  btnEODMobile" value = "'.$rowEvent['id'].'">GENERATE MOBILE SUMMARY REPORT</button><br/>';
												echo '<button class="text-xs text-white font-medium bg-green-500 px-3 py-2 rounded-full my-1 btnEODAll" value = "'.$rowEvent['id'].'">GENERATE END OF THE DAY REPORT</button><br/>';
												echo '<button class="text-xs text-white font-medium bg-green-500 px-3 py-2 rounded-full my-1 btnAllTeller" value = "'.$rowEvent['id'].'">GENERATE TELLER REPORTS</button><br/>';
												
												
												
											echo '</td>
										</tr>';
										$x++;
									}
								}else{
									
								}
								?>
							</tbody>
						</table>
					</div>


					<!-- list of cash in -->
					<div class="p-3 rounded-lg border shadow-md shadow-slate-100 bg-white mt-3 overflow-x-auto  w-full">
						<p class="text-xl text-black font-bold mb-4">List Of Cash In</p>
						<table class="table table-bordered" id="example" width="100%" cellspacing="0" >
							<thead>
								<tr>
									<th colspan = "6" style="text-align:center;">LIST OF CASH IN</th>
						
								</tr>
								<tr>
									<th class="text-sm" style="text-align:center;">#</th>
									<th class="text-sm" style="text-align:left;">Event Date</th>
									<th class="text-sm" style="text-align:left;">Teller</th>
									<th class="text-sm" style="text-align:left;">Amount</th>
									<th class="text-sm" style="text-align:left;">Status</th>
									<th class="text-sm" style="text-align:left;">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$queryEvent = $mysqli->query("SELECT a.`id`, a.`amount`, a.`statusID`, b.`username`, ev.`eventDate`, ev.`eventStatus` FROM `tblusertransactions` a 
								LEFT JOIN `tblusers` b ON a.userID = b.id
								LEFT JOIN tblevents ev ON a.eventID = ev.id
								WHERE transactionID = '1' ORDER BY a.id DESC ");
								if($queryEvent->num_rows > 0){
									$x = 1;
									while($rowEvent = $queryEvent->fetch_assoc()){
									echo '
										<tr>
											<td class="text-sm" style="text-align:center;">'.$x.'</td>
											<td class="text-sm" style="text-align:center;">'.DATE("M d, Y", strtotime($rowEvent['eventDate'])).'</td>
											<td class="text-sm" style="text-align:center;">'.$rowEvent['username'].'</td>
											<td class="text-sm" style="text-align:right;">'.number_format($rowEvent['amount'],2).'</td>';
											
											if($rowEvent['statusID'] == 1){
												echo '<td class="text-sm" style="text-align:center;">CANCELLED</td>';
												if($rowEvent['eventStatus'] == 0){ // 0 means OPEN EVENT
													echo '
													<td class="text-sm" style="text-align:left;">
														<button class="text-sm text-white font-semibold bg-blue-500 px-4 py-2 rounded-full btnCashinApproved" value = "'.$rowEvent['id'].'">APPROVED CASH IN</button>
													</td>';
												}else{
													echo '<td class="text-sm"></td>';
												}		
												
											}else{
												echo '<td class="text-sm" style="text-align:center;">APPROVED</td>';
												if($rowEvent['eventStatus'] == 0){ // 0 means OPEN EVENT
													echo '
													<td class="text-sm" style="text-align:left;">
														<button class="text-sm text-white font-semibold bg-red-500 px-4 py-2 rounded-full  btnCashinCancel" value = "'.$rowEvent['id'].'">CANCEL CASH IN</button>
													</td>';
												}else{
													echo '<td></td>';
												}															
											}
											
								
											
									echo '
										</tr>';
										$x++;
									}
								}else{
									
								}
								?>
							</tbody>
						</table>
					</div>

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
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});
		$(document).ready(function(){
			$('#example').DataTable( {
			});		
			$("#btnAddEvent").click(function(){
				$("#modal_addEvent").modal("show");
			});
			
			$(".btnCloseEvent").click(function(){
				idVal = $(this).val();
				$("#hiddenEventID").val(idVal);
				$("#modal_confirmCloseEvent").modal("show");
			});
			
			$(".btnCloseSystem").click(function(){
				idVal = $(this).val();
				$("#hiddenEventID").val(idVal);
				$("#modal_confirmCloseSystem").modal("show");
			});
			$(".btnOpenSystem").click(function(){
				idVal = $(this).val();
				$("#hiddenEventID").val(idVal);
				$("#modal_confirmOpenSystem").modal("show");
			});
			
			$(".btnEODTickets").click(function(){
				eventID = $(this).val();
				$("#hiddenEODTicketEventID").val(eventID);
				$("#generate_tellersummaryreport").click();
			});
			
			$(".btnEODMobile").click(function(){
				eventID = $(this).val();
				$("#hiddenEODMobileEventID").val(eventID);
				$("#generate_mobilesummaryreport").click();
			});
			
			$(".btnEODAll").click(function(){
				eventID = $(this).val();
				$("#hiddenEODAllEventID").val(eventID);
				$("#generate_allsummaryreport").click();
			});
			$(".btnAllTeller").click(function(){
				eventID = $(this).val();
				$("#hiddenEODAllTellerID").val(eventID);
				$("#generate_allTeller").click();
			});
			$('table#example tbody').on('click', 'tr td .btnCashinCancel', function(){	
				id = $(this).val();			
				swal({
					title: "CANCEL THE TELLER CASH IN?!",
					text: "Are you sure?",
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
						$.post("admin/saveCashinCancel.php", {cashinID:id}, function(res){
							if(res == 1){
								swal({
									html: true,
									title: "Successfully cancelled the teller cash in transaction for this event!",
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
								swal("Error! The cash in transaction does not exist. Please refresh the page and try again.", "", "error");		
							}else{
								swal("error! Refresh the page and try again or system developer assistance is required!.", "", "error");	
							}
						});
				   }
				});
				
			});
			
			
			$(".btnCashinApproved").click(function(){
				id = $(this).val();			
				swal({
					title: "APPROVE THE TELLER CASH IN?!",
					text: "Are you sure?",
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
						$.post("admin/saveCashinApproved.php", {cashinID:id}, function(res){
							if(res == 1){
								swal({
									html: true,
									title: "Successfully approved the teller cash in transaction for this event!",
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
								swal("Error! The cash in transaction does not exist. Please refresh the page and try again.", "", "error");		
							}else{
								swal("error! Refresh the page and try again or system developer assistance is required!.", "", "error");	
							}
						});
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