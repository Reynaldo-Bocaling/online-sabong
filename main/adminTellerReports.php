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
				<div class="row">
							<div class="col-md-12">
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<h6 class="m-0 font-weight-bold text-primary">TELLER TRANSACTIONS</h6>
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
																		<option value="all"  SELECTED>All</option>
																		<option value="year">Year</option>
																		<option value="monthly">Monthly</option>
																		<option value="this_month">This Month</option>
																		<option value="daily">Daily</option>
																		<option value="today">Today</option>
																		
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
																</div>
															</div>
															<div class="col-md-12">	
																<div class="form-group">
																	<span style='font-weight:bolder; font-size:13px;'>TELLER:</span><br>
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
													<div class="panel-foot">
														<div class="row">
															<div class="col-md-12">
																<form method="POST" class="form-inline" target="_blank" action="print/printAllTeller.php" id="frmgeneratereport">
																	
																	<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
																	<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
																	
																	<button type = "button" class="btn btn-primary btn-lg" id = "sbmtSummaryReport" style="font-size:15px; font-weight:bold; width:100%;"><i class="fa fa-print"></i> Print<br/>Money on Hand</button>
																</form>
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
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>