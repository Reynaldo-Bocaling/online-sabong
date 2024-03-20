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
				<div class="container">
					<div class="row">
						<div class="col-xl-12 col-md-12 mb-12">
							<div class="card shadow mb-12">
								<div class="card-header py-12">
									<div class="row">
										<div class="col-md-12">
											<div class="panel panel-success" style="border:2px solid #000; font-size:11px; padding:15px;">
												<div class="panel-heading">
													<h3 class="panel-title" style="font-weight:bolder;"><i class="ti-filter"></i> REPORT GENERATION</h3>
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
																	<option value="today">Today</option>
																	<option value="range">Date Range</option>
																	<option value="currentfight">Current Fight Only</option>
																</select>
																<span id="con_yearly" style="display:none;">
																	<span style='font-weight:bolder; font-size:13px;'>Year:</span><br>
																	<select id="reportYear" name="reportYear" class="form-control">
																		<option value = "<?php echo $currentYear?>"><?php echo $currentYear?></option>
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
																	<input id="date-range-picker" name = "daterange" type="text" value="10/01/2020 - 10/30/2020" class="form-control">
																</span>
															</div>
														</div>
														<div class="col-md-12">	
															<div class="form-group">
																<span style='font-weight:bolder; font-size:13px;'>BETTOR TYPE:</span><br>
																<select id="reportBettorType" name="reportBettorType" class="form-control">
																	<option value = "all">All</option>
																	<option value = "0">Ticket</option>
																	<option value = "1">Cellphone</option>
																	
																
																</select>
															</div>
														</div>
														<div class="col-md-12">	
															<div class="form-group">
																<span style='font-weight:bolder; font-size:13px;'>BETTING STATUS:</span><br>
																<select id="reportStatus" name="reportStatus" class="form-control">
																	<option value = "all">All</option>
																	<?php
																	$qstatus = $mysqli->query("SELECT * FROM tblbettingstatus");
																	
																	while($rstatus = $qstatus->fetch_assoc()){
																		echo '<option value = "'.$rstatus['id'].'">'.$rstatus['isBetting'].'</option>';
																	}
																	?>
																
																</select>
															</div>
														</div>
														
														<div class="col-md-12">	
															<div class="form-group">
																<span style='font-weight:bolder; font-size:13px;'>BETTING UNDER: <span style="color:red">applicable for bet history only</span></span><br>
																<select id="reportBetUnder" name="reportBetUnder" class="form-control">
																	<option value = "all">All</option>
																	<?php
																	$qbettype = $mysqli->query("SELECT * FROM tblbettypes");
																	
																	while($rbettype = $qbettype->fetch_assoc()){
																		echo '<option value = "'.$rbettype['id'].'">'.$rbettype['betType'].'</option>';
																	}
																	?>
																</select>
															</div>
														</div>
														<div class="col-md-12">
															<div class="form-group">
																<form method="post" target="_blank" action='reports/fightHistory.php' id='target_report1'>
																	<input type="hidden" id = "report1HiddenScope" name = "report1HiddenScope">
																	<input type="hidden" id = "report1HiddenYear" name = "report1HiddenYear">
																	<input type="hidden" id = "report1HiddenMonth" name = "report1HiddenMonth">
																	<input type="hidden" id = "report1HiddenRangeFrom" name = "report1HiddenRangeFrom">
																	<input type="hidden" id = "report1HiddenRangeTo" name = "report1HiddenRangeTo">
																	<input type="hidden" id = "report1HiddenBettorType" name = "report1HiddenBettorType">
																	<input type="hidden" id = "report1HiddenStatus" name = "report1HiddenStatus">
																	<input type="button" id = "sbmtReport1" class="btn btn-raised btn-info" style="font-size:15px; font-weight:bold; width:100%;" value = "FIGHT SUMMARY">
																</form>
															</div>
														</div>
														<div class="col-md-12" style=" text-align:right;">
															<div class="form-group">
																<form method="post" target="_blank" action='reports/betHistory.php' id='target_report2'>
																	<input type="hidden" id = "report2HiddenScope" name = "report2HiddenScope">
																	<input type="hidden" id = "report2HiddenYear" name = "report2HiddenYear">
																	<input type="hidden" id = "report2HiddenMonth" name = "report2HiddenMonth">
																	<input type="hidden" id = "report2HiddenRangeFrom" name = "report2HiddenRangeFrom">
																	<input type="hidden" id = "report2HiddenRangeTo" name = "report2HiddenRangeTo">
																	<input type="hidden" id = "report2HiddenBettorType" name = "report2HiddenBettorType">
																	<input type="hidden" id = "report2HiddenStatus" name = "report2HiddenStatus">
																	<input type="hidden" id = "report2HiddenBetUnder" name = "report2HiddenBetUnder">
																	<input type="button" id = "sbmtReport2" class="btn btn-raised btn-primary" style="font-size:15px; font-weight:bold;width:100%;" value = "BET HISTORY">
																</form>
															</div>
														</div>
														<div class="col-md-12" style=" text-align:right;">
															<div class="form-group">
																<form method="post" target="_blank" action='reports/incomeHistory.php' id='target_report3'>
																	<input type="hidden" id = "report3HiddenScope" name = "report3HiddenScope">
																	<input type="hidden" id = "report3HiddenYear" name = "report3HiddenYear">
																	<input type="hidden" id = "report3HiddenMonth" name = "report3HiddenMonth">
																	<input type="hidden" id = "report3HiddenRangeFrom" name = "report3HiddenRangeFrom">
																	<input type="hidden" id = "report3HiddenRangeTo" name = "report3HiddenRangeTo">
																	<input type="button" id = "sbmtReport3" class="btn btn-raised btn-success" style="font-size:15px; font-weight:bold;width:100%;" value = " INCOME HISTORY">
																</form>
															</div>
														</div>

														<div class="col-md-12" style=" text-align:right;">
															<div class="form-group">
																<form method="post" target="_blank" action='reports/depositHistory.php' id='target_report4'>
																	<input type="hidden" id = "report4HiddenScope" name = "report4HiddenScope">
																	<input type="hidden" id = "report4HiddenYear" name = "report4HiddenYear">
																	<input type="hidden" id = "report4HiddenMonth" name = "report4HiddenMonth">
																	<input type="hidden" id = "report4HiddenRangeFrom" name = "report4HiddenRangeFrom">
																	<input type="hidden" id = "report4HiddenRangeTo" name = "report4HiddenRangeTo">
																	<input type="button" id = "sbmtReport4" class="btn btn-raised btn-success" style="font-size:15px; font-weight:bold; width:100%;" value = " DEPOSIT HISTORY">
																</form>
															</div>
														</div>
														
														<div class="col-md-12" style=" text-align:right;">
															<div class="form-group">
																<form method="post" target="_blank" action='reports/withdrawalHistory.php' id='target_report5'>
																	<input type="hidden" id = "report5HiddenScope" name = "report5HiddenScope">
																	<input type="hidden" id = "report5HiddenYear" name = "report5HiddenYear">
																	<input type="hidden" id = "report5HiddenMonth" name = "report5HiddenMonth">
																	<input type="hidden" id = "report5HiddenRangeFrom" name = "report5HiddenRangeFrom">
																	<input type="hidden" id = "report5HiddenRangeTo" name = "report5HiddenRangeTo">
																	<input type="button" id = "sbmtReport5" class="btn btn-raised btn-danger" style="font-size:15px; font-weight:bold; width:100%;" value = " WITHDRAWAL HISTORY">
																</form>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div> <!-- end of panel for clients information-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.container-fluid -->
			</div>
			<!-- End of Main Content -->
		</div>
		<!-- End of Content Wrapper -->
	</div>
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
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>