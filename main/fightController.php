<?php
session_start();
require('includes/connection.php');
if($_SESSION['roleID'] == 6){
	$isBetting  = 0;
	//getting the Fight Number
	$qfight = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`fightNumber` as fightNum, a.`isBetting`, a.`isWinner`, a.`payoutMeron`, a.`payoutWala`, a.`closeMeron`, a.`closeWala`, b.`isBetting`  as bettingStatus,  d.`winner`, ev.`eventDate` FROM `tblfights` a 
LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
LEFT JOIN `tblwinner` d ON a.isWinner = d.id
LEFT JOIN `tblevents` ev ON a.eventID = ev.id
WHERE a.id = (select max(id) from tblfights);");
$queryPercent = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
$rowPercent = $queryPercent->fetch_assoc();	
$percentToLess = $rowPercent['percentToLess'];
	if($qfight->num_rows > 0){
		//isBetting = 1 means OPEN, isBetting = 2 means CLOSED
		while($rfight = $qfight->fetch_assoc()){
			$currentFightID = $rfight['id'];
			$currentFightNumber = $rfight['fightNum'];
			$currentFightCode = $rfight['fightCode'];
			$curdate = $rfight['eventDate'];
			$isBetting = $rfight['isBetting'];
			$closeMeron = $rfight['closeMeron'];
			$closeWala = $rfight['closeWala'];
			
			$winner = $rfight['winner'];
			
			$winnerFightID = $rfight['id'];
			$winnerID = $rfight['isWinner'];
			if($isBetting == 1){
				$isBettingText = $rfight['bettingStatus'];
			}else if($isBetting == 3 || $isBetting == 6){
				$isBettingText = $rfight['bettingStatus'];
				$isBettingWinner = $rfight['isWinner'];
				
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
			$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isCancelled = '0' GROUP BY betType");
		}else if($isBetting == 3 || $isBetting == 5 || $isBetting == 6){
			$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isCancelled = '0' GROUP BY a.betType");
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
			<div class="row">
				<?php
				if($display == 1){
				echo '	
				<div class="col-xl-12 col-md-6 mb-4">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
						
							<div class="card-body" >
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">DATE / BETTING STATUS / FIGHT NO.:</div>
										<div class="h6 mb-0 font-weight-bold; text-gray-800">
										'.DATE('M d, Y', strtotime($curdate)) . ' | ' . $isBettingText . ' | ' . $currentFightNumber .'
										
										<input type = "hidden" id = "hiddenPayoutMeron" value = "'.number_format((float)$payoutMeron, 2, '.', '').'" />
										<input type = "hidden" id = "hiddenPayoutWala" value = "'.number_format((float)$payoutWala, 2, '.', '').'" />
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive" id = "betsContainer">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr style="text-align:center;">
											<th style="font-weight:bold;">Bet Type</th>
											<th style="font-weight:bold;">Total Bets</th>
											<th style="font-weight:bold;">Payout</th>
											<th style="font-weight:bold;">RESULT</th>';
											
										echo '	
										
										</tr>
									</thead>
									<tbody>
										<tr style="text-align:center;">
											<td style="background-color:#f34141; color:#FFF; font-weight:bold; font-size:11px;">
												MERON
											</td>
											<td style="background-color:#f34141; color:#FFF; font-weight:bold; font-size:11px;">
												'.number_format($meronTotalBetAmount).'
											</td>
											<td style="background-color:#f34141; color:#FFF; font-weight:bold; font-size:11px;">
												'.number_format($payoutMeron).'
											</td>
											<td style="background-color:#f34141; color:#FFF; font-weight:bold; font-size:11px;">';
											
											if($isBetting == 3 || $isBetting == 6){
												if($isBettingWinner == 1){
													echo 'WIN';
												}else if($isBettingWinner == 2){
													echo 'LOST';
												}else{
													echo 'DRAW';
												}
											}else if($isBetting == 5){
												echo 'CANCELLED';
											}else{
												echo 'UNSETTLED';
											}
											echo '
											</td>
										</tr>
										<tr style="text-align:center;">
											<td style="background-color:#4e73df; color:#FFF; font-weight:bold; font-size:11px;">
												WALA
											</td>
											<td style="background-color:#4e73df; color:#FFF; font-weight:bold; font-size:11px;">
												'.number_format($walaTotalBetAmount).'
											</td>
											<td style="background-color:#4e73df; color:#FFF; font-weight:bold; font-size:11px;">
												'.number_format($payoutWala).'
											</td>
											<td style="background-color:#4e73df; color:#FFF; font-weight:bold; font-size:11px;">';
											
											if($isBetting == 3 || $isBetting == 6){
												if($isBettingWinner == 1){
													echo 'LOST';
												}else if($isBettingWinner == 2){
													echo 'WIN';
												}else{
													echo 'DRAW';
												}
											}else if($isBetting == 5){
												echo 'CANCELLED';
											}else{
												echo 'UNSETTLED';
											}
											echo '
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>';
				?>
				<div class="col-xl-12 col-md-6 mb-4">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">FIGHT CONTROL OPTIONS</h6>
						</div>
						<div class="card-body">
							<?php
							if($isBetting == 1){ // 1 for BETTING STATUS = OPEN
								echo '
								<button class="btn btn-info btn-lg" id = "btnBettingLast">
									<i class="fas fa-info-circle"> LAST CALL </i>
								</button>&nbsp;
								<button class="btn btn-danger btn-lg" id = "btnBettingCancel">
									<i class="fas fa-exclamation-triangle"> CANCEL FIGHT </i>
								</button>';
							}else if($isBetting == 4){// 4 for BETTING STATUS = LAST CALL
								if($meronTotalBetAmount > 100 && $walaTotalBetAmount > 100){
								echo '
									<button class="btn btn-success btn-lg" id = "btnBettingClose">
										<i class="fas fa-times-circle"> CLOSE BETTINGS </i>
									</button>
									<button class="btn btn-danger btn-lg" id = "btnBettingCancel">
										<i class="fas fa-exclamation-triangle"> CANCEL FIGHT </i>
									</button>';
								}else{
									echo '
									<button class="btn btn-danger btn-lg" id = "btnBettingCancel">
										<i class="fas fa-exclamation-triangle"> CANCEL FIGHT </i>
									</button>';
								}
							}else if($isBetting == 2 ){// 2 for BETTING STATUS = CLOSE
								echo '
								<button class="btn btn-success btn-lg" id = "btnBettingWinner">
									<i class="fas fa-check"> DECLARE FIGHT WINNER </i>
								</button>';
							}else if($isBetting == 3 ){// 2 for BETTING STATUS = FOR PAYOUT
								echo '
								<button class="btn btn-primary btn-lg" id = "btnBettingPayout">
									<i class="fas fa-check"> &nbsp;RELEASED PAYOUT </i>
								</button>';
							}else if($isBetting == 5 ){// 5 for BETTING STATUS = CANCELLED
								echo '
								<button class="btn btn-primary btn-lg" id = "btnBettingNew">
									<i class="fas fa-plus"> &nbsp;START NEW FIGHT </i>
								</button>
								';
							}else if($isBetting == 6){
								echo '
								<button class="btn btn-primary btn-lg" id = "btnBettingNew">
									<i class="fas fa-plus"> &nbsp;START NEW FIGHT </i>
								</button>
								';
							}
							
							echo'
							</div>
						</div>
					</div>';
						if($isBetting == 1 OR $isBetting == 4){
						echo '
					<div class="col-xl-12 col-md-6 mb-4">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">FIGHT BETTING OPTIONS</h6>
							</div>
							<div class="card-body">';
							if($isBetting == 1){ // 1 for BETTING STATUS = OPEN
								if($closeWala == 0){
									echo '
									<button class="btn btn-primary btn-lg" id = "btnCloseWala">
										<i class="fa fa-lock"> CLOSE WALA BETTINGS </i>
									</button>&nbsp;';
								}else{
									echo '
									<button class="btn btn-primary btn-lg" id = "btnOpenWala">
										<i class="fa fa-lock-open"> OPEN WALA BETTINGS </i>
									</button>&nbsp;';
								}
								
								if($closeMeron == 0){
									echo '
									<button class="btn btn-danger btn-lg" id = "btnCloseMeron">
										<i class="fa fa-lock"> CLOSE MERON BETTINGS </i>
									</button><br/>';
								}else{
									echo '
									<button class="btn btn-danger btn-lg" id = "btnOpenMeron">
										<i class="fa fa-lock-open"> OPEN MERON BETTINGS </i>
									</button><br/>';
								}	
							}else if($isBetting == 4){// 4 for BETTING STATUS = LAST CALL
								if($closeWala == 0){
									echo '
									<button class="btn btn-primary btn-lg" id = "btnCloseWala">
										<i class="fa fa-lock"> CLOSE WALA BETTINGS </i>
									</button>&nbsp;';
								}else{
									echo '
									<button class="btn btn-primary btn-lg" id = "btnOpenWala">
										<i class="fa fa-lock-open"> OPEN WALA BETTINGS </i>
									</button>&nbsp;';
								}
								
								if($closeMeron == 0){
									echo '
									<button class="btn btn-danger btn-lg" id = "btnCloseMeron">
										<i class="fa fa-lock"> CLOSE MERON BETTINGS </i>
									</button><br/>';
								}else{
									echo '
									<button class="btn btn-danger btn-lg" id = "btnOpenMeron">
										<i class="fa fa-lock-open"> OPEN MERON BETTINGS </i>
									</button><br/>';
								}
							}
							echo '
								</div>
							</div>
						</div>';
						}

				}else{
					echo '
					<div class="col-xl-12 col-md-6 mb-4">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">FIGHT CONTROL OPTIONS</h6>
							</div>
							<div class="card-body">
								<a href="#" class="btn btn-primary btn-lg" id = "btnBettingNew">
									<i class="fas fa-plus"> &nbsp;START NEW FIGHT </i>
								</a>
							</div>
						</div>
					</div>';		
				}
				?>		
			</div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->
  <!-- Bootstrap core JavaScript-->
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

		function caps(element){
			element.value = element.value.toUpperCase();
		}
		
		$(document).ready(function(){
			
			$("#btnBettingLast").click(function(){
				$("#modal_confirmLastCall").modal("show");
			});
			$("#btnBettingClose").click(function(){
				$("#modal_confirmClose").modal("show");
			});
			$("#btnBettingNew").click(function(){
				$("#modal_confirmNew").modal("show");
			});
			
			$("#btnBettingWinner").click(function(){
				$("#modal_declareWinner").modal("show");
			});
			$("#btnBettingCancel").click(function(){
				$("#modal_confirmCancelBets").modal("show");
				$('#txtConfirmPassword').val("");
			});
			$('#modal_confirmCancelBets').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtConfirmPassword').focus();
				}, 10);
			});	
			$("#btnBettingPayout").click(function(){
				$("#modal_confirmReleasePayout").modal("show");
			});
			

			setInterval(function(){   
				$("#betsContainer").load("admin/betsLoadLatestData.php");
			}, 10000);

			$("#btnCloseWala").click(function(){
				$("#modal_confirmCloseWala").modal("show");
			});
			$("#btnCloseMeron").click(function(){
				$("#modal_confirmCloseMeron").modal("show");
			});
			$("#btnOpenWala").click(function(){
				$("#modal_confirmOpenWala").modal("show");
			});
			$("#btnOpenMeron").click(function(){
				$("#modal_confirmOpenMeron").modal("show");
			});
		});
	</script>
	<?php
		include("modalboxes.php");
		include("adminModals.php");
	?>
</body>

</html>