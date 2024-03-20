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
	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<script src="design/dist/sweetalert.js"></script>
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
						<div class="col-md-10">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">
										<a href="dashboard.php" class=""><button class="btn btn-primary">DASHBOARD </button></a>
										<a href="staffBets.php" class=""><button class="btn btn-warning btn-lg" style="font-weight:bold; font-size:25px;">PLACE BET</button></a>
										<a href="staffCancelBets.php" class=""><button class="btn btn-danger">CANCEL BETS</button></a>
										<a href="staffCurrentBetList.php"><button class="btn btn-success" >CURRENT BETS</button></a>								
										<a href="staffDeposit.php" class=""><button class="btn btn-info">MOBILE DEPOSIT</button></a>
										<a href="staffWithdraw.php" class=""><button class="btn btn-info">MOBILE WITHDRAW</button></a>
										<button class="btn" style = "background-color:brown; color:#FFF;" id="cashinteller">TELLER CASH IN</button>
										<button class="btn" style = "background-color:brown; color:#FFF;" id="cashoutteller">TELLER CASH OUT</button>
									</h6>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<form method="POST" class="form-inline" target="_blank" action="print/printMoneyonhand.php" id="frmgeneratereport">
								<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
								<input type="hidden" id="varID" name = "varID" value = "<?php echo $_SESSION['systemVar']; ?>">
								<input type="hidden" id="varID2" name = "varID2" value = "<?php echo sha1($_SESSION['systemName']); ?>">
								<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
								<button type = "button" class="btn btn-primary btn-lg" id = "sbmtSummaryReport" style="font-size:25px; font-weight:bold; width:100%;"><i class="fa fa-print"></i> Print<br/>Money on Hand</button>
							</form>
						</div>
					</div>
					
					<?php
					$queryPayout = $mysqli->query("SELECT a.`id`, a.`payoutSettings`, a.`specialTeller` FROM `tblusers` a WHERE a.isActive = '1' AND a.roleID = '2' AND a.id = '".$_SESSION['companyID']."' LIMIT 1");
					if($queryPayout->num_rows > 0){
						while($rowPayout = $queryPayout->fetch_assoc()){
							$tellerPayoutSettings = $rowPayout['payoutSettings'];
							$specialTeller = $rowPayout['specialTeller'];
							
							if($tellerPayoutSettings == 1){
								echo '
								<div class="row">
									<div class="col-md-12">
										<div class="card shadow mb-4">
											<div class="card-header py-3">
												<h6 class="m-0 font-weight-bold text-primary">PAYOUT/REFUND:  SCAN BARCODE</h6>
											</div>
											<div class="card-body">
												
													<form method="POST" target="_blank" action="print/printBetPayout.php" id="frmPayoutNow">
														<div class="row">
															<div class="col-md-12">
																<input id="txtBarCode" name="txtBarCode" type="text"  style="width:100%; background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" maxlength= "14" placeholder="PAYOUT HERE" AUTOCOMPLETE = "OFF" />
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
								</div>';
							}else{
								if($display == 1){
									if($isBetting == 1 || $isBetting == 4){	
									echo'
										<div class="card shadow mb-4">
											<div class="card-header py-3">
												<input type = "hidden" id = "hiddenBetFightNumber" value = "" />
												<input type = "hidden" id = "hiddenBetFightID" value = "" />
												<input type = "hidden" id = "hiddenBetType"/>
												<input type = "hidden" id = "hiddenWinnerID"/>
												
												 <form method="POST" class="form-inline" target="_blank" action="print/printBet.php" id="frmGenerateBarcode">
													<input type="hidden" name="barcode_text">
													<input type="hidden" name="txtBetAmountBarcode" id = "txtBetAmountBarcode">
													<input type="hidden" name="hiddenBetTypeBarcode" id="hiddenBetTypeBarcode" >
													<input type="hidden" name="hiddenBetFightNumberBarcode" id="hiddenBetFightNumberBarcode">
													<input type="hidden" name="hiddenBetFightIDBarcode" id="hiddenBetFightIDBarcode">
													<input type="submit" name="generate_barcode" id = "sbmtGenerateBarcode" style="display:none;"value="GENERATE">

												</form>
												<h6 class="m-0 font-weight-bold text-primary"> PLACE BET: </h6>  
											</div>
											<div class="card-body">
												<div class="row">
													<div class="col-md-12">
														<input id="txtBetAmount" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" value = ""placeholder="ENTER AMOUNT HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS >
															
													</div>
												</div><br/>
												<div class="row">
													<div class="col-md-12" >
														<form class="form cf">
															<section class="plan cf">
																<input type="radio" name="betAmount" class="betAmount" id="bet1" value="100"><label class="four col" for="bet1">100</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet2" value="200"><label class="four col" for="bet2">200</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet3" value="300"><label class="four col" for="bet3">300</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet4" value="400"><label class="four col" for="bet4">400</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet5" value="500"><label class="four col" for="bet5">500</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet6" value="600"><label class="four col" for="bet6">600</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet7" value="700"><label class="four col" for="bet7">700</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet8" value="800"><label class="four col" for="bet8">800</label>
																<input type="radio" name="betAmount" class="betAmount" id="bet9" value="900"><label class="four col" for="bet9">900</label>															
																<input type="radio" name="betAmount" class="betAmount" id="bet10" value="1,000"> <label class="four col" for="bet10">1,000</label>
															</section>
														</form>
													</div>
													<div class="col-md-12" style="text-align:center;">';
													
														if($staffFor == 1 AND $specialTeller == 0){
															if($isBetting == 1 || $isBetting == 4 ){		
																echo '
																<button class = "btn btn-lg" id = "btnBetMeron" style="width:100%; background-color:#FF0000; color:#FFF; font-weight:bold; font-size:25px;" value = "MERON"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;BET MERON</button>';
															}
														}else if($staffFor == 2  AND $specialTeller == 0){
															if($isBetting == 1 || $isBetting == 4){
																echo '
																<button class = "btn btn-lg" id = "btnBetWala"  style="width:100%; background-color:#4e73df; color:#FFF; font-weight:bold; font-size:25px;" value = "WALA"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;BET WALA</button>';
															}
														}else if($specialTeller == 1){
															echo '
																<div class="row">
																	<div class="col-md-1"></div>
																	<div class="col-md-4" style="text-align:center;">
																		<button class = "btn btn-lg" id = "btnBetMeron" style="width:100%; background-color:#FF0000; text-align:center; color:#FFF; font-weight:bold; font-size:25px;" value = "MERON"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;BET MERON</button>
																	</div>
																	<div class="col-md-2"></div>';
															echo '
																	<div class="col-md-4" style="text-align:center;">
																		<button class = "btn btn-lg" id = "btnBetWala"  style="width:100%; background-color:#4e73df; color:#FFF; font-weight:bold; font-size:25px;" value = "WALA"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;BET WALA</button>
																	</div>
																	<div class="col-md-1"></div>
																</div>';
														}
													echo '	
													</div>
												</div>
											</div>
										</div>';
										
									echo '
									<div class="row">
										<div class="col-md-12">
											<div class="card shadow mb-4">
												<div class="card-header py-3">
													<h6 class="m-0 font-weight-bold text-primary">PAYOUT/REFUND:  SCAN BARCODE</h6>
												</div>
												<div class="card-body">
													
														<form method="POST" target="_blank" action="print/printBetPayout.php" id="frmPayoutNow">
															<div class="row">
																<div class="col-md-12">
																	<input id="txtBarCode" name="txtBarCode" type="text"  style="width:100%; background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" maxlength= "14" placeholder="PAYOUT HERE" AUTOCOMPLETE = "OFF" />
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
									</div>';
									}else{
										echo '
									<div class="row">
										<div class="col-md-12">
											<div class="card shadow mb-4">
												<div class="card-header py-3">
													<h6 class="m-0 font-weight-bold text-primary">PAYOUT/REFUND:  SCAN BARCODE</h6>
												</div>
												<div class="card-body">
													
														<form method="POST" target="_blank" action="print/printBetPayout.php" id="frmPayoutNow">
															<div class="row">
																<div class="col-md-12">
																	<input id="txtBarCode" name="txtBarCode" type="text"  style="width:100%; background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:40px;height:60px; letter-spacing:2px;" maxlength= "14" placeholder="PAYOUT HERE" AUTOCOMPLETE = "OFF" />
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
									</div>';
									}
									
								}
							}
						}
					}
						
					?>
					
					
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
			});
			$("#bet6").click(function(){
				betAmount = $("#bet6").val();
				$("#txtBetAmount").val(betAmount);
			});
			$("#bet7").click(function(){
				betAmount = $("#bet7").val();
				$("#txtBetAmount").val(betAmount);
			});
			$("#bet8").click(function(){
				betAmount = $("#bet8").val();
				$("#txtBetAmount").val(betAmount);
			});
			$("#bet9").click(function(){
				betAmount = $("#bet9").val();
				$("#txtBetAmount").val(betAmount);
			});
			$("#bet10").click(function(){
				betAmount = $("#bet10").val();
				$("#txtBetAmount").val(betAmount);
			});

			$("#btnBetMeron").click(function(){
				betType = $("#btnBetMeron").val();
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				$("#hiddenBetType").val(betType);
				betFightNumber = $("#hiddenBetFightNumber").val();
				betFightID = $("#hiddenBetFightID").val();
				varID =  $("#varID").val();
				varID2 =  $("#varID2").val();
				if(varID == varID2){
					if(betAmount == 0){
						$("#txtBetAmount").focus();
						swal("Please Input Bet Amount.","","error");
					}else if(betAmount1 < 100){
						$("#txtBetAmount").focus();
						swal("Please Input Bet Amount! Minimum bet amount is 100!","","error");
					}else if(betAmount1 > 50000){
						$("#txtBetAmount").focus();
						swal("Please Input Bet Amount! Maximum bet amount is 50,000!","","error");
					}else if(betAmount == ""){
						$("#txtBetAmount").focus();
						swal("Bet Amount is Empty! Please Input Bet Amount.","","error");
					}else{
						/*$("#txtBetTypeText").html(betType);
						$("#modal_isBet").modal("show");
						$("#betConfirmAmount").html(betAmount);
						
						*/
						$("#txtBetAmountBarcode").val(betAmount1);
						$("#hiddenBetTypeBarcode").val(betType); 

						$("#sbmtGenerateBarcode").click();	
					}
				}else{
					swal("Betting is not allowed at this moment! Please Contact System Developer","","error");
				}
			});
			$("#btnBetWala").click(function(){
				betType = $("#btnBetWala").val();
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				$("#hiddenBetType").val(betType);
				varID =  $("#varID").val();
				varID2 =  $("#varID2").val();
				if(varID == varID2){
					if(betAmount == 0){
						$("#txtBetAmount").focus();
						swal("Please Input Bet Amount.","","error");
					}else if(betAmount1 < 100){
						$("#txtBetAmount").focus();
						swal("Please Input Bet Amount! Minimum bet amount is 100!","","error");
					}else if(betAmount1 > 50000){
						$("#txtBetAmount").focus();
						swal("Please Input Bet Amount! Maximum bet amount is 50,000!","","error");
					}else if(betAmount == ""){
						$("#txtBetAmount").focus();
						swal("Bet Amount is Empty! Please Input Bet Amount.","","error");
					}else{
						$("#txtBetAmountBarcode").val(betAmount1);
						$("#hiddenBetTypeBarcode").val(betType); 
						$("#sbmtGenerateBarcode").click();
					}
				}else{
					swal("Betting is not allowed at this moment! Please Contact System Developer","","error");
				}

			});		
			$("#frmGenerateBarcode").submit(function(){	
				$.post("print/printBet.php", function (data) {
					//location.reload();
					$("#txtBetAmount").val("").focus();
				});
			});
						
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
			$('#frmPayoutNow').submit(function(event) {
				if ($('#txtBarCode').val().length < 14) {
					event.preventDefault();
					
				}else{
					
				}
			});
			$("#sbmtSummaryReport").click(function(){
				$("#generate_summaryreport").click();
			});
			
		});		
	</script>

		<?php
		include("modalboxes.php");
		include("staffModals.php");
	?>
  </body>
</html>