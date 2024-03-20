<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 13){
	
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
						<h6 class="m-0 font-weight-bold text-primary">Cash Handler</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<input type = "hidden" id = "hiddenMobileNumber" />
							<input type = "hidden" id = "hiddenAccountID" />
							<input type = "hidden" id = "hiddenTransCode" />
							<input type = "hidden" id = "hiddenNewBalanceID" />
							<table class="table table-bordered" id="example" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th style="text-align:center;">#</th>
													<th style="text-align:center;">Transaction Date</th>
													<th style="text-align:center;">Transaction</th>
													<th style="text-align:center;">Cashier</th>
													<th style="text-align:center;">Transaction Code</th>
													<th style="text-align:center;">Transaction Amount</th>
												</tr>
											</thead>
											<tbody>
												<?php
									$totalCashin = 0;
									$totalCashout = 0;
									$totalCash = 0;
									$qch = $mysqli->query("SELECT a.`transactionID`, a.`transactionCode`, a.`amount`, a.`transDate`, b.`username`, b.`cname` FROM `tblusertransactions`  a 
														LEFT JOIN `tblusers` b ON a.userID = b.id 
														WHERE a.cashHandlerID = '".$_SESSION['companyID']."' AND (a.transactionID = '1' OR a.transactionID = '8') ORDER BY a.id DESC");
									if($qch->num_rows > 0){
										$x= 1;
										while($rch = $qch->fetch_assoc()){
											$transactionID = $rch['transactionID'];
											$amount = $rch['amount'];
											$cashierName = $rch['username'] . "-".$rch['cname'];
					
											$transactionCode = $rch['transactionCode'];
											if($transactionID == 1){
												$totalCashin += $amount;
												$transaction = "CASH IN";
											}else if($transactionID == 8){
												$totalCashout += $amount;
												$transaction = "CASH OUT";
											}
											
											echo '
											<tr>
												<td style="text-align:center;">'.$x.'</td>
												<td style="text-align:center;">'.DATE('M d, Y h:i:s A', strtotime($rch['transDate'])).'</td>
												<td style="text-align:center;">'.$transaction.'</td>
												<td style="text-align:center;">'.$cashierName.'</td>
												<td style="text-align:center;">'.$transactionCode.'</td>
												<td style="text-align:center;">'.number_format($amount,2).'</td>
											</tr>';
											$x++;
										}
										if($totalCashout > $totalCashin){
											$totalCash = $totalCashout - $totalCashin;
										}else{
											$totalCash = "Self Computation";
										}
										echo '
											<tr>
												<td colspan = "5">TOTAL CASH IN</td>		
												<td style="text-align:center;">'.number_format($totalCashin,2).'</td>
											</tr>';
											echo '
											<tr>
												<td colspan = "5">TOTAL CASH OUT</td>		
												<td style="text-align:center;">'.number_format($totalCashout,2).'</td>
											</tr>';
											
											if($totalCashout > $totalCashin){
												$totalCash = $totalCashout - $totalCashin;
												echo '
												<tr>
													<td colspan = "5">TOTAL CASH: </td>		
													<td style="text-align:center;">'.number_format($totalCash,2).'</td>
												</tr>';
											}else{
												$totalCash = "Self Computation";
												echo '
												<tr>
													<td colspan = "5">TOTAL CASH: </td>		
													<td style="text-align:center;">'.$totalCash.'</td>
												</tr>';
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
    <script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="design/vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="design/vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="design/js/demo/datatables-demo.js"></script>
	<script src="design/js/sb-admin-2.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){		
			$('#example').DataTable( {
			});		
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>