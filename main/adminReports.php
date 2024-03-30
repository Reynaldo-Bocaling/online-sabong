<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1){	
	$qaccounts = $mysqli->query("SELECT * FROM `tblaccounts` ORDER BY lastname ASC, firstname ASC, balance DESC ");	
	$qyear = $mysqli->query("SELECT YEAR(CURDATE()) as dbyear;");
	while($ryear = $qyear->fetch_assoc()){
		$currentYear = $ryear['dbyear'];
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
		<!-- Custom fonts for this template-->
	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<script src="design/dist/sweetalert.js"></script>
	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
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
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list text-gray-400  mr-2"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm  p-3 font-normal text-gray-600" href="adminReportsManagement.php"><i class="fas fa-trash  text-gray-400 mr-2 "></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
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
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list text-gray-400  mr-2"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm  p-3 font-normal text-gray-600" href="adminReportsManagement.php"><i class="fas fa-trash  text-gray-400 mr-2 "></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
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
                    <span class="font-semibold text-blue-500">Manage-Reports</span>
                    </div>
                    <small><?php echo $currentDate ?></small>
                </div>

				<p class="text-xl text-black font-bold mb-2">Admin Reports</p>	
				
				<div class="flex items-center justify-start  w-full my-3">
					<div class="flex items-center  gap-7">
						<a href="adminManageReports.php" class="relative text-sm  font-semibold  px-2 text-blue-500 after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-2 after:left-0">Bettings Reports</a>
						<a href="adminManageEvents.php" class="relative text-sm font-semibold "> Event and Teller Reports</a>
						<!-- <a href="adminManageTransactionLogs.php" class="relative text-sm font-semibold px-2 text-blue-500 after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-2 after:left-0"> Transaction History</a><br/><br/> -->
					</div>	
				</div>


			

					
					<div class="flex items-center justify-start gap-20 mt-4">
						<div class=" max-w-[500px] w-full bg-white shadow-md shad-w-gray-100 border rounded-lg py-6 px-4">
							<span class="text-2xl text-black font-bold">Report Generation</span>

							<div class="flex flex-col gap-3 mt-4">
								<div class="flex flex-col">
									<span class="text-sm font-semibold text-black">Select Scope to display:</span>	
									<select id="reportScope" name="reportScope" class="form-control mt-1" >
										<option value="all">All</option>
										<option value="year">Year</option>
										<option value="monthly">Monthly</option>
										<option value="this_month">This Month</option>
										<option value="today">Today</option>
										<option value="range">Date Range</option>
										<option value="currentfight">Current Fight Only</option>
									</select>
									<span id="con_yearly" style="display:none;">
										<span class="text-sm font-semibold text-black">Year:</span>
										<select id="reportYear" name="reportYear" class="form-control">
											<option value = "<?php echo $currentYear?>"><?php echo $currentYear?></option>
										</select>
									</span>
								</div>

								<div class="flex flex-col">
								<span id="con_monthly" style="display:none;">
									<span class="text-sm font-semibold text-black">Month:</span>
									<select name="reportMonth" id="reportMonth" class="form-control" style="width:98%;">
										<option value="all" >All</option>
										<option value="01" >January</option>
										<option value="02" >February</option>
										<option value="03" >March</option>
										<option value="04" >April</option>
										<option value="05" >May</option>
										<option value="06" >June</option>
										<option value="07" >July</option>
										<option value="08" >August</option>
										<option value="09" >September</option>
										<option value="10" >October</option>
										<option value="11" >November</option>
										<option value="12" >December</option>
									</select>
									</span>	
									<span id="con_range" style="display:none;">
										<br/>
										<label style="font-weight:bolder; color:#1f364f;">Date Range Picker</label>
										<input id="date-range-picker" name = "daterange" type="text" value="10/01/2020 - 10/30/2020" class="form-control">
									</span>
								</div>

								<div class="flex flex-col">
									<span class="text-sm font-semibold text-black">Bettor Type:</span>
									<select id="reportBettorType" name="reportBettorType" class="form-control mt-1">
										<option value = "all">All</option>
										<option value = "0">Ticket</option>
										<option value = "1">Cellphone</option>
									</select>
								</div>
								<div class="flex flex-col">
									<span class="text-sm font-semibold text-black">Betting Status:</span>
									<select id="reportStatus" name="reportStatus" class="form-control mt-1">
										<option value = "all">All</option>
										<?php
										$qstatus = $mysqli->query("SELECT * FROM tblbettingstatus");
										
										while($rstatus = $qstatus->fetch_assoc()){
											echo '<option value = "'.$rstatus['id'].'">'.$rstatus['isBetting'].'</option>';
										}
										?>
									
									</select>
								</div>
								<div class="flex flex-col">
								<span class="text-sm font-semibold text-black">Betting Under: <span style="color:red">applicable for bet history only</span></span>
									<select id="reportBetUnder" name="reportBetUnder" class="form-control mt-1">
										<option value = "all">All</option>
										<?php
										$qbettype = $mysqli->query("SELECT * FROM tblbettypes");
										
										while($rbettype = $qbettype->fetch_assoc()){
											echo '<option value = "'.$rbettype['id'].'">'.$rbettype['betType'].'</option>';
										}
										?>
									</select>
								</div>
								<div class="flex flex-col">

								</div>
							</div>

						</div>

						<div class="fle flex-col max-w-[350px] w-full gap-5">
							<form method="post" class="w-full my-3" target="_blank" action='reports/fightHistory.php' id='target_report1'>
								<input type="hidden" id = "report1HiddenScope" name = "report1HiddenScope">
								<input type="hidden" id = "report1HiddenYear" name = "report1HiddenYear">
								<input type="hidden" id = "report1HiddenMonth" name = "report1HiddenMonth">
								<input type="hidden" id = "report1HiddenRangeFrom" name = "report1HiddenRangeFrom">
								<input type="hidden" id = "report1HiddenRangeTo" name = "report1HiddenRangeTo">
								<input type="hidden" id = "report1HiddenBettorType" name = "report1HiddenBettorType">
								<input type="hidden" id = "report1HiddenStatus" name = "report1HiddenStatus">
								<input type="button" id = "sbmtReport1" class="text-sm w-full text-white font-medium bg-blue-500 py-3 rounded-full" value = "FIGHT SUMMARY">
							</form>
							<form method="post" class="w-full my-3" target="_blank" action='reports/betHistory.php' id='target_report2'>
								<input type="hidden" id = "report2HiddenScope" name = "report2HiddenScope">
								<input type="hidden" id = "report2HiddenYear" name = "report2HiddenYear">
								<input type="hidden" id = "report2HiddenMonth" name = "report2HiddenMonth">
								<input type="hidden" id = "report2HiddenRangeFrom" name = "report2HiddenRangeFrom">
								<input type="hidden" id = "report2HiddenRangeTo" name = "report2HiddenRangeTo">
								<input type="hidden" id = "report2HiddenBettorType" name = "report2HiddenBettorType">
								<input type="hidden" id = "report2HiddenStatus" name = "report2HiddenStatus">
								<input type="hidden" id = "report2HiddenBetUnder" name = "report2HiddenBetUnder">
								<input type="button" id = "sbmtReport2" class="text-sm w-full text-white font-medium bg-blue-500 py-3 rounded-full"  value = "BET HISTORY">
							</form>
							<form method="post" class="w-full my-3" target="_blank" action='reports/incomeHistory.php' id='target_report3'>
								<input type="hidden" id = "report3HiddenScope" name = "report3HiddenScope">
								<input type="hidden" id = "report3HiddenYear" name = "report3HiddenYear">
								<input type="hidden" id = "report3HiddenMonth" name = "report3HiddenMonth">
								<input type="hidden" id = "report3HiddenRangeFrom" name = "report3HiddenRangeFrom">
								<input type="hidden" id = "report3HiddenRangeTo" name = "report3HiddenRangeTo">
								<input type="button" id = "sbmtReport3" class="text-sm w-full text-white font-medium bg-blue-500 py-3 rounded-full"  value = " INCOME HISTORY">
							</form>
							<form method="post" class="w-full my-3" target="_blank" action='reports/depositHistory.php' id='target_report4'>
								<input type="hidden" id = "report4HiddenScope" name = "report4HiddenScope">
								<input type="hidden" id = "report4HiddenYear" name = "report4HiddenYear">
								<input type="hidden" id = "report4HiddenMonth" name = "report4HiddenMonth">
								<input type="hidden" id = "report4HiddenRangeFrom" name = "report4HiddenRangeFrom">
								<input type="hidden" id = "report4HiddenRangeTo" name = "report4HiddenRangeTo">
								<input type="button" id = "sbmtReport4" class="text-sm w-full text-white font-medium bg-blue-500 py-3 rounded-full"  value = " DEPOSIT HISTORY">
							</form>
							<form method="post" class="w-full my-3" target="_blank" action='reports/withdrawalHistory.php' id='target_report5'>
								<input type="hidden" id = "report5HiddenScope" name = "report5HiddenScope">
								<input type="hidden" id = "report5HiddenYear" name = "report5HiddenYear">
								<input type="hidden" id = "report5HiddenMonth" name = "report5HiddenMonth">
								<input type="hidden" id = "report5HiddenRangeFrom" name = "report5HiddenRangeFrom">
								<input type="hidden" id = "report5HiddenRangeTo" name = "report5HiddenRangeTo">
								<input type="button" id = "sbmtReport5" class="text-sm w-full text-white font-medium bg-blue-500 py-3 rounded-full"  value = " WITHDRAWAL HISTORY">
							</form>
						</div>
					</div>
			</main>
		</div>
	</div>

</div>	














	<!-- script -->
	<script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="design/vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="design/js/sb-admin-2.min.js"></script>
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	
	<script type="text/javascript" src="assets/newjscss/moment.js"></script>
    <!-- Bootstrap Date Range Picker-->
    <script type="text/javascript" src="assets/newjscss/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/newjscss/daterangepicker.css" />
	<script>
		$(document).ready(function(){
			$("#reportScope").change(function(){
				sc = $("#reportScope").val();
				if(sc == "year"){
					$("#con_yearly").show();
					$("#con_monthly").hide();
					$("#con_range").hide();
				}else if(sc == "monthly"){
					$("#con_yearly").show();
					$("#con_monthly").show();
					$("#con_range").hide();
				}else if(sc == "range"){
					$("#con_yearly").hide();
					$("#con_monthly").hide();
					$("#con_range").show();
				}else{
					$("#con_yearly").hide();
					$("#con_monthly").hide();
					$("#con_range").hide();
				}
			});
			$( "#date-range-picker" ).daterangepicker();
			
			$("#sbmtReport1").click(function(){
				scopeVal = $("#reportScope").val();
				yearVal = $("#reportYear").val();
				monthVal = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				bettorTypeVal = $("#reportBettorType").val();
				statusVal = $("#reportStatus").val();

				
				$("#report1HiddenScope").val(scopeVal);
				$("#report1HiddenYear").val(yearVal);
				$("#report1HiddenMonth").val(monthVal);
				$("#report1HiddenBettorType").val(bettorTypeVal);
				$("#report1HiddenStatus").val(statusVal);
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				
				$("#report1HiddenRangeFrom").val(dateFrom);
				$("#report1HiddenRangeTo").val(dateTo);
				if($("#report1HiddenScope").val() != ""){
					
					$("#target_report1").submit();
				}else{
					swal("Select Scope for report!");
				}
			});
			
			$("#sbmtReport2").click(function(){
				scopeVal = $("#reportScope").val();
				yearVal = $("#reportYear").val();
				monthVal = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				bettorTypeVal = $("#reportBettorType").val();
				statusVal = $("#reportStatus").val();
				betUnderVal = $("#reportBetUnder").val();
				
				
				$("#report2HiddenScope").val(scopeVal);
				$("#report2HiddenYear").val(yearVal);
				$("#report2HiddenMonth").val(monthVal);
				$("#report2HiddenBettorType").val(bettorTypeVal);
				$("#report2HiddenStatus").val(statusVal);
				$("#report2HiddenBetUnder").val(betUnderVal);
				
				
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				
				$("#report2HiddenRangeFrom").val(dateFrom);
				$("#report2HiddenRangeTo").val(dateTo);
				if($("#report2HiddenScope").val() != ""){
					$("#target_report2").submit();
				}else{
					swal("Select Scope for report!");
				}
			});
			
			
			$("#sbmtReport3").click(function(){
				scopeVal = $("#reportScope").val();
				yearVal = $("#reportYear").val();
				monthVal = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				bettorTypeVal = $("#reportBettorType").val();
				$("#report3HiddenScope").val(scopeVal);
				$("#report3HiddenYear").val(yearVal);
				$("#report3HiddenMonth").val(monthVal);
				$("#report3HiddenBettorType").val(bettorTypeVal);
				
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				
				$("#report3HiddenRangeFrom").val(dateFrom);
				$("#report3HiddenRangeTo").val(dateTo);
				if($("#report3HiddenScope").val() != ""){
					$("#target_report3").submit();
				}else{
					swal("Select Scope for report!");
				}
			});
			
			$("#sbmtReport3a").click(function(){
				scopeVal = $("#reportScope").val();
				yearVal = $("#reportYear").val();
				monthVal = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				bettorTypeVal = $("#reportBettorType").val();
				$("#report3aHiddenScope").val(scopeVal);
				$("#report3aHiddenYear").val(yearVal);
				$("#report3aHiddenMonth").val(monthVal);
				$("#report3aHiddenBettorType").val(bettorTypeVal);
				
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				
				$("#report3aHiddenRangeFrom").val(dateFrom);
				$("#report3aHiddenRangeTo").val(dateTo);
				if($("#report3aHiddenScope").val() != ""){
					$("#target_report3a").submit();
				}else{
					swal("Select Scope for report!");
				}
			});
			$("#sbmtReport4").click(function(){
				scopeVal = $("#reportScope").val();
				yearVal = $("#reportYear").val();
				monthVal = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				bettorTypeVal = $("#reportBettorType").val();
				statusVal = $("#reportStatus").val();

				
				$("#report4HiddenScope").val(scopeVal);
				$("#report4HiddenYear").val(yearVal);
				$("#report4HiddenMonth").val(monthVal);
				$("#report4HiddenBettorType").val(bettorTypeVal);
				$("#report4HiddenStatus").val(statusVal);
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				
				$("#report4HiddenRangeFrom").val(dateFrom);
				$("#report4HiddenRangeTo").val(dateTo);
				if($("#report4HiddenScope").val() != ""){
					
					$("#target_report4").submit();
				}else{
					swal("Select Scope for report!");
				}
			});
			
			$("#sbmtReport5").click(function(){
				scopeVal = $("#reportScope").val();
				yearVal = $("#reportYear").val();
				monthVal = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				bettorTypeVal = $("#reportBettorType").val();
				statusVal = $("#reportStatus").val();

				
				$("#report5HiddenScope").val(scopeVal);
				$("#report5HiddenYear").val(yearVal);
				$("#report5HiddenMonth").val(monthVal);
				$("#report5HiddenBettorType").val(bettorTypeVal);
				$("#report5HiddenStatus").val(statusVal);
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				
				$("#report5HiddenRangeFrom").val(dateFrom);
				$("#report5HiddenRangeTo").val(dateTo);
				if($("#report5HiddenScope").val() != ""){
					
					$("#target_report5").submit();
				}else{
					swal("Select Scope for report!");
				}
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