<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
	
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
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 "></i>Bettings Management</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
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
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 "></i>Bettings Management</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
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
                    <p>BettingsManagement/ </p>
                    <span class="font-semibold text-blue-500">Bets-History</span>
                    </div>
                    <small><?php echo $currentDate ?></small>
                </div>

				<p class="text-xl text-black font-bold mb-2"> Bets History</p>	
				



				<div class="flex items-center justify-between  w-full">
					<div class="flex items-center  gap-7 -ml-5">
						<form method="post" target="_blank" action="print/reprintBet.php" id="frmGenerateBarcode">
							<input type="hidden" name="barcode_text" id = "barcode_text">
							<input type="submit" name="generate_barcode" id = "sbmtGenerateBarcode" style="display:none;"value="GENERATE">
						</form>
						<input type = "hidden" id = "hiddenMobileNumber" />
						<input type = "hidden" id = "hiddenAccountID" />
						<a href="adminManageBettings.php" class="relative text-sm  font-semibold "> Current Fight Bets History</a>
						<a href="adminManageBetHistory.php" class="relative text-sm font-semibold px-2 text-blue-500 after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-2 after:left-0">Bets History</a>
						<a href="adminManageTransactionLogs.php" class="text-sm font-semibold"> Transaction History</a><br/><br/>
					</div>	
					<button id="toggleFilter" class=" bg-blue-500 text-white px-4 py-2 rounded">Toggle Filter</button>
				</div>

					
				<!-- filter -->
				<div id="filterSection" class="hidden fixed top-0 left-0 w-screen h-screen bg-[rgba(0,0,0,0.2)] z-50 flex justify-end">
					<div class="flex flex-col  items-center justify-start gap-4 h-screen max-w-[220px] bg-white border rounded-lg w-full px-4 py-">
						<button id="closeFilter" class="text-2xl text-red-500 mb-2 ml-auto">&times;</button>
						<p>Filter by:</p>
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.6rem]">FIGHT NUMBER:</small>
							<select name="ffightcode" id="ffightcode" class="form-control text-sm" class="bg-transparent border-none" >
								<?php
								$qf = $mysqli->query("SELECT `id`, `fightCode`, `fightNumber` FROM `tblfights` a WHERE a.eventID = (SELECT MAX(id) FROM `tblevents`) ORDER BY a.id DESC ");
								if($qf->num_rows > 0){
									while($rf = $qf->fetch_assoc()){
										echo 
										'<option value ="'.$rf['fightCode'].'">'.$rf['fightNumber'].'</option>';
									}														
								}else{
									echo '<option value = "">NO Available Selection</option>';
								}
								?>
							</select>
						</div>
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.6rem]">TELLER:</small>
							<select id="fteller" name="fteller" class="form-control text-sm">
								<?php
								$qt = $mysqli->query("SELECT u.`id`, u.`username`, u.`cname` FROM `tblusertransactions` a LEFT JOIN tblusers u ON a.userID = u.id WHERE a.eventID = (SELECT MAX(id) FROM `tblevents`) AND transactionID = '2' GROUP BY a.userID ORDER BY u.username ");
								if($qt->num_rows > 0){
									echo '<option value = "ALL">ALL</option>';
									while($rt = $qt->fetch_assoc()){
										echo '
										<option value = "'.$rt['id'].'">'.$rt['username'].' - '.$rt['cname'].'</option>
										';
									}
								}else{
									echo '<option value = "">NO Available Selection</option>';
								}
								?>
							</select>
						</div>
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.6rem]">BET RECEIPT CODE:</small>
							<input type="text" id="fbetcode" class="form-control text-sm" name="fbetcode"  maxlength= "14" AUTOCOMPLETE = "OFF" placeholder="Enter Receipt Code" />
						</div>
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.6rem]">BET UNDER(MERON/WALA):</small>
							<select id="fbettype" name="fbettype" class="form-control text-sm">
								<?php
								$qbt = $mysqli->query("SELECT * FROM `tblbettypes`");
								if($qbt->num_rows > 0){
									echo '<option value = "ALL">ALL</option>';
									while($rbt = $qbt->fetch_assoc()){
										echo '
										<option value = "'.$rbt['id'].'">'.$rbt['betType'].'</option>
										';
									}
								}else{
									echo '<option value = "">NO Available Selection</option>';
								}
								?>
							</select>
						</div>
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.6rem]">FIGHT RESULT/WINNER:</small>
							<select id="fresult" name="fresult" class="form-control text-sm">
								<option value = "ALL">ALL</option>
								<option value = "1">MERON</option>
								<option value = "2">WALA</option>
								<option value = "3">DRAW</option>
								<option value = "0">CANCELLED</option>
							</select>
						</div>
						<div class="flex flex-col justify-centder gap-1  w-full ">
							<small class="text-[.6rem]">TELLER:</small>
							<select id="fclaim" name="fclaim" class="form-control text-sm">
								<option value = "ALL">ALL</option>
								<option value = "1">CLAIMED</option>
								<option value = "0">UNCLAIMED</option>
							</select>
						</div>
					</div>	
				</div>

				<!-- table of current bet -->
				<div class="p-3 rounded-lg border shadow-md shadow-slate-100 bg-white mt-3 overflow-x-auto  w-full">

					<!-- table start -->
					<input type = "hidden" id = "hiddenMobileNumber" />
					<input type = "hidden" id = "hiddenAccountID" />

					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="text-xs">#</th>
								<th class="text-xs">Fight #</th>
								<th class="text-xs">Teller Username</th>
								<th class="text-xs">Bet Code</th>
								<th class="text-xs">Bet Under</th>
								<th class="text-xs">Amount</th>
								<th class="text-xs">Status</th>
								<th class="text-xs">Result</th>
								<th class="text-xs">Is Claimed?</th>
								<th class="text-xs">Is Returned?</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$qbets = $mysqli->query("SELECT a.`betCode`, a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, a.`isCancelled`, b.`fightNumber`, ev.`eventDate`, b.`isWinner`, c.`betType` as betTypeStatus, d.`isBetting`, e.`winner`, f.`mobileNumber`, u.`username` FROM `tblbetlists` a 
							LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
							LEFT JOIN `tblbettypes` c ON a.betType = c.id 
							LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
							LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
							LEFT JOIN tblaccounts f ON a.accountID = f.id 
							LEFT JOIN `tblevents` ev ON b.eventID = ev.id
							LEFT JOIN tblusers u ON a.userID = u.id
							WHERE a.fightCode = (select z.`fightCode` from tblfights z ORDER BY z.id DESC LIMIT 1) ORDER BY a.id DESC");
							if($qbets->num_rows > 0){
								$count = 1;
								while($rbets = $qbets->fetch_assoc()){
									$isCancelled = $rbets['isCancelled'];
									$isClaimed = $rbets['isClaim'];
									$isReturned = $rbets['isReturned'];
									if($isCancelled == 0){
										echo '
											<tr>
											<td class="text-xs">'.$count.'</td>
											<td class="text-xs">'.$rbets['fightNumber'].'</td>';
											
								
											if($rbets['betRoleID'] == 3){
												echo '<td class="text-xs">'.$rbets['mobileNumber'].'</td>';
											}else{
												echo '
												<td class="text-xs">TICKET - '.$rbets['username'].'</td>';
											}
											echo '
											<td>'.$rbets['betCode'].'</td>
											<td class="text-xs">'.$rbets['betTypeStatus'].'</td>
											<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
											<td class="text-xs">'.$rbets['isBetting'].'</td>';
												if($rbets['isWinner'] == 0){
													if($rbets['isBetting'] == "CANCELLED"){
														echo '
														<td class="text-xs">CANCELLED</td>';
													}else{
														echo '
														<td class="text-xs">UNSETTLED</td>';
													}	
												}else if($rbets['isWinner'] == 3){
													echo '
														<td class="text-xs">DRAW</td>';
												}else{ 
													if($rbets['betTypeStatus'] == $rbets['winner']){
														echo '
														<td class="text-xs">WIN</td>';
													}else{
														echo '
														<td class="text-xs">LOST</td>';
													}
												}
											if($rbets['isBetting'] == "CANCELLED"){
												if($isClaimed == 1){
													echo '
													<td class="text-xs">YES</td>';
												}else{
													echo '
													<td class="text-xs">NO</td>';
												}
												
												if($isReturned == 1){
													echo '
													<td class="text-xs">RETURNED</td>';
												}else{
													echo '
													<td class="text-xs">FOR REFUND</td>';
												}
											}else{
												if($isClaimed == 0){
												echo '
												<td class="text-xs">NO</td>
												<td class="text-xs"></td>';
												}else{
												echo '
												<td class="text-xs">YES</td>
												<td class="text-xs"></td>';
												}
											}
									
											echo '
										</tr>';
									}else{
										echo '
											<tr>
											<td class="text-xs">'.$count.'</td>
											<td class="text-xs">'.$rbets['eventDate'].'</td>
											<td class="text-xs">'.$rbets['fightNumber'].'</td>';
											if($rbets['betRoleID'] == 3){
												echo '<td class="text-xs">'.$rbets['mobileNumber'].'</td>';
											}else{
												echo '
												<td class="text-xs">TICKET - '.$rbets['username'].'</td>';
											}
											echo '
											<td>'.$rbets['betCode'].'</td>
											<td class="text-xs">'.$rbets['betTypeStatus'].'</td>
											<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
											<td class="text-xs">'.$rbets['isBetting'].'</td>
											<td class="text-xs">BET CANCELLED</td>';
											if($isClaimed == 0){
												echo '
												<td class="text-xs">NO</td>';
												}else{
												echo '
												<td class="text-xs">YES</td>';
												}
											if($isReturned == 1){
												echo '
												<td class="text-xs">RETURNED</td>';
											}else{
												echo '
												<td class="text-xs">FOR REFUND</td>';
											}
										echo'
										</tr>';
									}
								$count++;
								}
							}
							?>
						</tbody>
					</table>
				</div>

			</main>
		</div>
	</div>

</div>				
													





















	<script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="design/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="design/js/sb-admin-2.min.js"></script>
	<script src="design/vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="design/vendor/datatables/dataTables.bootstrap4.min.js"></script>
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
		function filterBet(){
			ffightCode = $("#ffightcode").val();
			fteller = $("#fteller").val();
			fbetcode = $("#fbetcode").val();
			fbettype = $("#fbettype").val();
			fresult = $("#fresult").val();
			fclaim = $("#fclaim").val();
			$.post("filter/filterBetHistory.php", {fightcode:ffightCode, teller:fteller, betcode:fbetcode, bettype:fbettype, fightresult:fresult, claimstatus:fclaim}, function(res){
				$("#dataTable tbody").html(res);
			});
		}
		$(document).ready(function(){
			$("#ffightcode").change(function(){
				filterBet();
			});
			$("#fteller").change(function(){
				filterBet();
			});
			$("#fbettype").change(function(){
				filterBet();
			});
			$("#fresult").change(function(){
				filterBet();
			});
			$("#fclaim").change(function(){
				filterBet();
			});
			$("#fbetcode").change(function(){
				filterBet();
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
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>
</html>