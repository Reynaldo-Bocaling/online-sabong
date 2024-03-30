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
    <div id="content-wrapper" class="flex h-screen">

     <!-- sidebar for mobile -->
	 	<div id="sidebar" class="hide-scrollbar fixed z-50  w-screen h-screen bg-[rgba(0,0,0,0.3)] hidden transition-all">
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
		<div id="content" class="flex-1 flex flex-col overflow-hiddenw gap-2 bg-[#F6F8FA] h-screen">
			<nav class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7 ">
				<button id="openBtn" class="w-[30px] flex flex-col gap-[5px] border-none focus:outline-none md:hidden py-[10px]">
					<div class="w-full h-[3px] rounded-full bg-black"></div>
					<div class="w-full h-[3px] rounded-full bg-black"></div>
				</button>
				<div class="text-base font-mdium text-gray-700 flex items-center gap-2 ">
					<p class="hidden md:flex">Welcome, User</p>
					<img src="./assets/images/waving.png" class="w-[50px] hidden md:flex" />
				</div>
				<span><i class='bx bx-calendar-star text-blue-500 text-lg'></i> <?php echo date('F j, Y') ?></span>

			</nav>




			<main class="flex-1 overflow-x-auto overflow-y-auto p-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm mb-3 tracking-wide gap-1">
                    <p>BettingsManagement/ </p>
                    <span class="font-semibold text-blue-500">Current-Fight-Bets-History</span>
                    </div>
                    <small><?php echo $currentDate ?></small>
                </div>

				<p class="text-xl text-black font-bold mb-2">Current Fight Bets History</p>	
				



				<div class="flex flex-col md:flex-row md:items-center md:justify-start gap-7 -ml-7">
					<form method="post" target="_blank" action="print/reprintBet.php" id="frmGenerateBarcode">
						<input type="hidden" name="barcode_text" id = "barcode_text">
						<input type="submit" name="generate_barcode" id = "sbmtGenerateBarcode" style="display:none;"value="GENERATE">
					</form>
					<input type = "hidden" id = "hiddenMobileNumber" />
					<input type = "hidden" id = "hiddenAccountID" />
					<a href="adminManageBettings.php" class="relative text-sm text-blue-500 font-semibold after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-2 after:left-0 px-2"> Current Fight Bets History</a>
					<a href="adminManageBetHistory.php" class="text-sm font-semibold">Bets History</a>
					<a href="adminManageTransactionLogs.php" class="text-sm font-semibold"> Transaction History</a><br/><br/>
				</div>	


				<!-- table of current bet -->
				<div class="p-3 rounded-lg border shadow-md shadow-slate-100 bg-white mt-2 overflow-x-auto -mx-2 max-w-full w-full">
					<table class="tables w-full table-bordered " id="example">
							<thead>
								<tr class="h-12">
									<th class="text-xs text-center pl-2">#</th>
									<th class="text-xs text-center pl-2">Date</th>
									<th class="text-xs text-center pl-2">Fight Number</th>
									<th class="text-xs text-center pl-2">Bettor</th>
									<th class="text-xs text-center pl-2">Bet Code</th>
									<th class="text-xs text-center pl-2">Bet Under</th>
									<th class="text-xs text-center pl-2">Amount</th>
									<th class="text-xs text-center pl-2">Betting Status</th>
									<th class="text-xs text-center pl-2">Result</th>
									<th class="text-xs text-center pl-2">Is Claimed?</th>
									<th class="text-xs text-center pl-2">Is Returned?</th>
									<th class="text-xs text-center pl-2">Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$totalCurrentBetsAmount = 0;
							$qbets = $mysqli->query("SELECT a.`betCode`, a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, a.`isCancelled`, b.`fightNumber`, b.`fightDate`, b.`isBetting` as isBettingID, b.`isWinner`, c.`betType` as betTypeStatus, d.`isBetting`, e.`winner`, f.`mobileNumber`, ev.`eventDate` FROM `tblbetliststemp` a 
							LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
							LEFT JOIN `tblbettypes` c ON a.betType = c.id 
							LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
							LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
							LEFT JOIN tblaccounts f ON a.accountID = f.id 
							LEFT JOIN `tblevents` ev ON b.eventID = ev.id 
							ORDER BY a.id DESC ");
							if($qbets->num_rows > 0){
								$count = 1;
								
								while($rbets = $qbets->fetch_assoc()){
									$betCode = $rbets['betCode'];
									$isBettingID = $rbets['isBettingID'];
									
									$isCancelled = $rbets['isCancelled'];
									if($isCancelled == 0){
										$totalCurrentBetsAmount += $rbets['betAmount'];
									}else{
										
									}
									echo '
									<tr>
									<td class="text-xs text-center">'.$count.'</td>
									<td class="text-xs text-center">'.DATE('M d, Y', strtotime($rbets['eventDate'])).'</td>
									<td class="text-xs text-center">'.$rbets['fightNumber'].'</td>';
									if($rbets['betRoleID'] == 3){
										echo '<td class="text-xs text-center">'.$rbets['mobileNumber'].'</td>';
									}else{
										echo '
										<td class="text-xs text-center">TICKET</td>';
									}
									echo '
									<td>'.$betCode.'</td>
									<td class="text-xs text-center">'.$rbets['betTypeStatus'].'</td>
									<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
									<td class="text-xs text-center">'.$rbets['isBetting'].'</td>';
										if($rbets['isWinner'] == 0){
											if($rbets['isBetting'] == "CANCELLED"){
												echo '
												<td class="text-xs text-center">CANCELLED</td>';
											}else{
												echo '
												<td class="text-xs text-center">UNSETTLED</td>';
											}	
										}else if($rbets['isWinner'] == 3){
											echo '
												<td class="text-xs text-center">DRAW</td>';
										}else{ 
											if($rbets['betTypeStatus'] == $rbets['winner']){
												echo '
												<td class="text-xs text-center">WIN</td>';
											}else{
												echo '
												<td class="text-xs text-center">LOST</td>';
											}
										}
									if($rbets['isBetting'] == "CANCELLED"){
										if($rbets['isReturned'] == 1){
											echo '
											<td class="text-xs text-center">RETURNED</td>';
										}else{
											echo '
											<td class="text-xs text-center">FOR REFUND</td>';
										}
									}else{
										if($rbets['isClaim'] == 0){
										echo '
										<td class="text-xs text-center">NO</td>';
										}else{
										echo '
										<td class="text-xs text-center">YES</td>';
										}
									}
									if($rbets['isBetting'] == "CANCELLED" || $rbets['isWinner'] == 3){
										if($rbets['isReturned'] == 1){
											echo '
											<td class="text-xs text-center">RETURNED</td>';
										}else{
											echo '
											<td class="text-xs text-center">FOR REFUND</td>';
										}
									}else{
										echo '
										<td class="text-xs text-center"></td>';
									}
									if($rbets['betRoleID'] == 0){
										echo 
										'<td>';
										if($rbets['isBettingID'] == 1 || $rbets['isBettingID'] == 4){
											echo '
											<button class="text-xs text-white font-semibold px-4 py-2 rounded-full bg-blue-500" value = "'.$rbets['betCode'].'"><i class="fa fa-print"></i> REPRINT</button>';
										}else{
											
										}
										if($isCancelled == 1){
											echo '&nbsp; <p class="text-xs">BET CANCELLED</span>';	
										}else{
										}
										echo
										'</td>';
									}else{
										echo 
										'<td>';
										if($isCancelled == 1){
											echo '&nbsp; <p class="text-xs">BET CANCELLED</span>';	
										}else{
										}						
										echo 
									'</td>';
									}
									echo '
								</tr>';
								$count++;
								}
							}
							echo '
						</tbody>
						<tfoot>
							<tr>
								<td colspan = "6"> </td>
								<td style="text-align:right">'.number_format($totalCurrentBetsAmount,2).'</td>
								<td colspan = "5"></td>
							</tr>
						</tfoot>';
						?>
					</table>
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
			$('#example').DataTable( {
			});
			$('table#example tbody').on('click', 'tr td .btnreprint', function(){		
				barcodeVal = $(this).val();
				
				$("#barcode_text").val(barcodeVal);
				if(barcodeVal != ""){
					$("#sbmtGenerateBarcode").click();	
				}else{
					swal("Error, no barcode to reprint.","","error");
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