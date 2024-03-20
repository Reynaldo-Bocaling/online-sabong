<?php
	session_start();
	require('includes/connection.php');
	if($_SESSION['roleID'] == 7){ // 2 = STAFF
		$staffFor = $_SESSION['staffFor'];
		//getting the Fight Number
		$qfight = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`fightNumber` as fightNum, a.`fightDate`, a.`isBetting`, a.`isWinner`, b.`isBetting`  as bettingStatus FROM `tblfights` a 
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
		WHERE a.id = (select max(id) from tblfights);");
		$queryPercent = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
		$rowPercent = $queryPercent->fetch_assoc();	
		$percentToLess = $rowPercent['percentToLess'];
		if($qfight->num_rows > 0){
			//isBetting = 1 means OPEN, isBetting = 2 means CLOSED
			while($rfight = $qfight->fetch_assoc()){
				$currentFightID = $rfight['id'];
				$currentFightCode = $rfight['fightCode'];
				$currentFightNumber = $rfight['fightNum'];
				$curdate = $rfight['fightDate'];
				$isBetting = $rfight['isBetting'];

				if($isBetting == 1){
					$isBettingText = $rfight['bettingStatus'];
				}else{
					$isBettingText = $rfight['bettingStatus'];
				}
			}
			//bet details
			$meronTotalBetAmount = 0;
			$walaTotalBetAmount = 0;
			$totalBetAmount = 0;
			$threePercent = 0;
			$totalAmountLessThreePercent = 0;
			$totalAmountIfMeronWins = 0;
			$totalAmountIfWalaWins = 0;
			$pesoEquivalentIfMeronWins = 0;
			$pesoEquivalentIfWalaWins = 0;
			$payoutMeron = 0;
			$payoutWala = 0;
			
			if($isBetting == 1 || $isBetting == 2 || $isBetting == 4){
				$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isClaim = '0' GROUP BY betType");
			}else if($isBetting == 3 || $isBetting == 5 || $isBetting == 6){
				$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isClaim = '0' GROUP BY betType");
			}
			
			if($qbets->num_rows > 0){
				while($rbets = $qbets->fetch_assoc()){
					$betType = $rbets['betType'];
					if($betType == 1){
						$totalBetAmount += $rbets['bets'];
						$meronTotalBetAmount = $rbets['bets'];
					}else{
						$totalBetAmount += $rbets['bets'];
						$walaTotalBetAmount = $rbets['bets'];
					}
				}
				if($meronTotalBetAmount > 0 && $walaTotalBetAmount > 0){
					$threePercent = ($totalBetAmount * $percentToLess);
					$totalAmountLessThreePercent = ($totalBetAmount - $threePercent);
					$totalAmountIfMeronWins = ($totalAmountLessThreePercent - $meronTotalBetAmount);
					$pesoEquivalentIfMeronWins = ($totalAmountIfMeronWins / $meronTotalBetAmount);
					$payoutMeron = (($pesoEquivalentIfMeronWins * 100 ) + 100);
										
					$totalAmountIfWalaWins = ($totalAmountLessThreePercent - $walaTotalBetAmount);
					$pesoEquivalentIfWalaWins = ($totalAmountIfWalaWins / $walaTotalBetAmount);
					$payoutWala = (($pesoEquivalentIfWalaWins *100 ) +100);
				}else{
				}
			}
			$display = 1;
		}else{
			$display = 0;
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
					<li class="nav-item dropdown no-arrow mx-1" style="text-align:center; font-weight:bold; font-size:15px;">
						<?php echo $_SESSION['username']; ?>
					</li>
					 <div class="topbar-divider d-none d-sm-block"></div>

					<!-- Nav Item - User Information -->
					<li class="nav-item dropdown no-arrow">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
						<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
							MENU
						</button>
					</a>
				  <!-- Dropdown - User Information -->
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
		
				 <!-- Begin Page Content -->
				<div class="container-fluid">
					<input type = "hidden" id = "hiddenBetFightNumber" value = "<?php echo $currentFightNumber; ?>" />
					<input type = "hidden" id = "hiddenBetFightID" value = "<?php echo $currentFightID; ?>" />
					<input type = "hidden" id = "hiddenBetType"/>
					<input type = "hidden" id = "hiddenWinnerID"/>
				  <?php
					if($display == 1){
						if($isBetting == 1 || $isBetting == 4){
						echo'
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">PLACE BETS: BET AMOUNT</h6>
								</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-3">
												<div class="form-group">
													<form>
														<input id="txtBetAmount" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:20px;height:60px; letter-spacing:2px;" value = ""placeholder="ENTER AMOUNT HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS>	
													</form>		
													<br/>
													<button class = "btn btn-lg" id = "btnBetWala"  style="width:100%; background-color:#4e73df; color:#FFF; font-weight:bold; font-size:25px;" value = "WALA"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;BET WALA</button>
													<br/><br/>
													<button class = "btn btn-lg" id = "btnBetMeron" style="width:100%; background-color:green; color:#FFF; font-weight:bold; font-size:25px;" value = "MERON"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;BET MERON</button>
																
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>';
						}else{
						
						}
					}
					?>
					<div class="row">
						<div class="col-md-12">
							<div class="card shadow mb-4">
								<div class="card-body">
									<div class="row">
										<div class="col-md-3">
											<button class = "btn btn-lg btn-danger" id = "btnRefreshPage" style="width:100%;  font-weight:bold; font-size:25px;">&nbsp;REFRESH PAGE</button><br/><br/>
											
											<button class = "btn btn-lg btn-warning" id = "cashin" style="width:100%;  font-weight:bold; font-size:25px;"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Cash IN</button><br/><br/>
											
											<button class = "btn btn-lg btn-danger" id = "cashout" style="width:100%;  font-weight:bold; font-size:25px;"><i class="fa fa-minus-circle" aria-hidden="true"></i>&nbsp;Cash OUT</button><br/><br/>
											
											
											<form method="POST" target="_blank" action="print/printMoneyonhandCollector.php" id="frmgeneratereport">
												<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
												<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
												<button type = "button" class="btn btn-primary btn-lg" id = "sbmtSummaryReport" style="font-size:15px; font-weight:bold; width:100%;"> <i class="fa fa-print mr-2 text-gray-400"></i><br/> MONEY ON HAND</button>
											</form><br/><br/>
											<button class = "btn btn-lg btn-warning" id = "changePassword" style="width:100%;  font-weight:bold; font-size:25px;"><i class="fa fa-lock mr-2 text-gray-400"></i>&nbsp;Change Password</button>
										</div>
									</div>
									<iframe  width="100%" height="100%" name="frame_report" id="frame_report" style="display:none;"></iframe>

									 <form method="POST" class="form-inline" target="frame_report" action="print/printBetCollector.php" id="frmGenerateBarcode">
											  <input type="hidden" name="barcode_text">
											  <input type="hidden" name="txtBetAmountBarcode" id = "txtBetAmountBarcode">
											  <input type="hidden" name="hiddenBetTypeBarcode" id="hiddenBetTypeBarcode" >
											  <input type="hidden" name="hiddenBetFightNumberBarcode" id="hiddenBetFightNumberBarcode">
											  <input type="hidden" name="hiddenBetFightIDBarcode" id="hiddenBetFightIDBarcode">
											  <input type="submit" name="generate_barcode" id = "sbmtGenerateBarcode" style="display:none;"value="GENERATE">
											 </form>
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
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript" src="assets/js/autoNumeric.js"></script>
	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});
		function reloadPage(){ 
			location.reload();
		}
		$(document).ready(function(){
			
			$("#btnReset").click(function(){
				$("#txtBetAmount").val("");
				$(".betAmount"). prop("checked", false);
			});
			$("#bet1").click(function(){
				betAmount = $("#bet1").val();
				$("#txtBetAmount").val(betAmount);
			});
			$("#bet2").click(function(){
				betAmount = $("#bet2").val();
				$("#txtBetAmount").val(betAmount);				
			});
			$("#bet3").click(function(){
				betAmount = $("#bet3").val();
				$("#txtBetAmount").val(betAmount);			
			});
			$("#bet4").click(function(){
				betAmount = $("#bet4").val();
				$("#txtBetAmount").val(betAmount);			
			});
			$("#bet5").click(function(){
				betAmount = $("#bet5").val();
				$("#txtBetAmount").val(betAmount);				
			});$("#bet6").click(function(){
				betAmount = $("#bet6").val();
				$("#txtBetAmount").val(betAmount);
			});
			$("#btnBetMeron").click(function(){
				betType = $("#btnBetMeron").val();
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				$("#hiddenBetType").val(betType);
				betFightNumber = $("#hiddenBetFightNumber").val();
				betFightID = $("#hiddenBetFightID").val();

				if(betAmount == 0){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else if(betAmount < 100){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Minimum bet amount is 100 Points!","","error");
				}else if(betAmount == ""){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else{
					/*$("#txtBetTypeText").html(betType);
					$("#modal_isBet").modal("show");
					$("#betConfirmAmount").html(betAmount);
					
					*/
					$("#txtBetAmountBarcode").val(betAmount1);
					$("#hiddenBetTypeBarcode").val(betType); 
					swal({
						title: "BET MERON?",
						text: "Are you sure?",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						cancelButtonText: "No, cancel it!",
						confirmButtonText: 'Yes, I am sure!',
						
						closeOnConfirm: true,
						closeOnCancel: true
					 },
					 function(isConfirm){

						if (isConfirm){
							$("#sbmtGenerateBarcode").click();	
							$("#txtBetAmount").val("").focus();
						} else {
							$("#txtBetAmount").val("").focus();
							swal("Cancelled", "Bet Cancelled", "error");
						}
					});
					
				}
				
			});
			$("#btnBetWala").click(function(){
				betType = $("#btnBetWala").val();
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				$("#hiddenBetType").val(betType);
				
				if(betAmount == 0){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else if(betAmount < 100){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Minimum bet amount is 100 Points!","","error");
				}else if(betAmount == ""){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else{
					$("#txtBetAmountBarcode").val(betAmount1);
					$("#hiddenBetTypeBarcode").val(betType); 
					swal({
						title: "BET WALA?",
						text: "Are you sure?",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Yes, I am sure!',
						cancelButtonText: "No, cancel it!",
						closeOnConfirm: true,
						closeOnCancel: true
					 },
					 function(isConfirm){

						if (isConfirm){
							$("#sbmtGenerateBarcode").click();
							$("#txtBetAmount").val("").focus();
						} else {
							$("#txtBetAmount").val("").focus();
							swal("Cancelled", "Bet Cancelled", "error");
						}
					});
					
				}
				
			});		
			$("#frmGenerateBarcode").submit(function(){	
				//$.post("staff/saveBets.php", function (data) {
				$.post("print/printBetCollector.php", function (data) {
					
				});
			});
			
			$("#sbmtSummaryReport").click(function(){
				$("#generate_summaryreport").click();
		
			});
			$("#btnRefreshPage").click(function(){
				location.reload(true);
				
			});
		});
	</script>
		<?php
		include("modalboxes.php");
		include("staffModals.php");
	?>
  </body>
</html>