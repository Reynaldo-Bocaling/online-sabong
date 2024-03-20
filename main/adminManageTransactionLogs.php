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
							<h6 class="m-0 font-weight-bold text-warning">Bettings Management: Transaction History</h6>
						</div>
						<div class="card-body">
							<?php
							echo '
							<a href="adminManageBettings.php" class=""><button class="btn btn-sm btn-primary"><i class="fas fa-book"></i> Current Fight Bets History</button></a>
							<a href="adminManageBetHistory.php" class=""><button class="btn btn-sm btn-success"><i class="fas fa-book"></i> Bets History</button></a>
							<a href="adminManageTransactionLogs.php" class=""><button class="btn btn-lg btn-warning"><i class="fas fa-book"></i> Transaction History</button></a><br/><br/>';
							?>
							<div class="table-responsive">
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
												<h6 class="m-0 font-weight-bold text-primary">TRANSACTIONS</h6>
											</div>
											<div class="card-body">
												<div class="table-responsive">
													<input type = "hidden" id = "hiddenMobileNumber" />
													<input type = "hidden" id = "hiddenAccountID" />
													<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
													  <thead>
														<tr>
															<th style="text-align:center;">#</th>
															<th style="text-align:center;">Mobile Number</th>
															<th style="text-align:center;">Type of Transaction</th>
															<th style="text-align:left;">Transaction Details</th>
															<th style="text-align:center;">Transaction Date</th>
														</tr>
													  </thead>
													  <tbody>
													   <?php
													   // $query = $mysqli->query("SELECT a.`accountID`, a.`dt`, c.`transaction`, b.`mobileNumber`, a.`transactionDetails`, d.`cname` FROM `tbltransactionlogs` a 
														//LEFT JOIN `tblaccounts` b ON a.accountID = b.id 
														//LEFT JOIN `tbltransaction` c ON a.transactionID = c.id 
														//LEFT JOIN `tblusers` d ON a.userID = d.id
														//WHERE (c.id = '1' || c.id = '2' || c.id = '3' || c.id = '4' || c.id = '8' || c.id = '9' || c.id = '10' || c.id = '11') ORDER BY a.id DESC");
														
													   $query = $mysqli->query("SELECT a.`accountID`, a.`dt`, c.`transaction`, b.`mobileNumber`, a.`transactionDetails`, d.`cname` FROM `tbltransactionlogs` a 
														LEFT JOIN `tblaccounts` b ON a.accountID = b.id 
														LEFT JOIN `tbltransaction` c ON a.transactionID = c.id 
														LEFT JOIN `tblusers` d ON a.userID = d.id
														WHERE  c.id = '8' ORDER BY a.id DESC");
													   if($query->num_rows > 0){
														   $count = 1;
														   while($row = $query->fetch_assoc()){
															  echo '
															 <tr>
																<td style="text-align:center;">'.$count.'</td>';
																if($row['accountID'] == 0){
																	echo'
																	<td style="text-align:center;">'.$row['cname'].'</td>';
																}else{
																	echo'
																	<td style="text-align:center;">'.$row['mobileNumber'].'</td>';
																}	
																echo '<td style="text-align:center;">'.$row['transaction'].'</td>
																<td style="text-align:left;">'.$row['transactionDetails'].'</td>
																<td style="text-align:left;">'.$row['dt'].'</td>
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
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript">
		function reloadPage(){ 
			location.reload();
		}
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>