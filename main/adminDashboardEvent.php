<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
	
	$query = $mysqli->query("SELECT a.`id`, a.`username`, a.`cname`, a.`roleID`,  b.`role`, c.betType  FROM `tblusers` a 
	LEFT JOIN `tblroles` b ON a.roleID = b.id
	LEFT JOIN `tblbettypes` c ON a.betTypeID = c.id
	WHERE a.isActive = '1' ORDER BY a.roleID ASC, a.username ASC");
	
	$qcs = $mysqli->query("SELECT * FROM `tblsystem`");
	$count = $qcs->num_rows;
	if($count > 0){
		while($rcs = $qcs->fetch_assoc()){
			$systemName = $rcs['systemName'];
			$systemLocation = $rcs['systemLocation'];
		}
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
						<h6 class="m-0 font-weight-bold text-primary">System Display Configuration</h6>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="table-responsive">
									<?php
									echo '
										<button class="btn btn-md btn-primary" id = "btnAddBanner"><i class="fas fa-plus"></i> Add Event Type</button><br/><br/>';
									?>
									<table class="table table-bordered" id="example" width="100%" cellspacing="0">
										<thead>
											<tr>
												<th style="text-align:center;">#</th>
												<th style="text-align:left;">Event Type</th>
												<th style="text-align:left;">Default</th>
												<th style="text-align:left;">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$qbanner = $mysqli->query("SELECT * FROM `tblbanner` WHERE isActive = '1' ORDER BY isDefault DESC, id ASC ");
												if($qbanner->num_rows){
													$x = 1;
													while($rbanner = $qbanner->fetch_assoc()){
														echo '
														<tr>
															<td style="text-align:center;">'.$x.'</td>
															<td>'.$rbanner['eventName'].'</td>
															<td style="font-weight:bold;">';
															if($rbanner['isDefault'] == 1){
																echo '&nbsp;DEFAULT&nbsp;';															
															}else{
																echo '';
															}
															
															echo'
															</td>
															<td class="color-red-dark text-center">';
															if($rbanner['isDefault'] == 1){
															}else{
																echo '
																<button class="btn btn-primary btnDefaultBanner" value = "'.$rbanner['id'].'" style="cursor:pointer; width:120px;"><i class="ti-pencil"></i>Set Default</button>&nbsp;<button class="btn btn-danger btnRemoveBanner" value = "'.$rbanner['id'].'" style="cursor:pointer; width:120px;"><i class="ti-pencil"></i>Remove</button>';
															}
															echo'
															</td>
														
														
														</tr>';
														$x++;
													}
												}
												
												?>
										</tbody>
									</table>
								</div>
						
								
							</div>


							<div class="col-lg-6">
								<div class="table-responsive">
									<?php
									echo '
										<button class="btn btn-md btn-success" id = "btnAddPromoter"><i class="fas fa-plus"></i> Add Promoter</button><br/><br/>';
									?>
									<table class="table table-bordered" id="example1" width="100%" cellspacing="0">
										<thead>
											<tr>
												<th style="text-align:center;">#</th>
												<th style="text-align:left;">Promoter</th>
												<th style="text-align:left;">Default</th>
												<th style="text-align:left;">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$qpromoter = $mysqli->query("SELECT * FROM `tblpromoters` WHERE isActive = '1' ORDER BY isDefault DESC, id ASC");
												if($qpromoter->num_rows){
													$x = 1;
													while($rpromoter = $qpromoter->fetch_assoc()){
														echo '
														<tr>
															<td style="text-align:center;">'.$x.'</td>
															<td>'.$rpromoter['promoterName'].'</td>
															<td style="font-weight:bold;">';
															if($rpromoter['isDefault'] == 1){
																echo '&nbsp;DEFAULT&nbsp;';															
															}else{
																echo '';
															}
															
															echo'
															</td>
															<td class="color-red-dark text-center">';
															if($rpromoter['isDefault'] == 1){
															}else{
																echo '
																<button class="btn btn-primary btnDefaultPromoter" value = "'.$rpromoter['id'].'" style="cursor:pointer; width:120px;"><i class="ti-pencil"></i>Set Default</button>&nbsp;<button class="btn btn-danger btnRemovePromoter" value = "'.$rpromoter['id'].'" style="cursor:pointer; width:120px;"><i class="ti-pencil"></i>Remove</button>';
															}
															echo'
															</td>
														
														
														</tr>';
														$x++;
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
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});
		function caps(element){
			element.value = element.value.toUpperCase();
		}

		$(document).ready(function(){
			$('#example').DataTable( {
			});		
			$('#example1').DataTable( {
			});	
			$('table#example tbody').on('click', 'tr td .btnDefaultBanner', function(){
				bannerIDVal = $(this).val();
				showModal();
				$("#loader").show();
				$.post("banners/setDefaultBanner.php", {bannerID:bannerIDVal}, function(res){
					hideModal();
					$("#loader").hide();
					if(res == 1){
						swal({
							title: "Event Name Added Successfully!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});
					}else{
						swal("Error! Unable to set as the default Banner. Please refresh the page and try again.","","error");
					}
				});
			});
			$('table#example tbody').on('click', 'tr td .btnRemoveBanner', function(){
				bannerIDVal = $(this).val();
				showModal();
				$("#loader").show();
				$.post("banners/removeBanner.php", {bannerID:bannerIDVal}, function(res){
					hideModal();
					$("#loader").hide();
					if(res == 1){
						swal({
							title: "Event Name Removed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});
					}else{
						swal("Error! Unable to remove Event Name. Please refresh the page and try again.","","error");
					}
				});
			});
			$('table#example1 tbody').on('click', 'tr td .btnDefaultPromoter', function(){
				promoterIDVal = $(this).val();
				showModal();
				$("#loader").show();
				$.post("promoters/setDefaultPromoter.php", {promoterID:promoterIDVal}, function(res){
					hideModal();
					$("#loader").hide();
					if(res == 1){
						swal({
							title: "Promoter Set Successfully!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});
					}else{
						swal("Error! Unable to set as the default Promoter. Please refresh the page and try again.","","error");
					}
				});
			});
			$('table#example1 tbody').on('click', 'tr td .btnRemovePromoter', function(){
				promoterIDVal = $(this).val();
				showModal();
				$("#loader").show();
				$.post("promoters/removePromoter.php", {promoterID:promoterIDVal}, function(res){
					hideModal();
					$("#loader").hide();
					if(res == 1){
						swal({
							title: "Promoter Removed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});
					}else{
						swal("Error! Unable to remove the Promoter. Please refresh the page and try again.","","error");
					}
				});
			});
			$("#btnAddBanner").click(function(){
				$("#modal_addBanner").modal("show");
			});
			
			$("#btnAddPromoter").click(function(){
				$("#modal_addPromoter").modal("show");
			});
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>