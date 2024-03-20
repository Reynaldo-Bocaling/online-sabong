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
			  <!-- DataTales Example -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Registered User List of Request for Withdrawal of Points</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<input type = "hidden" id = "hiddenMobileNumber" />
							<input type = "hidden" id = "hiddenAccountID" />
							<input type = "hidden" id = "hiddenTransCode" />
							<input type = "hidden" id = "hiddenNewBalanceID" />
							<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th style="text-align:center;">#</th>
										<th style="text-align:center;">ACTION</th>
										<th style="text-align:center;">Transaction Code</th>
										<th style="text-align:center;">Amount/Points</th>
										<th style="text-align:center;">Mobile Number</th>
										<th style="text-align:center;">Date</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$qtrans = $mysqli->query("SELECT a.`id`, a.`accountID`, a.`transDate`, a.`transCode`, a.`transAmount`, a.`isProcess`, b.`mobileNumber` FROM `tblnewbalance` a LEFT JOIN `tblaccounts` b ON a.accountID = b.id WHERE a.`transID` = '2' AND a.`isProcess` = '0' ORDER BY a.id DESC");
									if($qtrans->num_rows > 0){
										$count = 1;
										while($rtrans = $qtrans->fetch_assoc()){
											echo '
											<tr  style="text-align:center;">
												<td>'.$count.'</td>
												<td><button class="btn btn-primary btnWithdrawConfirm" value = "'.$rtrans['transCode'].'" data-accountID ="'.$rtrans['accountID'].'" data-id= "'.$rtrans['id'].'">ENTER PIN</button</td>
												<td>'.$rtrans['transCode'].'</td>
												<td style="text-align:right;">'.number_format($rtrans['transAmount'],2).'</td>
												<td>'.$rtrans['mobileNumber'].'</td>
												<td>'.$rtrans['transDate'].'</td>
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
		function reloadPage(){ 
			location.reload();
		}
		function myFunction() {
		   pinVal = prompt("Please Enter Withdrawal PIN", "");
			if (pinVal != null) {
				transCodeVal = $("#hiddenTransCode").val();
				accountIDVal = $("#hiddenAccountID").val();
				idVal = $("#hiddenNewBalanceID").val();
				$.post("admin/confirmAccountBalanceWithdraw.php", {transCode:transCodeVal, accountID:accountIDVal, transPin:pinVal, transID:idVal}, function(res){
					if(res == 1){
						
						swal({
							title: "Withdrawn Points Successfully!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							reloadPage();
						});
					}else if(res == 2){
						swal("error on Transaction Code, due to already used barcode! Refresh the page and try again!.", "", "error");
					}else if(res == 3){
						swal("Unable to add points! Refresh the page and try again!.", "", "error");
					}else if(res == 4){
						swal({
							title: "Account Balance will be negative! The request for withdrawal has been cancelled automatically due to insufficient points!!",
							text: "",
							type: "error",
							confirmButtonClass: "btn-danger",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							reloadPage();
						});
					}else{
						swal("Invalid Transaction Code or PIN! Refresh the page and try again!.", "", "error");
					}
				});
			}
		}
		$(document).ready(function(){
			$(".btnWithdrawConfirm").click(function(){
				transCodeVal = $(this).val();
				accountIDVal = $(this).attr("data-accountID");
				idVal = $(this).attr("data-id");
				$("#hiddenTransCode").val(transCodeVal);
				$("#hiddenAccountID").val(accountIDVal);
				$("#hiddenNewBalanceID").val(idVal);
				myFunction()
			});
			
			
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>