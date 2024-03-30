<?php
session_start();
require 'includes/connection.php';
if ($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) {

    $query = $mysqli->query("SELECT a.`id`, a.`username`, a.`cname`, a.`roleID`,  b.`role`, c.betType  FROM `tblusers` a
	LEFT JOIN `tblroles` b ON a.roleID = b.id
	LEFT JOIN `tblbettypes` c ON a.betTypeID = c.id
	WHERE a.isActive = '1' ORDER BY a.roleID ASC, a.username ASC");

    $qcs = $mysqli->query("SELECT * FROM `tblsystem`");
    $count = $qcs->num_rows;
    if ($count > 0) {
        while ($rcs = $qcs->fetch_assoc()) {
            $systemName = $rcs['systemName'];
            $systemLocation = $rcs['systemLocation'];
        }
    }
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

	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<script src="design/dist/sweetalert.js"></script>
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	 <!-- Custom styles for this page -->
	 <script src="https://cdn.tailwindcss.com"></script>
	  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
	  <link rel="stylesheet"
  href="./assets/styles/table.css">

</head>
<style>

::-webkit-scrollbar {
  width: 0;
}

</style>

<body id="page-top">
	

<div id="wrapper" class="fixed top-0 left-0 w-screen h-screen overflow-y-auto">
    <div id="content-wrapper" class="flex h-screen overflow-hidden">

     <!-- sidebar for mobile -->
	 	<div id="sidebar" class="hide-scrollbar overflow-hidden fixed z-50  w-screen h-screen bg-[rgba(0,0,0,0.3)] hidden transition-all">
            <div class="relative h-screen bg-white border-r shadow-lg shadow-slate-100 px-[20px] py-10 transition-all w-[270px] overflow-y-auto">
                <button id="closeBtn" class="text-red-500 text-3xl absolute top-0 right-0 m-4">&times;</button>
                <span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
                <div class="flex flex-col  mt-9 px-2 overflow-y-auto">
               		<?php
						$links = [
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ?
							'<a class="text-sm text-gray-600  p-3 font-normal" href="administrator.php"><i class="fas fa-home mr-2 text-gray-400"></i>Home</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="dashboard.php"><i class="bx bxs-plus-circle text-gray-400 mr-2"></i>Betting Odds Display</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminDashboardEvent.php"><i class="bx bxs-dashboard mr-2" ></i>Dashboard Configuration</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Bettings Management</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 12) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 13) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="cashHandler.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Cash INs and OUTs</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						];
						foreach ($links as $link) {
							if (!empty($link)) {
								echo $link;
							}
						}

						?>
                </div>
            </div>
        </div>

        <!-- sidebar for desktop size -->
        <div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-10 transition-all hidden md:flex md:flex-col max-w-[270px] w-full h-screen">
            <span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
            <div class="flex flex-col overflow-y-auto mt-9 px-2">
				<?php
					$links = [
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ?
						'<a class="text-sm text-gray-600  p-3 font-normal" href="administrator.php"><i class="fas fa-home mr-2 text-gray-400"></i>Home</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="dashboard.php"><i class="bx bxs-plus-circle text-gray-400 mr-2"></i>Betting Odds Display</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="adminDashboardEvent.php"><i class="bx bxs-dashboard mr-2" ></i>Dashboard Configuration</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Bettings Management</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 13) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="cashHandler.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Cash INs and OUTs</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
					];
					foreach ($links as $link) {
						if (!empty($link)) {
							echo $link;
						}
					}
					?>
        	</div>
    	</div>


		<!-- main -->
		<div id="content" class="flex-1 flex flex-col overflow-hiddenw gap-2 bg-[#F6F8FA]">
			<nav class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7 ">
				<button id="openBtn" class="w-[30px] flex flex-col gap-[5px] border-none focus:outline-none md:hidden py-[10px]">
					<div class="w-full h-[3px] rounded-full bg-black"></div>
					<div class="w-full h-[3px] rounded-full bg-black"></div>
				</button>
				<div class="text-base font-mdium text-gray-700 flex items-center gap-2 ">
					<p class="hidden md:flex">Welcome, User</p>
					<img src="./assets/images/waving.png" class="w-[50px] hidden md:flex" />
				</div>
				<span><i class='bx bx-calendar-star text-blue-500 text-lg'></i> <?php echo date('F j, Y') ?></span>

			</nav>


			<main class="flex-1 overflow-x-hidden overflow-y-auto p-3">
				<div class="flex items-center justify-between">
					<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
					<p>DashboardConfiguration/ </p>
					<span class="font-semibold text-blue-500">Dashboard-Event</span>
					</div>
					<small><?php echo $currentDate ?></small>
				</div>
				<div class="flex items-center justify-between px-1 -mt-2">
					<p class="text-lg text-black font-semibold">System Display Configuration</p>
					<div class="flex items-center gap-2">
						<button class="text-sm text-white font-semibold px-6 py-2 bg-blue-500 rounded-full" id = "btnAddBanner"><i class="fas fa-plus"></i> Add Event Type</button><br/><br/>
						<button class="text-sm text-white font-semibold px-6 py-2 bg-green-500 rounded-full" id = "btnAddPromoter"><i class="fas fa-plus"></i> Add Promoter</button><br/><br/>
					</div>
				</div>



				<div class="w-full flex flex-col items-g gap-9 bg-white p-4 rounded-lg border mt-2 overflow-x-auto">
					<div class="w-full">
						<p class="text-2xl text-blue-500 font-semibold text-center mb-7">Event Type</p>
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
									if ($qbanner->num_rows) {
										$x = 1;
										while ($rbanner = $qbanner->fetch_assoc()) {
											echo '
											<tr>
												<td class="text-sm font-semibold">' . $x . '</td>
												<td class="text-sm">' . $rbanner['eventName'] . '</td>
												<td class="text-sm font-bold">';
											if ($rbanner['isDefault'] == 1) {
												echo '&nbsp;DEFAULT&nbsp;';
											} else {
												echo '';
											}

											echo '
											</td>
											<td class="color-red-dark text-center">';
											if ($rbanner['isDefault'] == 1) {
											} else {
												echo '
												<div class="w-full flex items-center gap-1">
													<button class="text-xs font=medium text-white rounded-full py-2 px-3 bg-blue-500 btnDefaultBanner" value = "' . $rbanner['id'] . ' "><i class="ti-pencil"></i>Set Default</button>&nbsp;
													<button class="text-xs font=medium text-white rounded-full py-2 px-3 bg-red-500 btnRemoveBanner" value = "' . $rbanner['id'] . '" ><i class="ti-pencil"></i>Remove</button>
												</div>
												';
											}
											echo '
											</td>
											</tr>';
											$x++;
										}
									}

									?>
							</tbody>
					</table>
					</div>
					<div class="w-full mt-7 border-t pt-4">
						<p class="text-2xl text-blue-500 font-semibold text-center mb-7">Promoter</p>
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
									if ($qpromoter->num_rows) {
										$x = 1;
										while ($rpromoter = $qpromoter->fetch_assoc()) {
											echo '<tr>
												<td class="text-sm font-semibold">' . $x . '</td>
												<td class="text-sm">' . $rpromoter['promoterName'] . '</td>
												<td class="text-sm font-bold">';
											if ($rpromoter['isDefault'] == 1) {
												echo '&nbsp;DEFAULT&nbsp;';
											} else {
												echo '';
											}

											echo '
											</td>
											<td class="color-red-dark text-center">';
											if ($rpromoter['isDefault'] == 1) {
											} else {
												echo '
													<div  class="w-full flex items-center gap-1">
														<button class="text-xs font=medium text-white rounded-full py-2 px-3 bg-blue-500 btnDefaultPromoter" value = "' . $rpromoter['id'] . '"><i class="ti-pencil"></i>Set Default</button>&nbsp;
														<button class="text-xs font=medium text-white rounded-full py-2 px-3 bg-red-500 btnRemovePromoter" value = "' . $rpromoter['id'] . '"><i class="ti-pencil"></i>Remove</button>
													</div>
												';
											}
											echo '
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
			</main>
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

		$(document).ready(function(){
    $('#openBtn').click(function(){
      $('#sidebar').toggleClass('hidden');
    });

    $('#closeBtn').click(function(){
      $('#sidebar').addClass('hidden');
    });


    $('#sidebar').click(function(e){
      if (e.target === this) {
        $(this).addClass('hidden');
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