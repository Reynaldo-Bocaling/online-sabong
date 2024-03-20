<?php
	session_start();
	require('includes/connection.php');
	if($_SESSION['roleID'] == 2){ // 2 = STAFF
		$staffFor = $_SESSION['staffFor'];
		$q = $mysqli->query("SELECT CURRENT_DATE() as curdate;");
		$r = $q->fetch_assoc();
		$curdate = $r['curdate'];
		$currentDate = DATE("M/d/Y", strtotime($curdate));
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
	<script src="design/dist/sweetalert.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
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
										<a href="staffBetList.php" class=""><button class="btn btn-sm btn-success">LIST OF BETS</button></a>									
										<a href="staffDeposit.php" class=""><button class="btn btn-sm btn-info">MOBILE DEPOSIT</button></a>
										<a href="staffWithdraw.php" class=""><button class="btn btn-sm btn-info">MOBILE WITHDRAW</button></a>
										<button class="btn btn-sm" style = "background-color:brown; color:#FFF;" id="cashin">TELLER CASH IN</button>
										<button class="btn btn-sm" style = "background-color:brown; color:#FFF;" id="cashoutteller">TELLER CASH OUT</button>
										<a href="staffTransactionHistory.php" class=""><button class="btn btn-xs btn-primary">TRANSACTION HISTORY</button></a>
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
										<h6 class="m-0 font-weight-bold text-primary"><?php echo strtoupper($_SESSION['username']); ?> TELLER TRANSACTIONS</h6>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-2">
												<div class="panel panel-primary" style="border:2px solid #000; font-size:11px; padding:15px;">
													<div class="panel-heading">
														<h6 class="panel-title" style="font-weight:bolder;"><i class="ti-filter"></i> FILTER</h6>
													</div>
													<div class="panel-body">
														<div class="row">
															<div class="col-md-12">	
																<div class="form-group">
																	<label for="" style="font-weight:bolder; color:#1f364f;">Select Scope to display:</label>	
																	<select id="reportScope" name="reportScope" class="form-control"  style="font-size:15px; font-weight:bolder;">
																		<option value="all">All</option>
																		<option value="year">Year</option>
																		<option value="monthly">Monthly</option>
																		<option value="this_month">This Month</option>
																		<option value="today" SELECTED>Today</option>
																		<option value="range">Date Range</option>
																	</select>
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
																	<span id="con_range" style="display:none;">
																	<br/>
																		<label style="font-weight:bolder; color:#1f364f;">Date Range Picker</label>
																		<input id="date-range-picker" name = "daterange" type="text" value="02/10/2021 - 03/30/2021" class="form-control">
																	</span>
																</div>
															</div>
															<div class="col-md-12">	
																<div class="form-group">
																	<span style='font-weight:bolder; font-size:13px;'>TRANSACTION TYPE:</span><br>
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
															</div>
															<div class="col-md-12" style=" text-align:right;">
																<div class="form-group">
																	<button type = "button" class="btn btn-success btn-lg" id = "sbmtFilter" style="font-size:15px; font-weight:bold; width:100%;"><i class="ti-filter"></i> &nbsp;FILTER</button>
																</div>
															</div>
														</div>
													</div>

												</div>
											</div>
											<div class="col-md-10">
												<div class="panel panel-default">
													<div class="panel-body">
														<div class="row">
															<div class="col-md-12">
																<table class="table table-bordered table-responsive" id = "tblbets">
																	<thead>
																		<tr class="active" style="border:2px solid #000;">
																			<th style="text-align:center; width:2%;">#</th>
																			<th style="text-align:center;">TRANSACTION TYPE</th>
																			<th style="text-align:center;">AMOUNT</th>
																			<th style="text-align:center;">TRANSACTION CODE</th>
																			<th style="text-align:center;">TRANSACTION DATE</th>
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
																					<td style="text-align:center;">'.$count.'</td>
																					<td style="text-align:center;">'.$row['transaction'].'</td>
																					<td style="text-align:right;">'.number_format($row['totalAmount'],2).'</td>
																					<td style="text-align:center;">'.$row['transactionCode'].'</td>
																					<td style="text-align:center;">'.DATE("M d, Y", strtotime($row['eventDate'])).'</td>
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
																				if($transactionID == 8){ //cash out
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				if($transactionID == 9){ //betCanclled
																					$totalBetAmount -= $row['totalAmount'];
																				}
																				
																				
																				$count++;
																			 }
																			echo '
																				<tr>
																					<td colspan = "2" style="font-weight:bold;">TOTAL:</td>
																					<td style="font-weight:bold; text-align:right;">'.number_format($totalBetAmount,2).'</td>
																					<td colspan = "2" style="font-weight:bold; text-align:right;"></td>
																				</tr>';
																		}
																		?>
																	</tbody>
																</table>
															</div><!--end of div col-md-12 -->
														</div>
														
													</div>
												</div>
											</div>
										</div>
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
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript" src="assets/newjscss/moment.js"></script>
    <!-- Bootstrap Date Range Picker-->
    <script type="text/javascript" src="assets/newjscss/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/newjscss/daterangepicker.css" />
	<script>
		function reloadPage(){ 
			location.reload();
		}
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
				$("#sbmtFilter").click();
			});
			$( "#date-range-picker" ).daterangepicker();
			$("#reportTransactionType").change(function(){
				$("#sbmtFilter").click();
			});

			$("#sbmtFilter").click(function() {
				sc = $("#reportScope").val();
				ryear = $("#reportYear").val();
				rmonth = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				transactionTypeVal = $("#reportTransactionType").val();
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				
				
				dateVal1 = $('input[name="daterange"]').val();
				dateValA = dateVal1.slice(0, 10);
				dateValB = dateVal1.slice(12, 23);
				var dateFrom = moment(dateValA, 'MM-DD-YYYY').format('YYYY-MM-DD');
				var dateTo = moment(dateValB, 'MM-DD-YYYY').format('YYYY-MM-DD');
				$.post("reports/tellerTransactionHistory.php",{scope:sc, year:ryear, month:rmonth, rangeFrom:dateFrom, rangeTo:dateTo, transactionType:transactionTypeVal}, function(res){
					$("#tblbets tbody").html(res);
				});
			});	
		
			$("#sbmtSummaryReport").click(function(){
				$("#generate_summaryreport").click();
			});
		
		});
	</script>

	<?php
		include("modalboxes.php");
		include("staffModals.php")
	?>
  </body>
</html>