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
		<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script src="design/dist/sweetalert.js"></script>

	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	 <!-- Custom styles for this page -->
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list text-gray-400 mr-2"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm  p-3 font-normal text-gray-600" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 "></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
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
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list text-gray-400 mr-2"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm  p-3 font-normal text-gray-600" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 "></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
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
                    <p>Transaction/ </p>
                    <span class="font-semibold text-blue-500">Manage-Transactions</span>
                    </div>
                    <small><?php echo $currentDate ?></small>
                </div>

				<div class="w-full flex items-center justify-between px-3 mb-4">
					<p class="text-xl text-black font-bold mb-2">TELLER TRANSACTIONS</p>
					<button id="toggleFilter" class=" bg-blue-500 text-white px-4 py-2 rounded">Toggle Filter</button>
				</div>


				


				<!-- filter -->
				<div id="filterSection" class="hidden fixed top-0 left-0 w-screen h-screen bg-[rgba(0,0,0,0.2)] z-50 flex justify-end">
					<div class="flex flex-col  items-center justify-start gap-4 h-screen max-w-[220px] bg-white border rounded-lg w-full px-4 py-">
						<button id="closeFilter" class="text-2xl text-red-500 mb-2 ml-auto">&times;</button>
						<p>Filter by:</p>

						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.7rem] text-black font-medium">Select Scope to display:</small>
							<select id="reportScope" name="reportScope" class="form-control text-sm font-medium" >
								<option value="all"  SELECTED>All</option>
								<option value="year">Year</option>
								<option value="monthly">Monthly</option>
								<option value="this_month">This Month</option>
								<option value="daily">Daily</option>
								<option value="today">Today</option>
							</select>
						</div>

						<!-- hidden -->
						<span id="con_yearly" style="display:none;">
							<span style='font-weight:bolder; font-size:13px;'>Year:</span><br>
							<select id="reportYear" name="reportYear" class="form-control">
							<?php
								$qyear = $mysqli->query("SELECT YEAR(eventDate) as getYear FROM tblevents GROUP by YEAR(eventDate) ");
								if($qyear->num_rows > 0){
									while($ryear = $qyear->fetch_assoc()){
										echo '<option value = "'.$ryear['getYear'].'">'.$ryear['getYear'].'</option>';
									}
								}
							?>
							</select>
						</span>
						<span id="con_monthly" style="display:none;">
							<span style='font-weight:bolder; font-size:13px;'>Month:</span><br>
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
						<span id="con_daily" style="display:none;">
							<span style='font-weight:bolder; font-size:13px;'>Day:</span><br>
							<select name="reportDaily" id="reportDaily" class="form-control" style="width:100%;">
								<option value="All" >All</option>
								<?php
								for($a=1; $a<=31; $a++){
									if($a < 10){
										$a = "0".$a;
									}
									echo '<option value="'.$a.'" >'.$a.'</option>';
								}												
								?>
							</select>
						</span>	

						
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.7rem] text-black font-medium">Teller:</small>
							<select id="reportTransactionTeller" name="reportTransactionTeller" class="form-control">
								<option value = "all">All</option>
								<?php
									$qteller = $mysqli->query("SELECT `id`, `username`, `cname` FROM `tblusers` a  WHERE roleID = '2' OR roleID = '7' AND isActive = '1' ");
									while($rteller = $qteller->fetch_assoc()){
										echo '<option value = "'.$rteller['id'].'">'.$rteller['username'].' - '.$rteller['cname'].'</option>';
									}
								?>
							</select>
						</div>
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.7rem] text-black font-medium">TRANSACTION TYPE:</small>
							<select id="reportTransactionType" name="reportTransactionType" class="form-control">
								<option value = "all">All</option>
								<?php
									$qtt = $mysqli->query("SELECT * FROM `tblusertransactionsstatus`");
									while($rtt = $qtt->fetch_assoc()){
										echo '<option value = "'.$rtt['id'].'">'.$rtt['transaction'].'</option>';
									}
								?>
							</select>
						</div>

						<!-- button for filter -->
						<button type = "button" class="btn btn-success btn-lg" id = "sbmtFilter" style="font-size:15px; font-weight:bold; width:100%;"><i class="ti-filter"></i> &nbsp;FILTER</button>

						<form method="POST"  class="flex flex-col justify-centder gap-1  w-full "  target="_blank" action="print/printAllTeller.php" id="frmgeneratereport">
							<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
							<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
							
							<button type = "button" class="btn btn-primary btn-lg" id = "sbmtSummaryReport" style="font-size:15px; font-weight:bold; width:100%;"><i class="fa fa-print"></i> Print<br/>Money on Hand</button>
						</form>

					</div>	
				</div>








				<!-- table -->
				<div class="bg-white px-3 py-2 max-w-full w-full bg-white rounded-2xl shadow-md shadow-slate-100 flex items-center">
					<table class="table table-bordered  table-responsive" id = "tblbets">
						<thead>
							<tr class="active">
								<th class="text-sm" style="text-align:center; width:2%;">#</th>
								<th class="text-sm" style="text-align:center;">TRANSACTION TYPE</th>
								<th class="text-sm" style="text-align:center;">AMOUNT</th>
								<th class="text-sm" style="text-align:center;">TRANSACTION CODE</th>
								<th class="text-sm" style="text-align:center;">TRANSACTION DATE</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$qtime = $mysqli->query("SELECT CURDATE() as curdatetime;");
							$rtime = $qtime->fetch_assoc();
							$curdatetime = $rtime['curdatetime'];
							$query = $mysqli->query("SELECT a.`transactionID`, b.`transaction`, a.`amount` as totalAmount, a.`transactionCode`, ev.`eventDate` FROM `tblusertransactions` a
									LEFT JOIN `tblusertransactionsstatus` b ON a.transactionID = b.id 
									LEFT JOIN `tblevents` ev ON a.eventID = ev.id 
									WHERE ev.id = (SELECT max(id) FROM `tblevents`) AND a.statusID = '0' AND a.userID = '".$_SESSION['companyID']."' ORDER BY a.id ");
								
							if($query->num_rows > 0){
								$x = 1;
								$count = 1;
								$totalBetAmount = 0;
								while($row = $query->fetch_assoc()){
								echo '
									<tr>
										<td class="text-sm" style="text-align:center;">'.$count.'</td>
										<td class="text-sm" style="text-align:center;">'.$row['transaction'].'</td>
										<td class="text-sm" style="text-align:right;">'.number_format($row['totalAmount'],2).'</td>
										<td class="text-sm" style="text-align:center;">'.$row['transactionCode'].'</td>
										<td class="text-sm" style="text-align:center;">'.DATE("M d, Y", strtotime($row['eventDate'])).'</td>
									</tr>';	
									
									$transactionID = $row['transactionID'];
									if($transactionID == 1){ //1 cash in
										$cashin = $row['totalAmount'];
										$totalBetAmount += $row['totalAmount'];
									}
									
									if($transactionID == 2){ // 2 bets
										$bets = $row['totalAmount'];
										$totalBetAmount += $row['totalAmount'];
									}
									
									if($transactionID == 3){ // 3 payout
										$totalPayoutPaid = $row['totalAmount'];
										$totalBetAmount -= $row['totalAmount'];
									}
									
									if($transactionID == 4){ // 4 refund cancelled
										$cancelledPaid = $row['totalAmount'];
										$totalBetAmount -= $row['totalAmount'];
									}
									
									if($transactionID == 5){ // refund draw
										$drawPaid = $row['totalAmount'];
										$totalBetAmount -= $row['totalAmount'];
									}
									
									if($transactionID == 6){ // mobile deposit
										$mobileDeposit = $row['totalAmount'];
										$totalBetAmount += $row['totalAmount'];
									}
									
									if($transactionID == 7){ // mobile withdraw
										$mobileWithdraw = $row['totalAmount'];
										$totalBetAmount -= $row['totalAmount'];
									}
									
									
									$count++;
									}
								echo '
									<tr>
										<td class="text-sm" colspan = "2" style="font-weight:bold;">TOTAL:</td>
										<td class="text-sm" style="font-weight:bold; text-align:right;">'.number_format($totalBetAmount,2).'</td>
										<td class="text-sm" colspan = "2" style="font-weight:bold; text-align:right;"></td>
									</tr>';
							}
							?>
						</tbody>
					</table>				
				</div>
					
			</main>
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

	<script type="text/javascript">
		function caps(element){
			element.value = element.value.toUpperCase();
		}
		function reloadPage(){ 
			location.reload();
		}
		$(document).ready(function(){
			$("#reportScope").change(function(){
				sc = $("#reportScope").val();
				if(sc == "year"){
					$("#con_yearly").show();
					$("#con_monthly").hide();
				}else if(sc == "monthly"){
					$("#con_yearly").show();
					$("#con_monthly").show();
				}else if(sc == "daily"){
					$("#con_yearly").show();
					$("#con_monthly").show();
					$("#con_daily").show();
				}else{
					$("#con_yearly").hide();
					$("#con_monthly").hide();
					$("#con_daily").hide();
				}
			});
			$("#sbmtFilter").click(function(){
				sc = $("#reportScope").val();
				ryear = $("#reportYear").val();
				rmonth = $("#reportMonth").val();
				rdaily = $("#reportDaily").val();
				rtellerID = $("#reportTransactionTeller").val();
				rtypeID = $("#reportTransactionType").val();
				if(sc == "daily" && rmonth == "all" && rdaily != "all"){
					$("#reportMonth").focus();
					swal("Error! No Selected Month", "", "error");
				}else{
					$.post("reports/filterTellerTransaction.php",{scope:sc, year:ryear, month:rmonth, daily:rdaily, teller:rtellerID, rtype:rtypeID}, function(res){
						$("#tblbets tbody").html(res);
					});
				}
			});
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

		$(document).ready(function(){
	$("#toggleFilter").click(function(){
            $("#filterSection").toggleClass("hidden");
        });

        // Close filter section when the close button is clicked
        $("#closeFilter").click(function(){
            $("#filterSection").addClass("hidden");
        });

        // Close filter section when the parent div is clicked
        $("#filterSection").click(function(e){
            if (e.target.id === "filterSection") {
                $(this).addClass("hidden");
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