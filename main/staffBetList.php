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
		<!-- Custom fonts for this template-->
		<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
		<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link rel="stylesheet" href="design/dist/sweetalert.css">
		<script src="design/dist/sweetalert.js"></script>
	</head>
	<body id="page-top">
  <!-- Page Wrapper -->
		<div id="wrapper">
    <!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
				<div id="content">

			<!-- Topbar -->
					<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
						<!-- Topbar Navbar -->
						<ul class="navbar-nav ml-auto">
							<li class="nav-item dropdown no-arrow mx-1" style="text-align:center; font-weight:bold; font-size:15px;">
								<?php echo $_SESSION['username']; ?>
							</li>
							 <div class="topbar-divider d-none d-sm-block"></div>

							<!-- Nav Item - User Information -->
							<li class="nav-item dropdown no-arrow">
								<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
								<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
									MENU
								</button>
							</a>
						  <!-- Dropdown - User Information -->
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
			
					 <!-- Begin Page Content -->
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-10">
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<a href="dashboard.php" class=""><button class="btn btn-sm btn-primary">DASHBOARD</button></a>
										<a href="staffBets.php" class=""><button class="btn btn-sm btn-warning" style="font-weight:bold;">PLACE BET</button></a>
										<a href="staffCurrentBetList.php"><button class="btn btn-sm btn-success" >CURRENT BETS</button></a>
										<a href="staffBetList.php" class=""><button class="btn btn-xs btn-success">LIST OF BETS</button></a>									
										<a href="staffDeposit.php" class=""><button class="btn btn-sm btn-info">MOBILE DEPOSIT</button></a>
										<a href="staffWithdraw.php" class=""><button class="btn btn-sm btn-info">MOBILE WITHDRAW</button></a>
										<button class="btn btn-sm" style = "background-color:brown; color:#FFF;" id="cashin">TELLER CASH IN</button>
										<button class="btn btn-sm" style = "background-color:brown; color:#FFF;" id="cashoutteller">TELLER CASH OUT</button>
										<a href="staffTransactionHistory.php" class=""><button class="btn btn-sm btn-primary">TRANSACTION HISTORY</button></a>
										<!--<a href="staffBetsManagement.php" class=""><button class="btn btn-sm btn-primary">BETS REPORT</button></a>-->
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<form method="POST" class="form-inline" target="_blank" action="print/printMoneyonhand.php" id="frmgeneratereport">
									<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
									<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
									<button type = "button" class="btn btn-primary btn-lg" id = "sbmtSummaryReport" style="font-size:25px; font-weight:bold; width:100%;"><i class="fa fa-print"></i> Print<br/>Money on Hand</button>
								</form>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<h6 class="m-0 font-weight-bold text-primary">LIST OF BETS</h6>
									</div>
									<div class="card-body">
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
													<th style="text-align:center;">Betting Status</th>
													<th style="text-align:center;">Amount</th>
													<th style="text-align:center;">Result</th>
													<th style="text-align:center;">Odds</th>
													<th style="text-align:center;">Payout</th>
													<th style="text-align:center;">Is Claimed?</th>
													<th style="text-align:center;">Is Returned?</th>
												
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
															<td style="text-align:center;">'.$count.'</td>
															<td style="text-align:center;">'.$rbets['eventDate'].'</td>
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
															
															<td style="text-align:center;">'.$rbets['bettingStatus'].'</td>
															<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>';
																if($rbets['isWinner'] == 0){
																	if($rbets['bettingStatus'] == "CANCELLED"){
																		echo '
																		<td style="text-align:center;">CANCELLED</td>';
																	}else{
																		echo '
																		<td style="text-align:center;">UNSETTLED</td>';
																	}
																		echo '													
																		<td style="text-align:center;"></td>
																		<td style="text-align:center;"></td>';
																	
																}else if($rbets['isWinner'] == 3){
																	echo '
																		<td style="text-align:center;">'.$rbets['winner'].'</td>													
																		<td style="text-align:center;"></td>
																		<td style="text-align:center;"></td>';	
																}else{ 
																	if($rbets['betTypeStatus'] == $rbets['winner']){
																		echo '
																		<td style="text-align:center;">WIN</td>';
																		 if($rbets['betType'] == 1){
																			 echo '
																			 <td style="text-align:center;">'.number_format($oddsMeron,2).'</td>';
																			   $totalPayout = ($rbets['betAmount'] / 100) * $oddsMeron;
																		   }else if($rbets['betType'] == 2){
																			  echo '
																			  <td style="text-align:center;">'.number_format($oddsWala,2).'</td>';
																			   $totalPayout = ($rbets['betAmount'] / 100) * $oddsWala;
																		   }
																		echo'
																		<td style="text-align:center;">'.number_format($totalPayout,2).'</td>';
																	}else{
																		echo '
																		<td style="text-align:center;">LOST</td>';
																		if($rbets['betType'] == 1){
																		 echo '
																		 <td style="text-align:center;">'.number_format($oddsMeron,2).'</td>';
																		}else if($rbets['betType'] == 2){
																		 echo '
																		 <td style="text-align:center;">'.number_format($oddsWala,2).'</td>';
																		}else{
																		echo'
																		<td style="text-align:center;"></td>';
																		}
																		echo'
																		<td style="text-align:center;"></td>';
																	}
																}
															
						
																// FOR isClaim ROW
																if($rbets['bettingStatus'] == "CANCELLED"){
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
																// FOR isReturned ROW
																if($rbets['bettingStatus'] == "CANCELLED" || $rbets['isWinner'] == 3){
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
																// FOR ACTIONS ROW
																if($rbets['betRoleID'] == 0){
																
																			echo '
																			<td>';
																			/*
																			echo '
																			<button class="btn btn-primary btnreprint" value = "'.$rbets['betCode'].'"><i class="fa fa-print"></i> REPRINT</button>';
																			*/
																			
																			if($rbets['betTypeStatus'] == $rbets['winner']){
																				
																			echo '
																			<button class="btn btn-success btnreprintpayout" value = "'.$rbets['betCode'].'"><i class="fa fa-print"></i> REPRINT PAYOUT</button>';
																				
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
																	<td></td>';
																}	
																
															echo '
														</tr>';
														}else{
															 echo '
														 <tr>
															<td style="text-align:center;">'.$count.'</td>
															<td style="text-align:center;">'.$rbets['eventDate'].'</td>
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
															
															<td style="text-align:center;">'.$rbets['bettingStatus'].'</td>
															<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
															
															<td style="text-align:right;">BET CANCELLED</td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>
															<td></td>
														</tr>';
														}
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