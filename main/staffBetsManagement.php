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
							<div class="col-md-12">
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<h6 class="m-0 font-weight-bold text-primary">
											<a href="dashboard.php" class=""><button class="btn btn-primary">DASHBOARD</button></a>
											<a href="staffBets.php" class=""><button class="btn btn-warning">PLACE BET</button></a>
											<a href="staffCurrentBetList.php"><button class="btn btn-success">CURRENT BETS</button></a>							
											<a href="staffDeposit.php" class=""><button class="btn btn-info">MOBILE DEPOSIT</button></a>
											<a href="staffWithdraw.php" class=""><button class="btn btn-info">MOBILE WITHDRAW</button></a>
											<button class="btn" style = "background-color:brown; color:#FFF;" id="cashin">TELLER CASH IN</button>
											<button class="btn" style = "background-color:brown; color:#FFF;" id="cashoutteller">TELLER CASH OUT</button>
											<a href="staffTransactionHistory.php" class=""><button class="btn btn-primary">TRANSACTION HISTORY</button></a>
											<a href="staffBetsManagement.php" class=""><button class="btn btn-primary btn-lg" style="font-weight:bold; font-size:25px;">BETS REPORT</button></a>
										</h6>
										</h6>
									</div>
								</div>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<h6 class="m-0 font-weight-bold text-primary">BETS MANAGEMENT</h6>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-2">
												<div class="panel panel-success" style="border:2px solid #000; font-size:11px; padding:15px;">
													<div class="panel-heading">
														<h6 class="panel-title" style="font-weight:bolder;"><i class="ti-filter"></i> FILTER REPORT</h6>
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
																		<option value="currentfight">Current Fight Only</option>
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
																		<input id="date-range-picker" name = "daterange" type="text" value="<?php echo $currentDate ."-".$currentDate;?>" class="form-control">
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
													<div class="panel-heading">
														<h3 class="panel-title" style="font-weight:bolder;">BETS</h3>
													</div>
													<div class="panel-body">
														<div class="row">
															<div class="col-md-12">
																<table class="table table-bordered table-responsive" id = "tblbets">
																	<thead>
																		<tr class="active" style="border:2px solid #000;">
																			<th style="text-align:center; width:2%;">#</th>
																			<th style="text-align:center;">EVENT DATE</th>
																			<th style="text-align:center;">FIGHT CODE</th>
																			<th style="text-align:center;">FIGHT #</th>
																			<th style="text-align:center;">BETTOR</th>
																			<th style="text-align:center;">BET CODE</th>
																			<th style="text-align:center;">BET UNDER</th>
																			<th style="text-align:center;">BET STATUS</th>
																			<th style="text-align:center;">RESULT</th>
																			<th style="text-align:center;">BET AMOUNT</th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php
																	$totalBetAmount = 0;
																	   $qbets = $mysqli->query("SELECT ev.eventDate, a.`fightCode`, b.`fightNumber`, a.`betRoleID`, a.`betCode`, a.`isCancelled`, c.`betType` as betTypeStatus, d.`isBetting` as bettingStatus, e.`winner`, a.`betAmount`, a.`isClaim`, b.`isWinner`, b.`isBetting`, f.`mobileNumber` FROM `tblbetlists` a 
																	   LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode 
																	   LEFT JOIN `tblbettypes` c ON a.betType = c.id 
																	   LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id 
																	   LEFT JOIN `tblwinner` e ON b.isWinner = e.id 
																	   LEFT JOIN `tblaccounts` f ON a.accountID = f.id 
																	   LEFT JOIN `tblevents` ev ON b.eventID = ev.id
																	   WHERE ev.eventDate = (SELECT CURDATE()) ORDER BY a.id DESC ");
																	   if($qbets->num_rows > 0){
																		   $count = 1;
																		   
																		   while($rbets = $qbets->fetch_assoc()){
																			   $isCancelled = $rbets['isCancelled'];
																			  echo '
																			 <tr>
																				<td style="text-align:center;">'.$count.'</td>
																				<td style="text-align:center;">'.$rbets['eventDate'].'</td>
																				<td style="text-align:center;">'.$rbets['fightCode'].'</td>
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
																				
																				<td style="text-align:center;">'.$rbets['bettingStatus'].'</td>';
																					if($isCancelled == 0){
																					
																						if($rbets['isWinner'] == 0){
																							if($rbets['bettingStatus'] == "CANCELLED"){
																								echo '
																								<td style="text-align:center;">CANCELLED</td>';
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
																						echo '<td style="text-align:right;">'.number_format($rbets['betAmount'],2).'</td>';
																					}else{
																						echo '
																						<td style="text-align:center;">BET CANCELLED</td>
																						<td></td>';	
																					}

																					
																				echo '
																			</tr>';
																			if($isCancelled == 0){
																				$totalBetAmount += $rbets['betAmount'];
																			}else{
																				
																			}
																			$count++;
																		   }
																	   }
																	   ?>
																	   <tr>
																			<td colspan= "9" style="font-weight:bold;">TOTAL:</td>
																			<td style="font-weight:bold; text-align:right;"><?php echo number_format($totalBetAmount,2);?></td>
																		</tr>
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
			$("#reportBettorType").change(function(){
				$("#sbmtFilter").click();
			});
			$("#reportStatus").change(function(){
				$("#sbmtFilter").click();
			});
			$("#reportBetUnder").change(function(){
				$("#sbmtFilter").click();
			});
				
			$("#sbmtFilter").click(function() {
				sc = $("#reportScope").val();
				ryear = $("#reportYear").val();
				rmonth = $("#reportMonth").val();
				rangeVal = $("#date-range-picker").val();
				bettorTypeVal = $("#reportBettorType").val();
				bettingStatusVal = $("#reportStatus").val();
				bettingUnderVal = $("#reportBetUnder").val();
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
				$.post("reports/filterReport.php",{scope:sc, year:ryear, month:rmonth, rangeFrom:dateFrom, rangeTo:dateTo, bettorType:bettorTypeVal, bettingStatus:bettingStatusVal, bettingUnder:bettingUnderVal}, function(res){
					$("#tblbets tbody").html(res);
				});
			});	
		});
	</script>

	<?php
		include("modalboxes.php");
		include("staffModals.php")
	?>
  </body>
</html>