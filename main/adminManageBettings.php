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
						<h6 class="m-0 font-weight-bold text-primary">Bettings Management: Current Fight Bets History</h6>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="table-responsive">
									<input type = "hidden" id = "hiddenAccountID" />	
									<?php
									echo '
										<a href="adminManageBettings.php" class=""><button class="btn btn-lg btn-primary"><i class="fas fa-book"></i> Current Fight Bets History</button></a>
										<a href="adminManageBetHistory.php" class=""><button class="btn btn-sm btn-success"><i class="fas fa-book"></i> Bets History</button></a>
										<a href="adminManageTransactionLogs.php" class=""><button class="btn btn-sm btn-warning"><i class="fas fa-book"></i> Transaction History</button></a><br/><br/>';
									?>
									<form method="post" target="_blank" action="print/reprintBet.php" id="frmGenerateBarcode">
										<input type="hidden" name="barcode_text" id = "barcode_text">
										<input type="submit" name="generate_barcode" id = "sbmtGenerateBarcode" style="display:none;"value="GENERATE">
									</form>
									<input type = "hidden" id = "hiddenMobileNumber" />
									<input type = "hidden" id = "hiddenAccountID" />
									<table class="table table-bordered" id="example" width="100%" cellspacing="0">
										<thead>
											<tr>
												<th style="text-align:center;">#</th>
												<th style="text-align:center;">Date</th>
												<th style="text-align:center;">Fight Number</th>
												<th style="text-align:center;">Bettor</th>
												<th style="text-align:center;">Bet Code</th>
												<th style="text-align:center;">Bet Under</th>
												<th style="text-align:center;">Amount</th>
												<th style="text-align:center;">Betting Status</th>
												<th style="text-align:center;">Result</th>
												<th style="text-align:center;">Is Claimed?</th>
												<th style="text-align:center;">Is Returned?</th>
												<th style="text-align:center;">Actions</th>
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
												<td style="text-align:center;">'.$count.'</td>
												<td style="text-align:center;">'.DATE('M d, Y', strtotime($rbets['eventDate'])).'</td>
												<td style="text-align:center;">'.$rbets['fightNumber'].'</td>';
												if($rbets['betRoleID'] == 3){
													echo '<td style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
												}else{
													echo '
													<td style="text-align:center;">TICKET</td>';
												}
												echo '
												<td>'.$betCode.'</td>
												<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
												<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
												<td style="text-align:center;">'.$rbets['isBetting'].'</td>';
													if($rbets['isWinner'] == 0){
														if($rbets['isBetting'] == "CANCELLED"){
															echo '
															<td style="text-align:center;">CANCELLED</td>';
														}else{
															echo '
															<td style="text-align:center;">UNSETTLED</td>';
														}	
													}else if($rbets['isWinner'] == 3){
														echo '
															<td style="text-align:center;">DRAW</td>';
													}else{ 
														if($rbets['betTypeStatus'] == $rbets['winner']){
															echo '
															<td style="text-align:center;">WIN</td>';
														}else{
															echo '
															<td style="text-align:center;">LOST</td>';
														}
													}
												if($rbets['isBetting'] == "CANCELLED"){
													if($rbets['isReturned'] == 1){
														echo '
														<td style="text-align:center;">RETURNED</td>';
													}else{
														echo '
														<td style="text-align:center;">FOR REFUND</td>';
													}
												}else{
													if($rbets['isClaim'] == 0){
													echo '
													<td style="text-align:center;">NO</td>';
													}else{
													echo '
													<td style="text-align:center;">YES</td>';
													}
												}
												if($rbets['isBetting'] == "CANCELLED" || $rbets['isWinner'] == 3){
													if($rbets['isReturned'] == 1){
														echo '
														<td style="text-align:center;">RETURNED</td>';
													}else{
														echo '
														<td style="text-align:center;">FOR REFUND</td>';
													}
												}else{
													echo '
													<td style="text-align:center;"></td>';
												}
												if($rbets['betRoleID'] == 0){
													echo 
													'<td>';
													if($rbets['isBettingID'] == 1 || $rbets['isBettingID'] == 4){
														echo '
														<button class="btn btn-primary btnreprint" value = "'.$rbets['betCode'].'"><i class="fa fa-print"></i> REPRINT</button>';
													}else{
														
													}
													if($isCancelled == 1){
														echo '&nbsp;BET CANCELLED';	
													}else{
													}
													echo
													'</td>';
												}else{
													echo 
													'<td>';
													if($isCancelled == 1){
														echo '&nbsp;BET CANCELLED';	
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>