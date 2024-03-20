<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 2){ // 2 = STAFF
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
	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">	
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">

</head>
<body id="page-top">
	<div id="wrapper">
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item dropdown no-arrow mx-1" style="text-align:center; font-weight:bold; font-size:15px;">
							<?php echo $_SESSION['username']; ?>
						</li>
						<div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
								<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
									MENU
								</button>
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
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
						</li>
					</ul>
				</nav>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<h6 class="m-0 font-weight-bold text-primary">
											<a href="dashboard.php" class=""><button class="btn btn-primary">DASHBOARD</button></a>
											<a href="staffBets.php" class=""><button class="btn btn-warning">PLACE BET</button></a>
											<a href="staffCancelBets.php" class=""><button class="btn btn-danger">CANCEL BETS</button></a>
											<a href="staffCurrentBetList.php"><button class="btn btn-success btn-lg" style="font-weight:bold; font-size:25px;">CURRENT BETS</button></a>								
											<a href="staffDeposit.php" class=""><button class="btn btn-info">MOBILE DEPOSIT</button></a>
											<a href="staffWithdraw.php" class=""><button class="btn btn-info">MOBILE WITHDRAW</button></a>
											<button class="btn" style = "background-color:brown; color:#FFF;" id="cashinteller">TELLER CASH IN</button>
											<button class="btn" style = "background-color:brown; color:#FFF;" id="cashoutteller">TELLER CASH OUT</button>
										</h6>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<h6 class="m-0 font-weight-bold text-primary">LIST OF CURRENT FIGHT BETS</h6>
									</div>
									<div class="card-body">
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
													<th style="text-align:center;">Teller</th>
													<th style="text-align:center;">Fight #</th>
													<th style="text-align:center;">Bettor</th>
													<th style="text-align:center;">Bet Code</th>
													<th style="text-align:center;">Bet Under</th>
													<th style="text-align:center;">Amount</th>
													<th style="text-align:center;">Status</th>
													<th style="text-align:center;">Result</th>
													<th style="text-align:center;">Is Claimed?</th>
													<th style="text-align:center;">Is Returned?</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
											   <?php
											   $qbets = $mysqli->query("SELECT a.`betCode`, a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, a.`isCancelled`, b.`id`, b.`fightCode`, b.`fightNumber`, b.`isWinner`, b.`isBetting`, c.`betType` as betTypeStatus, d.`isBetting` as bettingStatus, e.`winner`, f.`mobileNumber`, ev.`eventDate`, u.`username` FROM `tblbetliststemp` a 
										   
											   LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode						   
											   LEFT JOIN `tblbettypes` c ON a.betType = c.id 
											   LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
											   LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
											   LEFT JOIN `tblaccounts` f ON a.accountID = f.id 
											   LEFT JOIN `tblevents` ev ON b.eventID = ev.id 	
												LEFT JOIN `tblusers` u ON a.userID = u.id
												WHERE a.userID = '".$_SESSION['companyID']."'
											   ORDER BY a.id DESC ");
												if($qbets->num_rows > 0){
													$count = 1;  
													while($rbets = $qbets->fetch_assoc()){
														$isCancelled = $rbets['isCancelled'];
														$isReturned = $rbets['isReturned'];
														$isClaimed = $rbets['isClaim'];
													 echo '
													 <tr>
														<td style="text-align:center;">'.$count.'</td>
														<td style="text-align:center;">'.DATE('M d, Y', strtotime($rbets['eventDate'])).'</td>
														<td style="text-align:center;">'.$rbets['username'].'</td>
														<td style="text-align:center;">'.$rbets['fightNumber'].'</td>';
														if($rbets['betRoleID'] == 3){
														echo '
															<td style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
														}else{
															echo '
															<td style="text-align:center;">TICKET</td>';
														}
														echo '
														<td>'.$rbets['betCode'].'</td>
														<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
														<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
														<td style="text-align:center;">'.$rbets['bettingStatus'].'</td>';
															if($rbets['isWinner'] == 0){
																if($rbets['bettingStatus'] == "CANCELLED"){
																	echo '
																	<td style="text-align:center;">CANCELLED</td>';
																}else if($rbets['bettingStatus'] == "OPEN"){
																	if($isCancelled == 1){
																		echo '
																		<td style="text-align:center;">CANCELLED</td>';
																	}else{
																		echo '
																		<td style="text-align:center;">UNSETTLED</td>';
																	}
																}else{
																	echo '
																	<td style="text-align:center;">UNSETTLED</td>';
																}	
															}else if($rbets['isWinner'] == 3){
																echo '
																	<td style="text-align:center;">'.$rbets['winner'].'</td>';
																
															}else{ 
																if($rbets['betTypeStatus'] == $rbets['winner']){
																	echo '
																	<td style="text-align:center;">WIN</td>';
																}else{
																	echo '
																	<td style="text-align:center;">LOST</td>';
																}
															}
															
															if($rbets['bettingStatus'] == "CANCELLED"){
																if($isClaimed == 0){
																echo '
																<td style="text-align:center;">NO</td>';
																}else{
																echo '
																<td style="text-align:center;">YES</td>';
																}
																
																if($rbets['isReturned'] == 1){
																	echo '
																	<td style="text-align:center;">RETURNED</td>';
																}else{
																	echo '
																	<td style="text-align:center;">FOR REFUND</td>';
																}
															}else if($rbets['bettingStatus'] == "OPEN" || $rbets['bettingStatus'] == "LAST CALL" || $rbets['bettingStatus'] == "CLOSED"){
																if($isClaimed == 0){
																echo '
																<td style="text-align:center;"></td>';
																}else{
																echo '
																<td style="text-align:center;">YES</td>';
																}
																
																if($rbets['isReturned'] == 1){
																	echo '
																	<td style="text-align:center;">RETURNED</td>';
																}else{
																	echo '
																	<td style="text-align:center;"></td>';
																}
															}else{
																if($isClaimed == 0){
																echo '
																<td style="text-align:center;">NO</td>';
																}else{
																echo '
																<td style="text-align:center;">YES</td>';
																}
																
																if($rbets['isReturned'] == 1){
																	echo '
																	<td style="text-align:center;">RETURNED</td>';
																}else{
																	echo '
																	<td style="text-align:center;">FOR REFUND</td>';
																}
															}
															
															
															if($rbets['betRoleID'] == 0){
																echo 
																'<td>';
																if($rbets['isBetting'] == 1 || $rbets['isBetting'] == 4){
																	
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
			/*
			$('table#example tbody').on('click', 'tr td .btnreprint', function(){		
				barcodeVal = $(this).val();
				
				$("#barcode_text").val(barcodeVal);
				if(barcodeVal != ""){
					$("#sbmtGenerateBarcode").click();	
				}else{
					swal("Error, no barcode to reprint.","","error");
				}
			});
			*/
		});
	</script>

	<?php
		include("modalboxes.php");
		include("staffModals.php")
	?>
  </body>
</html>