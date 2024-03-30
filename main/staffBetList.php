<?php
	session_start();
	require('includes/connection.php');
	if($_SESSION['roleID'] == 1){ // 2 = STAFF
		$staffFor = $_SESSION['staffFor'];

		
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
		<!-- Custom styles for this template-->
		<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
		<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link rel="stylesheet" href="design/dist/sweetalert.css">
		<script src="design/dist/sweetalert.js"></script>
		<script src="https://cdn.tailwindcss.com"></script>
	  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

	</head>
	<body id="page-top">
 
<style>

::-webkit-scrollbar {
  width: 0;
}
#userDropdown, #btnRefreshPage{
	outline:none
}
</style>

<div id="content" class="flex-1 flex flex-col overflow-hidden gap-2 bg-white ">
	<header class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7 ">
		
		<div class="text-base font-mdium text-gray-700 flex items-center gap-2 ">
			<p class=" md:flex md:gap-2">Welcome, <span class="capitalize text-black font-semibold"><?php echo $_SESSION['username']; ?></span></p>
			<img src="./assets/images/waving.png" class="w-[50px]  md:flex" />
		</div>
		
		<div class="flex items-center gap-3">
			
			<button class="md:outline-none noneOutlineBtn" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class='bx bx-menu-alt-right text-3xl' ></i>
			</button>
			<!-- Dropdown - User Information -->
			<div class="dropdown-menu dropdown-menu-right shadow mt-2" aria-labelledby="userDropdown">
				<a class="dropdown-item" id = "changePassword">
					<i class="fa fa-lock mr-2 text-gray-400"></i>
					Change Password
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="includes/logout.php">
					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
					Logout
				</a>
			</div>
		</div>

	</header>




	<main class="flex-1 overflow-x-auto overflow-y-auto p-3 h-[90vh] w-screen">
		<div class="flex items-center justify-between w-full px-4 mb-6">
			<div class="flex items-center justify-between mr-4">
				<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
				<p>Bet/ </p>
				<span class="font-semibold text-blue-500">List-Of-Bets </span>
				</div>
				<small><?php echo $currentDate ?></small>
			</div>
			<form method="POST" class="form-inline" target="_blank" action="print/printMoneyonhand.php" id="frmgeneratereport">
				<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
				<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
				<button type = "button" class="text-base text-white font-semibold px-6 py-2 rounded-full bg-blue-500" id = "sbmtSummaryReport"><i class="fa fa-print"></i> Print Money on Hand</button>
			</form>
		</div>
		
		<div class="w-full grid grid-cols-2 md:grid-cols-5 lg:grid-cols-9 gap-4 items-start justify-arround mt-4 px-10 border-b pb-3">
			<a href="dashboard.php" class="text-sm text-center">Dashboard</a>
			<a href="staffBets.php" class="text-sm text-center">Place Bet</a>
			<a href="staffCurrentBetList.php" class="text-sm text-center">Current Bets</a>
			<a href="staffBetList.php" class="relative text-sm text-blue-500 font-semibold after:w-full after:h-[2px] after:rounded-full after:bg-blue-500 after:absolute after:-bottom-4 after:left-0 px-2 text-center">List Of Bets</a>   
			<a href="staffDeposit.php" class="text-sm text-center">Mobile Deposit</a>
			<a href="staffWithdraw.php" class="text-sm text-center">Mobile Withdraw</a>
			<button class="text-sm text-center">Teller Cash In</button>
			<button class="text-sm text-center">Teller Cash Out</button>
			<a href="staffTransactionHistory.php" class="text-sm text-center">Transaction History</a>
		</div>


		<div class="mt-4 w-full py-4 px-3 bg-white rounded-xl border">
			<!-- hidden -->
			<div class="hidden">
				<form method="post" target="_blank" action="print/reprintBetList.php" id="frmGenerateBarcode">
					<input type="hidden" name="barcode_text" id = "barcode_text">
					<input type="submit" name="generate_barcode" id = "sbmtGenerateBarcode" style="display:none;"value="GENERATE">
					</form>
				<form method="post" target="_blank" action="print/reprintPayout.php" id="frmReprintPayout">
					<input type="hidden" name="barcode_payout" id = "barcode_payout">
					<input type="submit" name="generate_reprintPayout" id = "sbmtReprintPayout" style="display:none;"value="GENERATE">
				</form>
				<input type = "hidden" id = "hiddenMobileNumber" />
				<input type = "hidden" id = "hiddenAccountID" />
			</div>


			<!-- table -->
			<table class="table table-bordered" id="example" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th class="text-sm" style="text-align:center;">#</th>
						<th class="text-sm" style="text-align:center;">Date</th>
						<th class="text-sm" style="text-align:center;">Teller</th>
						<th class="text-sm" style="text-align:center;">Fight #</th>
						<th class="text-sm" style="text-align:center;">Bettor</th>
						<th class="text-sm" style="text-align:center;">Bet Code</th>
						<th class="text-sm" style="text-align:center;">Bet Under</th>
						<th class="text-sm" style="text-align:center;">Betting Status</th>
						<th class="text-sm" style="text-align:center;">Amount</th>
						<th class="text-sm" style="text-align:center;">Result</th>
						<th class="text-sm" style="text-align:center;">Odds</th>
						<th class="text-sm" style="text-align:center;">Payout</th>
						<th class="text-sm" style="text-align:center;">Is Claimed?</th>
						<th class="text-sm" style="text-align:center;">Is Returned?</th>
					
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$qbets = $mysqli->query("SELECT a.`betCode`, a.`betType`, a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, a.`isCancelled`, b.`id`, b.`fightNumber`, b.`isWinner`, b.`isBetting`, b.`payoutMeron`, b.`payoutWala`, c.`betType` as betTypeStatus, d.`isBetting` as bettingStatus, e.`winner`, f.`mobileNumber`, ev.`eventDate`, u.username FROM `tblbetlists` a 
					LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
					LEFT JOIN `tblbettypes` c ON a.betType = c.id 
					LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
					LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
					LEFT JOIN `tblaccounts` f ON a.accountID = f.id 
					LEFT JOIN `tblevents` ev ON b.eventID = ev.id
					LEFT JOIN `tblusers` u ON a.userID = u.id
					WHERE accountID = '0' ORDER BY a.id DESC ");
					$oddsMeron = 0;
					$oddsWala = 0;
					$totalPayout = 0;
					if($qbets->num_rows > 0){
						$count = 1;
						$qfightlastID = $mysqli->query("select max(id) as lastid from tblfights;");
						while($rfightlastid = $qfightlastID->fetch_assoc()){
							$lastid = $rfightlastid['lastid'];
						}   
						while($rbets = $qbets->fetch_assoc()){
							$fightID = $rbets['id'];
							$oddsMeron = $rbets['payoutMeron'];
							$oddsWala = $rbets['payoutWala'];
							$isCancelled = $rbets['isCancelled'];
							
							if($isCancelled == 0){
								echo '
								<tr>
								<td class="text-sm" style="text-align:center;">'.$count.'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['eventDate'].'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['username'].'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['fightNumber'].'</td>';
								if($rbets['betRoleID'] == 3){
								echo '
									<td class="text-sm" style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
								}else{
									echo '
									<td class="text-sm" style="text-align:center;">TICKET</td>';
								}
								echo '
								<td class="text-sm">'.$rbets['betCode'].'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
								
								<td class="text-sm" style="text-align:center;">'.$rbets['bettingStatus'].'</td>
								<td class="text-sm" style="text-align:right;">'.number_format($rbets['betAmount']).'</td>';
									if($rbets['isWinner'] == 0){
										if($rbets['bettingStatus'] == "CANCELLED"){
											echo '
											<td class="text-sm" style="text-align:center;">CANCELLED</td>';
										}else{
											echo '
											<td class="text-sm" style="text-align:center;">UNSETTLED</td>';
										}
											echo '													
											<td class="text-sm" style="text-align:center;"></td>
											<td class="text-sm" style="text-align:center;"></td>';
										
									}else if($rbets['isWinner'] == 3){
										echo '
											<td class="text-sm" style="text-align:center;">'.$rbets['winner'].'</td>													
											<td class="text-sm" style="text-align:center;"></td>
											<td class="text-sm" style="text-align:center;"></td>';	
									}else{ 
										if($rbets['betTypeStatus'] == $rbets['winner']){
											echo '
											<td class="text-sm" style="text-align:center;">WIN</td>';
												if($rbets['betType'] == 1){
													echo '
													<td class="text-sm" style="text-align:center;">'.number_format($oddsMeron,2).'</td>';
													$totalPayout = ($rbets['betAmount'] / 100) * $oddsMeron;
												}else if($rbets['betType'] == 2){
													echo '
													<td class="text-sm" style="text-align:center;">'.number_format($oddsWala,2).'</td>';
													$totalPayout = ($rbets['betAmount'] / 100) * $oddsWala;
												}
											echo'
											<td class="text-sm" style="text-align:center;">'.number_format($totalPayout,2).'</td>';
										}else{
											echo '
											<td class="text-sm" style="text-align:center;">LOST</td>';
											if($rbets['betType'] == 1){
												echo '
												<td class="text-sm" style="text-align:center;">'.number_format($oddsMeron,2).'</td>';
											}else if($rbets['betType'] == 2){
												echo '
												<td class="text-sm" style="text-align:center;">'.number_format($oddsWala,2).'</td>';
											}else{
											echo'
											<td class="text-sm" style="text-align:center;"></td>';
											}
											echo'
											<td class="text-sm" style="text-align:center;"></td>';
										}
									}
								

									// FOR isClaim ROW
									if($rbets['bettingStatus'] == "CANCELLED"){
										if($rbets['isReturned'] == 1){
											echo '
											<td class="text-sm" style="text-align:center;">RETURNED</td>';
										}else{
											echo '
											<td class="text-sm" style="text-align:center;">FOR REFUND</td>';
										}
									}else{
										if($rbets['isClaim'] == 0){
										echo '
										<td class="text-sm" style="text-align:center;">NO</td>';
										}else{
										echo '
										<td class="text-sm" style="text-align:center;">YES</td>';
										}
									}
									// FOR isReturned ROW
									if($rbets['bettingStatus'] == "CANCELLED" || $rbets['isWinner'] == 3){
										if($rbets['isReturned'] == 1){
											echo '
											<td class="text-sm" style="text-align:center;">RETURNED</td>';
										}else{
											echo '
											<td class="text-sm" style="text-align:center;">FOR REFUND</td>';
										}
									}else{
											echo '
											<td class="text-sm" style="text-align:center;"></td>';
									}
									// FOR ACTIONS ROW
									if($rbets['betRoleID'] == 0){
									
												echo '
												<td class="text-sm">';
												/*
												echo '
												<button class="btn btn-primary btnreprint" value = "'.$rbets['betCode'].'"><i class="fa fa-print"></i> REPRINT</button>';
												*/
												
												if($rbets['betTypeStatus'] == $rbets['winner']){
													
												echo '
												<button class="text-xs text-white bg-blue-500 font-medium px-4 py-2 rounded-full btnreprintpayout" value = "'.$rbets['betCode'].'"><i class="fa fa-print"></i> REPRINT PAYOUT</button>';
													
												}else{
												
												}
												
												if($isCancelled == 1){
													echo '&nbsp;BET CANCELLED';	
												}else{
													
												}
											
												echo'
												</td>';
											
									}else{
										echo '
										<td class="text-sm"></td>';
									}	
									
								echo '
							</tr>';
							}else{
									echo '
								<tr>
								<td class="text-sm" style="text-align:center;">'.$count.'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['eventDate'].'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['username'].'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['fightNumber'].'</td>';
								if($rbets['betRoleID'] == 3){
								echo '
									<td class="text-sm" style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
								}else{
									echo '
									<td class="text-sm" style="text-align:center;">TICKET</td>';
								}
								echo '
								<td class="text-sm">'.$rbets['betCode'].'</td>
								<td class="text-sm" style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
								
								<td class="text-sm" style="text-align:center;">'.$rbets['bettingStatus'].'</td>
								<td class="text-sm" style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
								
								<td class="text-sm" style="text-align:right;">BET CANCELLED</td>
								<td class="text-sm" style="text-align:center;"></td>
								<td class="text-sm" style="text-align:center;"></td>
								<td class="text-sm" style="text-align:center;"></td>
								<td class="text-sm" style="text-align:center;"></td>
								<td class="text-sm"></td>
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












	 
    <!-- Bootstrap core JavaScript-->
	<script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="design/vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="design/vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="design/js/demo/datatables-demo.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="design/js/sb-admin-2.min.js"></script>
	<script type="text/javascript">
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
			$('table#example tbody').on('click', 'tr td .btnreprintpayout', function(){
				barcodeVal = $(this).val();
				
				$("#barcode_payout").val(barcodeVal);
				if(barcodeVal != ""){
					$("#sbmtReprintPayout").click();	
				}else{
					swal("Error, no barcode to reprint.","","error");
				}
			});
		});
	</script>

	<?php
		include("modalboxes.php");
		include("staffModals.php")
	?>
  </body>
</html>