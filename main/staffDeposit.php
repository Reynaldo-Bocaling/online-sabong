<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 2){ // 2 = STAFF
	$staffFor = $_SESSION['staffFor'];
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
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script src="design/dist/sweetalert.js"></script>
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
	<div id="wrapper">
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item dropdown no-arrow mx-1" style="text-align:center; font-weight:bold; font-size:15px;">
							<?php echo $_SESSION['username']; ?>
						</li>
						 <div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
							<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
								MENU
							</button>
						</a>
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
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">
										<a href="dashboard.php" class=""><button class="btn btn-primary">DASHBOARD</button></a>
										<a href="staffBets.php" class=""><button class="btn btn-warning">PLACE BET</button></a>
										<a href="staffCancelBets.php" class=""><button class="btn btn-danger">CANCEL BETS</button></a>
										<a href="staffCurrentBetList.php"><button class="btn btn-success">CURRENT BETS</button></a>							
										<a href="staffDeposit.php" class=""><button class="btn btn-info btn-lg" style="font-weight:bold; font-size:25px;">MOBILE DEPOSIT</button></a>
										<a href="staffWithdraw.php" class=""><button class="btn btn-info">MOBILE WITHDRAW</button></a>
										<button class="btn" style = "background-color:brown; color:#FFF;" id="cashinteller">TELLER CASH IN</button>
										<button class="btn" style = "background-color:brown; color:#FFF;" id="cashoutteller">TELLER CASH OUT</button>
									</h6>
								</div>
							</div>
						</div>
					</div>				
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">DEPOSIT POINTS:  SCAN TICKET</h6>
						</div>
						<div class="card-body">
							<form method="POST" target="_blank" action="print/printMobileDeposit.php" id="frmMobileDeposit">
								<div class="row">
									<div class="col-md-12">
										<input id="txtDepositBarcode" name="txtDepositBarcode" type="text" class="form-control" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" value = "" placeholder="ENTER TICKET HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS />
									</div>
								</div>
								<div class="row" style="margin-top:5px;">
									<div class="col-md-12">
										<input type = "submit"  value = "CONFIRM DEPOSIT" class="btn btn-success" style=" font-size:30px; font-weight:bold; width:100%; height:100%;" />
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
	<script src="design/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="design/js/sb-admin-2.min.js"></script>
	<script type="text/javascript">
		function reloadPage(){ 
			location.reload();
		}
		$(document).ready(function(){
			$("#frmMobileDeposit").submit(function(){
				$.post("print/printMobileDeposit.php", function (res) {
					location.reload();
				});
			});			
			$('#txtDepositBarcode').keyup(function(){
				if(this.value.length >= 14){
					$('#frmMobileDeposit').submit();
				}
			});
		});
	</script>

		<?php
		include("modalboxes.php");
		include("staffModals.php");
	?>
</body>
</html>