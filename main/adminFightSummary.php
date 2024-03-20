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
		<div id="content-wrapper" class="d-flex flex-column">
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
							<?php
								include('includes/header.php');
							?>
						</li>
					</ul>
				</nav>
				 <!-- Begin Page Content -->
				<div class="container-fluid">
				  <!-- DataTales Example -->
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Fight Summary</h6>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<input type = "hidden" id = "hiddenMobileNumber" />
								<input type = "hidden" id = "hiddenAccountID" />
								<table class="table table-bordered" id="example" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th style="text-align:center;">#</th>
											<th style="text-align:center;">Date</th>
											<th style="text-align:center;">Fight #</th>
											<th style="text-align:center;">Status</th>
											<th style="text-align:center;">Winner</th>
											<th style="text-align:center;">Bets for Meron</th>
											<th style="text-align:center;">Bets for Wala</th>
											<th style="text-align:center;">Total bets</th>
											<th style="text-align:center;">Payout Meron</th>
											<th style="text-align:center;">PayoutWala</th>
											<th style="text-align:center;">Action</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$query = $mysqli->query("SELECT a.`id`, a.`fightCode`, ev.`eventDate`, a.`fightNumber`, a.`isBetting`, b.`isBetting` as isBettingText, c.`winner`, a.`payoutMeron`, a.`payoutWala`  FROM `tblfights` a 
										LEFT JOIN `tblevents` ev ON a.eventID = ev.id
										LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
										LEFT JOIN `tblwinner` c ON a.isWinner = c.id
										WHERE a.id > 0
										ORDER BY a.id DESC ");
										if($query->num_rows > 0){
										$count = 1;
										$grandTotal = 0;
										$grandTotalMeron = 0;
										$grandTotalWala = 0;

											while($row = $query->fetch_assoc()){
												$fightID = $row['id'];
												$fightCode = $row['fightCode'];
												$fightDate = $row['eventDate'];
												$fightNumber = $row['fightNumber'];
												$isBettingText = $row['isBettingText'];
												$isBetting = $row['isBetting'];
												$winner = $row['winner'];
												$payoutMeron = $row['payoutMeron'];
												$payoutWala = $row['payoutWala'];
												$meronTotalBetAmount = 0;
												$walaTotalBetAmount = 0;
												$totalBetAmount = 0;
										
												echo '
												<tr>
													<td style="text-align:center;">'.$count.'</td>
													<td style="text-align:center;">'.$fightDate.'</td>
													<td style="text-align:center;">'.$fightNumber.'</td>
													<td style="text-align:center;">'.$isBettingText.'</td>
													<td style="text-align:center;">'.$winner.'</td>
													<td style="text-align:right;">'.number_format($meronTotalBetAmount,2).'</td>
													<td style="text-align:right;">'.number_format($walaTotalBetAmount,2).'</td>
													<td style="text-align:right;">'.number_format($totalBetAmount,2).'</td>
													<td style="text-align:right;">'.number_format($payoutMeron,2).'</td>
													<td style="text-align:right;">'.number_format($payoutWala,2).'</td>
												</tr>
												';
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
			$('#example').DataTable( {
			});
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
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>