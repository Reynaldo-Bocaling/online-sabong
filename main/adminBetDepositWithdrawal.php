<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1){
	
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
	<script src="design/dist/sweetalert.js"></script>

	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	 <!-- Custom styles for this page -->
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <!-- Page Wrapper -->
	<div id="wrapper">

    <!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

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
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="administrator.php">
									<i class="fas fa-home mr-2 text-gray-400"></i>
									Dashboard								
								</a>
								<a class="dropdown-item" href="adminBetHistory.php">
									<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
									Bets History								
								</a>
								<a class="dropdown-item" href="adminBetDepositWithdrawal.php">
									<i class="fas fa-money-bill-alt mr-2 text-gray-400"></i>
									Deposit and Withdrawal History
								</a>
								<a class="dropdown-item" href="adminListAccounts.php">
									<i class="fas fa-users mr-2 text-gray-400"></i>
									Accounts
								</a>
								<a class="dropdown-item" href="adminReports.php">
									<i class="fa fa-pie-chart-o mr-2 text-gray-400"></i>
									Reports
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
				  <!-- DataTales Example -->
				  <div class="card shadow mb-4">
					<div class="card-header py-3">
					  <h6 class="m-0 font-weight-bold text-primary">Deposit and Withdrawal History</h6>
					</div>
					<div class="card-body">
					  <div class="table-responsive">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						  <thead>
							<tr>
								<th style="text-align:center;">#</th>
								<th style="text-align:center;">Mobile Number</th>
								<th style="text-align:center;">Type of Transaction</th>
								<th style="text-align:left;">Transaction Details</th>
							</tr>
						  </thead>
						  <tbody>
						   <?php
						   
						   $query = $mysqli->query("SELECT c.`transaction`, b.`mobileNumber`, a.`transactionDetails` FROM `tbltransactionlogs` a 
							LEFT JOIN `tblaccounts` b ON a.accountID = b.id 
							LEFT JOIN `tbltransaction` c ON a.transactionID = c.id 
							ORDER BY a.id DESC");
						   if($query->num_rows > 0){
							   $count = 1;
							   while($row = $query->fetch_assoc()){
								  echo '
								 <tr>
									<td style="text-align:center;">'.$count.'</td>
									<td style="text-align:center;">'.$row['mobileNumber'].'</td>
									<td style="text-align:center;">'.$row['transaction'].'</td>
									<td style="text-align:left;">'.$row['transactionDetails'].'</td>
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
  
    <!-- Bootstrap core JavaScript-->
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
			$(".btnAddBalance").click(function(){
				mobileNumberVal = $(this).val();
				accountIDVal = $(this).attr("data-accountID");
				fnameVal = $(this).attr("data-fname");
				lnameVal = $(this).attr("data-lname");
				balanceVal = $(this).attr("data-balance");
				$("#hiddenMobileNumber").val(mobileNumberVal);
				$("#hiddenAccountID").val(accountIDVal);
				
				$("#modal_adminAddBalance").modal("show");
				
				$("#spanAddFname").text(fnameVal);
				$("#spanAddLname").text(lnameVal);
				$("#spanAddMobileNumber").text(mobileNumberVal);
				$("#spanAddBalance").text(balanceVal);
			});
			$("#modal_adminAddBalance").on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtAddBalance').focus();
				}, 100);
			});	
			
			
			$(".btnWithdrawBalance").click(function(){
				mobileNumberVal = $(this).val();
				accountIDVal = $(this).attr("data-accountID");
				fnameVal = $(this).attr("data-fname");
				lnameVal = $(this).attr("data-lname");
				balanceVal = $(this).attr("data-balance");
				$("#hiddenMobileNumber").val(mobileNumberVal);
				$("#hiddenAccountID").val(accountIDVal);
				
				$("#modal_adminMinusBalance").modal("show");
				
				$("#spanMinusFname").text(fnameVal);
				$("#spanMinusLname").text(lnameVal);
				$("#spanMinusMobileNumber").text(mobileNumberVal);
				$("#spanMinusBalance").text(balanceVal);
			});
			$("#modal_adminMinusBalance").on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtMinusBalance').focus();
				}, 100);
			});	
			
			
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>