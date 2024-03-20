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
</head>
<body id="page-top">
	<div id="wrapper">
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">		
						<li class="nav-item dropdown no-arrow mx-1" style="text-align:center;">    
							<br/>	<?php echo $_SESSION['cname']; ?>
						</li>
						<div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
								<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
									<i class="fa fa-star"></i><i class="fa fa-bars"></i><i class="fa fa-star"></i>
								</button>
							</a>
							<?php
								include('includes/header.php');
							?>
						</li>
					</ul>
				</nav>
				<div class="container-fluid">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-success">Bettings Management: Bets History</h6>
						</div>
						<div class="card-body">
							<div class="table-responsive">
							<?php
							echo '
								<a href="adminManageBettings.php" class=""><button class="btn btn-sm btn-primary"><i class="fas fa-book"></i> Current Fight Bets History</button></a>
								<a href="adminManageBetHistory.php" class=""><button class="btn btn-lg btn-success"><i class="fas fa-book"></i> Bets History</button></a>
								<a href="adminManageTransactionLogs.php" class=""><button class="btn btn-sm btn-warning"><i class="fas fa-book"></i> Transaction History</button></a><br/><br/>';
							?>
								<div class="row">
									<div class="col-md-2">
										<div class="panel panel-success" style="border:2px solid #000; font-size:11px; padding:15px;">
											<div class="panel-heading">
												<h6 class="panel-title" style="font-weight:bolder;"><i class="ti-filter"></i> FILTER HISTORY</h6>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-md-12">	
														<div class="form-group">		
															<span id="con_monthly">
																<span style='font-weight:bolder; font-size:10px;'>FIGHT NUMBER:</span><br>
																<select name="ffightcode" id="ffightcode" class="form-control" style="width:98%;">
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
															</span>	
														</div>
													</div>
													<div class="col-md-12">	
														<div class="form-group">
															<span style='font-weight:bolder; font-size:10px;'>TELLER:</span><br>
															<select id="fteller" name="fteller" class="form-control">
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
													</div>
													<div class="col-md-12">	
														<div class="form-group">
															<span style='font-weight:bolder; font-size:10px;'>BET RECEIPT CODE:</span><br>
															<input type="text" id="fbetcode" class="form-control" name="fbetcode"  style="width:100%; background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder;" maxlength= "14" AUTOCOMPLETE = "OFF" />
																		
														</div>
													</div>
													<div class="col-md-12">	
														<div class="form-group">
															<span style='font-weight:bolder; font-size:10px;'>BET UNDER(MERON/WALA):</span><br>
															<select id="fbettype" name="fbettype" class="form-control">
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
													</div>
													<div class="col-md-12">	
														<div class="form-group">
															<span style='font-weight:bolder; font-size:10Px;'>FIGHT RESULT/WINNER:</span><br>
															<select id="fresult" name="fresult" class="form-control">
																<option value = "ALL">ALL</option>
																<option value = "1">MERON</option>
																<option value = "2">WALA</option>
																<option value = "3">DRAW</option>
																<option value = "0">CANCELLED</option>
															</select>
														</div>
													</div>
													<div class="col-md-12">	
														<div class="form-group">
															<span style='font-weight:bolder; font-size:10px;'>RECEIPT CLAIM STATUS:</span><br>
															<select id="fclaim" name="fclaim" class="form-control">
																<option value = "ALL">ALL</option>
																<option value = "1">CLAIMED</option>
																<option value = "0">UNCLAIMED</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>				
									</div>
									<div class="col-md-10">
										<div class="card shadow mb-4">
											<div class="card-header py-3">
												<h6 class="m-0 font-weight-bold text-primary">BETS</h6>
											</div>
											<div class="card-body">
												<div class="table-responsive">
													<input type = "hidden" id = "hiddenMobileNumber" />
													<input type = "hidden" id = "hiddenAccountID" />
													<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
														<thead>
															<tr>
																<th style="text-align:center;">#</th>
																<th style="text-align:center;">Fight #</th>
																<th style="text-align:center;">Teller Username</th>
																<th style="text-align:center;">Bet Code</th>
																<th style="text-align:center;">Bet Under</th>
																<th style="text-align:center;">Amount</th>
																<th style="text-align:center;">Status</th>
																<th style="text-align:center;">Result</th>
																<th style="text-align:center;">Is Claimed?</th>
																<th style="text-align:center;">Is Returned?</th>
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
																			<td style="text-align:center;">'.$count.'</td>
																			<td style="text-align:center;">'.$rbets['fightNumber'].'</td>';
																			
																
																			if($rbets['betRoleID'] == 3){
																				echo '<td style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
																			}else{
																				echo '
																				<td style="text-align:center;">TICKET - '.$rbets['username'].'</td>';
																			}
																			echo '
																			<td>'.$rbets['betCode'].'</td>
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
																				if($isClaimed == 1){
																					echo '
																					<td style="text-align:center;">YES</td>';
																				}else{
																					echo '
																					<td style="text-align:center;">NO</td>';
																				}
																				
																				if($isReturned == 1){
																					echo '
																					<td style="text-align:center;">RETURNED</td>';
																				}else{
																					echo '
																					<td style="text-align:center;">FOR REFUND</td>';
																				}
																			}else{
																				if($isClaimed == 0){
																				echo '
																				<td style="text-align:center;">NO</td>
																				<td style="text-align:center;"></td>';
																				}else{
																				echo '
																				<td style="text-align:center;">YES</td>
																				<td style="text-align:center;"></td>';
																				}
																			}
																	
																			echo '
																		</tr>';
																	}else{
																	   echo '
																		 <tr>
																			<td style="text-align:center;">'.$count.'</td>
																			<td style="text-align:center;">'.$rbets['eventDate'].'</td>
																			<td style="text-align:center;">'.$rbets['fightNumber'].'</td>';
																			if($rbets['betRoleID'] == 3){
																				echo '<td style="text-align:center;">'.$rbets['mobileNumber'].'</td>';
																			}else{
																				echo '
																				<td style="text-align:center;">TICKET - '.$rbets['username'].'</td>';
																			}
																			echo '
																			<td>'.$rbets['betCode'].'</td>
																			<td style="text-align:center;">'.$rbets['betTypeStatus'].'</td>
																			<td style="text-align:right;">'.number_format($rbets['betAmount']).'</td>
																			<td style="text-align:center;">'.$rbets['isBetting'].'</td>
																			<td style="text-align:center;">BET CANCELLED</td>';
																			if($isClaimed == 0){
																				echo '
																				<td style="text-align:center;">NO</td>';
																				}else{
																				echo '
																				<td style="text-align:center;">YES</td>';
																				}
																			if($isReturned == 1){
																				echo '
																				<td style="text-align:center;">RETURNED</td>';
																			}else{
																				echo '
																				<td style="text-align:center;">FOR REFUND</td>';
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
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>
</html>