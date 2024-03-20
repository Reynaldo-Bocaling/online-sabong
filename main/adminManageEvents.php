<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
	$checkEvent = $mysqli->query("SELECT * FROM tblevents WHERE eventStatus = '0' ORDER BY id DESC ");
	$countEvent = $checkEvent->num_rows;

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
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	 <!-- Custom styles for this page -->
	

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
						<?php
							echo '
							<a href="adminManageReports.php" class=""><button class="btn btn-sm btn-primary"><i class="fas fa-book"></i> Bettings Reports</button></a>
							<a href="adminManageEvents.php" class=""><button class="btn btn-lg btn-success"><i class="fas fa-book"></i> Event and Teller Reports</button></a>';
						?>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-7">
								<form method="POST" class="form-inline" target="_blank" action="print/printEODTickets.php">
									<input type ="hidden" name = "hiddenEODTicketEventID" id ="hiddenEODTicketEventID" />
									<input type = "submit" name = "generate_tellersummaryreport" id = "generate_tellersummaryreport" style = "display:none;" value = "GENERATE">
								</form>
								
								<form method="POST" class="form-inline" target="_blank" action="print/printEODMobile.php">
									<input type ="hidden" name = "hiddenEODMobileEventID" id ="hiddenEODMobileEventID" />
									<input type = "submit" name = "generate_mobilesummaryreport" id = "generate_mobilesummaryreport" style = "display:none;" value = "GENERATE">
								</form>
							
								<form method="POST" class="form-inline" target="_blank" action="print/printEODAll.php">
									<input type ="hidden" name = "hiddenEODAllEventID" id ="hiddenEODAllEventID" />
									<input type = "submit" name = "generate_allsummaryreport" id = "generate_allsummaryreport" style = "display:none;" value = "GENERATE">
								</form>
							
								<form method="POST" class="form-inline" target="_blank" action="print/printAllTeller.php" id="frmallTeller">
									<input type="hidden" name="hiddenEODAllTellerID" id="hiddenEODAllTellerID">
									<input type = "submit" name = "generate_allTeller" id = "generate_allTeller" style = "display:none;" value = "GENERATE">
								</form>
										
								<div class="table-responsive table-bordered">
									<input type = "hidden" id = "hiddenEventID" />
									<button class="btn btn-md btn-primary" id = "btnAddEvent" style="margin:10px;"><i class="fas fa-plus"></i> Add Event</button><br/><br/>
									
									<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" >
										<thead>
											<tr>
												<th style="text-align:center;">#</th>
												<th style="text-align:left;">Event Date</th>
												<th style="text-align:left;">Event Status</th>
												<th style="text-align:left;">User System Access Status</th>
												<th style="text-align:left;">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$queryEvent = $mysqli->query("SELECT * FROM `tblevents` ORDER BY id DESC ");
											if($queryEvent->num_rows > 0){
												$x = 1;
												while($rowEvent = $queryEvent->fetch_assoc()){
												echo '
													<tr>
														<td style="text-align:center;">'.$x.'</td>
														<td style="text-align:left;">'.DATE("M d, Y", strtotime($rowEvent['eventDate'])).'</td>';
														
														if($rowEvent['eventStatus'] == 0){
															echo '<td style="text-align:left;">OPEN</td>';
														}else{
															echo '<td style="text-align:left;">CLOSE</td>';
														}
														
														if($rowEvent['userAccessStatus'] == 0){
															echo '<td style="text-align:left;">OPEN</td>';
														}else{
															echo '<td style="text-align:left;">CLOSE</td>';
														}
														
														echo '
														<td style="text-align:left;">';
														
															if($rowEvent['eventStatus'] == 0){
																echo '<button class="btn btn-primary btnCloseEvent" value = "'.$rowEvent['id'].'" style="margin:5px;">CLOSE EVENT</button>&nbsp;';
															}else{
																
															}
															if($rowEvent['userAccessStatus'] == 0){
																echo '<button class="btn btn-danger btnCloseSystem" value = "'.$rowEvent['id'].'" style="margin:5px;">CLOSE SYSTEM ACCESS</button>&nbsp;';
															}else{
																echo '<button class="btn btn-success btnOpenSystem" value = "'.$rowEvent['id'].'" style="margin:5px;"> OPEN SYSTEM ACCESS</button><br/>';
															}
															echo '<button class="btn btn-dark btnEODTickets" value = "'.$rowEvent['id'].'" style="margin:5px;">GENERATE TICKETING SUMMARY REPORT</button><br/>';
															echo '<button class="btn btn-info btnEODMobile" value = "'.$rowEvent['id'].'" style="margin:5px;">GENERATE MOBILE SUMMARY REPORT</button><br/>';
															echo '<button class="btn btn-warning btnEODAll" value = "'.$rowEvent['id'].'" style="margin:5px; color:#000;">GENERATE END OF THE DAY REPORT</button><br/>';
															echo '<button class="btn btn-warning btnAllTeller" value = "'.$rowEvent['id'].'" style="margin:5px; color:#000;">GENERATE TELLER REPORTS</button><br/>';
															
															
															
														echo '</td>
													</tr>';
													$x++;
												}
											}else{
												
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							
							<div class="col-lg-5">
								<table class="table table-bordered" id="example" width="100%" cellspacing="0" >
										<thead>
											<tr>
												<th colspan = "6" style="text-align:center;">LIST OF CASH IN</th>
									
											</tr>
											<tr>
												<th style="text-align:center;">#</th>
												<th style="text-align:left;">Event Date</th>
												<th style="text-align:left;">Teller</th>
												<th style="text-align:left;">Amount</th>
												<th style="text-align:left;">Status</th>
												<th style="text-align:left;">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$queryEvent = $mysqli->query("SELECT a.`id`, a.`amount`, a.`statusID`, b.`username`, ev.`eventDate`, ev.`eventStatus` FROM `tblusertransactions` a 
											LEFT JOIN `tblusers` b ON a.userID = b.id
											LEFT JOIN tblevents ev ON a.eventID = ev.id
											WHERE transactionID = '1' ORDER BY a.id DESC ");
											if($queryEvent->num_rows > 0){
												$x = 1;
												while($rowEvent = $queryEvent->fetch_assoc()){
												echo '
													<tr>
														<td style="text-align:center;">'.$x.'</td>
														<td style="text-align:center;">'.DATE("M d, Y", strtotime($rowEvent['eventDate'])).'</td>
														<td style="text-align:center;">'.$rowEvent['username'].'</td>
														<td style="text-align:right;">'.number_format($rowEvent['amount'],2).'</td>';
														
														if($rowEvent['statusID'] == 1){
															echo '<td style="text-align:center;">CANCELLED</td>';
															if($rowEvent['eventStatus'] == 0){ // 0 means OPEN EVENT
																echo '
																<td style="text-align:left;">
																	<button class="btn btn-primary btnCashinApproved" value = "'.$rowEvent['id'].'" style="margin:5px;">APPROVED CASH IN</button>
																</td>';
															}else{
																echo '<td></td>';
															}		
															
														}else{
															echo '<td style="text-align:center;">APPROVED</td>';
															if($rowEvent['eventStatus'] == 0){ // 0 means OPEN EVENT
																echo '
																<td style="text-align:left;">
																	<button class="btn btn-danger btnCashinCancel" value = "'.$rowEvent['id'].'" style="margin:5px;">CANCEL CASH IN</button>
																</td>';
															}else{
																echo '<td></td>';
															}															
														}
														
											
														
												echo '
													</tr>';
													$x++;
												}
											}else{
												
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
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});
		$(document).ready(function(){
			$('#example').DataTable( {
			});		
			$("#btnAddEvent").click(function(){
				$("#modal_addEvent").modal("show");
			});
			
			$(".btnCloseEvent").click(function(){
				idVal = $(this).val();
				$("#hiddenEventID").val(idVal);
				$("#modal_confirmCloseEvent").modal("show");
			});
			
			$(".btnCloseSystem").click(function(){
				idVal = $(this).val();
				$("#hiddenEventID").val(idVal);
				$("#modal_confirmCloseSystem").modal("show");
			});
			$(".btnOpenSystem").click(function(){
				idVal = $(this).val();
				$("#hiddenEventID").val(idVal);
				$("#modal_confirmOpenSystem").modal("show");
			});
			
			$(".btnEODTickets").click(function(){
				eventID = $(this).val();
				$("#hiddenEODTicketEventID").val(eventID);
				$("#generate_tellersummaryreport").click();
			});
			
			$(".btnEODMobile").click(function(){
				eventID = $(this).val();
				$("#hiddenEODMobileEventID").val(eventID);
				$("#generate_mobilesummaryreport").click();
			});
			
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
			$('table#example tbody').on('click', 'tr td .btnCashinCancel', function(){	
				id = $(this).val();			
				swal({
					title: "CANCEL THE TELLER CASH IN?!",
					text: "Are you sure?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				 },
				function(isConfirm){

				   if (isConfirm){
						$.post("admin/saveCashinCancel.php", {cashinID:id}, function(res){
							if(res == 1){
								swal({
									html: true,
									title: "Successfully cancelled the teller cash in transaction for this event!",
									text: "",
									type: "success",
									confirmButtonClass: "btn-success",
									confirmButtonText: "OK",
									closeOnConfirm: true
								},
								function(){
									location.reload();
								});	
							}else if(res == 2){
								swal("Error! The cash in transaction does not exist. Please refresh the page and try again.", "", "error");		
							}else{
								swal("error! Refresh the page and try again or system developer assistance is required!.", "", "error");	
							}
						});
				   }
				});
				
			});
			
			
			$(".btnCashinApproved").click(function(){
				id = $(this).val();			
				swal({
					title: "APPROVE THE TELLER CASH IN?!",
					text: "Are you sure?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				 },
				function(isConfirm){

				   if (isConfirm){
						$.post("admin/saveCashinApproved.php", {cashinID:id}, function(res){
							if(res == 1){
								swal({
									html: true,
									title: "Successfully approved the teller cash in transaction for this event!",
									text: "",
									type: "success",
									confirmButtonClass: "btn-success",
									confirmButtonText: "OK",
									closeOnConfirm: true
								},
								function(){
									location.reload();
								});	
							}else if(res == 2){
								swal("Error! The cash in transaction does not exist. Please refresh the page and try again.", "", "error");		
							}else{
								swal("error! Refresh the page and try again or system developer assistance is required!.", "", "error");	
							}
						});
				   }
				});
				
			});
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>