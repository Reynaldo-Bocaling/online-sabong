<?php
session_start();
require 'includes/connection.php';
if ($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) {

} else {
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
	<style>
html, body {
   min-height: 100vh;
  padding: 0;
}

	</style>
</head>

<body id="page-top" >
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
include 'includes/header.php';
?>
						</li>
					</ul>
				</nav>
				 <!-- Begin Page Content -->
				<div class="container-fluid">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">CANCEL TICKET BET:  SCAN BARCODE </h6>
						</div>
						<div class="card-body">
							<form method="POST" target="_blank" action="print/printCancelTicketBet.php" id="frmCancelTicketBet">
								<div class="row">
									<div class="col-md-12">
										<input id="txtCancelTicketBetAmount" name="txtCancelTicketBetAmount" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" value = "" placeholder="ENTER TICKET BET AMOUNT" AUTOCOMPLETE = "OFF" AUTOFOCUS />
										<input id="txtCancelTicketBetAmount1" name="txtCancelTicketBetAmount1" type="hidden"  style="display:none;" />
									</div>
								</div><br/>
								<div class="row">
									<div class="col-md-12">
										<input id="txtCancelTicketBetBarcode" name="txtCancelTicketBetBarcode" type="text" class="form-control" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" value = "" placeholder="ENTER BARCODE HERE" AUTOCOMPLETE = "OFF" />
									</div>
								</div>
								<div class="row" style="margin-top:5px;">
									<div class="col-md-12">
										<input type = "submit" id = "sbmtCancelTicketBet" class="btn btn-success" style="display:none;" />
										<input type = "button"  id = "btnCancelTicketBet" value = "CANCEL TICKET BET" class="btn btn-success" style=" font-size:30px; font-weight:bold; width:100%; height:100%;" />
									</div>
								</div>
							</form>
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
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});
		function reloadPage(){
			location.reload();
		}
		$(document).ready(function(){

			$("#btnCancelTicketBet").click(function(){
				$("#frmCancelTicketBet").submit();
				location.reload();
			});

			$('#txtCancelTicketBetBarcode').keyup(function(){
				if(this.value.length == 14){
					amount = $("#txtCancelTicketBetAmount").val();
					amount1 = parseFloat(amount.replace(/,/g,""));
					$("#txtCancelTicketBetAmount1").val(amount1);
					if(amount1 == 0 || amount1 == ""){
						$("#txtCancelTicketBetAmount").focus();
						swal("Enter the Ticket Bet Amount to cancel","","error");
					}else{
						$('#btnCancelTicketBet').click();
					}
				}
			});

		});
	</script>
	<?php
include "modalboxes.php";
include "adminModals.php";
?>
</body>

</html>