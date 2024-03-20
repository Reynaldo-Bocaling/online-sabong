<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 12){
	
	$qaccounts = $mysqli->query("SELECT * FROM `tblaccounts` ORDER BY lastname ASC, firstname ASC, balance DESC ");
	
	$qyear = $mysqli->query("SELECT YEAR(CURDATE()) as dbyear;");
	while($ryear = $qyear->fetch_assoc()){
		$currentYear = $ryear['dbyear'];
	}
	
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
				<div class="container">
					<form method="POST" class="form-inline" target="_blank" action="print/printSupervisorAll.php">
						<input type ="hidden" name = "hiddenEODAllEventID" id ="hiddenEODAllEventID" />
						<input type = "submit" name = "generate_allsummaryreport" id = "generate_allsummaryreport" style = "display:none;" value = "GENERATE">
					</form>
							
					<form method="POST" class="form-inline" target="_blank" action="print/printSupervisorTellers.php" id="frmallTeller">
						<input type="hidden" name="hiddenEODAllTellerID" id="hiddenEODAllTellerID">
						<input type = "submit" name = "generate_allTeller" id = "generate_allTeller" style = "display:none;" value = "GENERATE">
					</form>
										
					<div class="row">
						<div class="col-xl-12 col-md-12 mb-12">
							<div class="card shadow mb-12">
								<div class="card-header py-12">
									<div class="row">
										<div class="col-md-12">
											<div class="panel panel-success" style="border:2px solid #000; font-size:11px; padding:15px;">
												<div class="panel-heading">
													<h3 class="panel-title" style="font-weight:bolder;"><i class="ti-filter"></i>SUMMARY REPORTS</h3>
												</div>
												<div class="panel-body">
													<div class="row">
														<div class="col-md-12">	
															<?php
															$queryEvent = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC LIMIT 1");
															if($queryEvent->num_rows > 0){
																$x = 1;
																while($rowEvent = $queryEvent->fetch_assoc()){
																	echo '<button class="btn btn-lg btn-info btnEODAll" value = "'.$rowEvent['id'].'" style="margin:5px; color:#000;">VIEW END OF DAY REPORT</button><br/>';
																	echo '<button class="btn btn-lg btn-warning btnAllTeller" value = "'.$rowEvent['id'].'" style="margin:5px; color:#000;">VIEW TELLERS SUMMARY</button><br/>';
																}
															}else{
																
															}
															?>
															
														</div>
													</div>
												</div>
											</div>
										</div>
									</div> <!-- end of panel for clients information-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.container-fluid -->
			</div>
			<!-- End of Main Content -->
		</div>
		<!-- End of Content Wrapper -->
	</div>
	<script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="design/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="design/js/sb-admin-2.min.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script>
		$(document).ready(function(){
			$(".btnEODAll").click(function(){
				eventID = $(this).val();
				$("#hiddenEODAllEventID").val(eventID);
				$("#generate_allsummaryreport").click();
			});
			$(".btnAllTeller").click(function(){
				eventID = $(this).val();
				$("#hiddenEODAllTellerID").val(eventID);
				$("#generate_allTeller").click();
			});
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>