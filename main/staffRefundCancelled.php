<?php
	session_start();
	require('includes/connection.php');
	if($_SESSION['roleID'] == 2){ // 2 = STAFF
		$staffFor = $_SESSION['staffFor'];
		//getting the Fight Number
		$qfight = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`fightNumber` as fightNum, a.`fightDate`, a.`isBetting`, a.`isWinner`, b.`isBetting`  as bettingStatus, c.`percentToLess` FROM `tblfights` a 
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
		LEFT JOIN `tblpercentless` c ON a.percentlessID = c.id 
		WHERE a.id = (select max(id) from tblfights);");
		if($qfight->num_rows > 0){
			//isBetting = 1 means OPEN, isBetting = 2 means CLOSED
			while($rfight = $qfight->fetch_assoc()){
				$currentFightID = $rfight['id'];
				$currentFightCode = $rfight['fightCode'];
				$currentFightNumber = $rfight['fightNum'];
				$curdate = $rfight['fightDate'];
				$isBetting = $rfight['isBetting'];
				$percentToLess = $rfight['percentToLess'];
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
				$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isClaim = '0' AND isCancelled = '0' GROUP BY betType");
			}else if($isBetting == 3 || $isBetting == 5 || $isBetting == 6){
				$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isClaim = '0' AND isCancelled = '0'  GROUP BY betType");
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
	<style>
			.four { width: 9.9%; max-width: 9.9%;}

			.col {
			  display: block;
			  float:left;
			  margin: 0 0 .5% .1%;
			}

			.form .plan input, .form .payment-plan input, .form .payment-type input{
				display: none;
			}

			.form label{
				position: relative;
				color: #FFF;
				background-color: #000;
				font-size: 20px;
				font-weight:bold;
				text-align: center;
				
				height: 50x;
				line-height: 40px;
				display: block;
				cursor: pointer;
				border: 3px solid #FFF;
				-webkit-box-sizing: border-box;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
			}
		</style>
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
					<div class="row">
						<div class="col-md-12">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">
										<a href="dashboard.php" class=""><button class="btn btn-primary">DASHBOARD</button></a>
										<a href="staffBets.php" class=""><button class="btn btn-warning">PLACE BET</button></a>
										<a href="staffRefundCancelled.php" class=""><button class="btn btn-danger btn-lg" style="font-weight:bold; font-size:25px;">REFUND CANCELLED BET</button></a>
										<a href="staffCurrentBetList.php"><button class="btn btn-success" >CURRENT BETS</button></a>								
										<a href="staffDeposit.php" class=""><button class="btn btn-info">MOBILE DEPOSIT</button></a>
										<a href="staffWithdraw.php" class=""><button class="btn btn-info">MOBILE WITHDRAW</button></a>
										<button class="btn" style = "background-color:brown; color:#FFF;" id="cashinteller">TELLER CASH IN</button>
										<button class="btn" style = "background-color:brown; color:#FFF;" id="cashoutteller">TELLER CASH OUT</button>
										<!--<a href="staffTransactionHistory.php" class=""><button class="btn btn-primary">TRANSACTION HISTORY</button></a>
										<a href="staffBetsManagement.php" class=""><button class="btn btn-primary">BETS REPORT</button></a>-->
									</h6>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">REFUND CANCELLED BET FOR CURRENT FIGHTS AND WINNER IS NOT YET DECLARED ONLY:  SCAN BARCODE</h6>
								</div>
								<div class="card-body">
									<form method="POST" target="_blank" action="print/printBetRefundCancelled.php" id="frmPayoutCancelled">
										<div class="row">
											<div class="col-md-12">
												<input id="txtBarCode" name="txtBarCode" type="text"  style="width:100%; background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" maxlength= "14" placeholder="PAYOUT REFUND CANCELLED BET HERE" AUTOCOMPLETE = "OFF" />
												<input id="txtBarCode1" name="txtBarCode1" type="hidden"  maxlength= "14" AUTOCOMPLETE = "OFF" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<input type = "submit"  id = "sbmtPayout" value = "CLAIM PAYOUT" class="btn btn-success btn-lg" style="display:none; font-size:30px; font-weight:bold; width:100%; height:100%;" />
											</div>															
										</div>
										<div class="row" style="margin-top:5px;">
											<div class="col-md-12" style="text-align:center;">
												<input type = "button"  id = "btnPayout" value = "CLAIM PAYOUT" class="btn btn-success" style=" font-size:30px; font-weight:bold; width:100%; height:100%;" />
											</div>
										</div>
									</form>
								</div>
							</div>
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
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});		
		$(document).ready(function(){		
			$('#txtBarCode').keyup(function(){
				txt = $('#txtBarCode').val();
				txtlen = $('#txtBarCode').val().length;
				if(txtlen == 14){
					$('#txtBarCode1').val(txt);
					$("#sbmtPayout").click();	 
					$("#txtBarCode").val("").focus();				
				}
			});		
			$("#btnPayout").click(function(){
				txt = $('#txtBarCode').val();
				txtlen = $('#txtBarCode').val().length;
				if(txtlen == 14){
					$('#txtBarCode1').val(txt);
					$("#sbmtPayout").click();		 
					$("#txtBarCode").val("").focus();			
				}
			});
			$('#frmPayoutNowCancelled').submit(function(event) {
				if ($('#txtBarCode').val().length < 14) {
					event.preventDefault();
					
				}else{
					
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