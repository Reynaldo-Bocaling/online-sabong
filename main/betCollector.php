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
	<script src="https://cdn.tailwindcss.com"></script>
	  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body id="page-top">

<style>

::-webkit-scrollbar {
  width: 0;
}
#userDropdown, #btnRefreshPage{
	outline:none
}
</style>

<div id="content" class="flex-1 flex flex-col overflow-hidden gap-2 bg-white ">
	<header class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7 ">
		
		<div class="text-base font-mdium text-gray-700 flex items-center gap-2 ">
			<p class=" md:flex md:gap-2">Welcome, <span class="capitalize text-black font-semibold"><?php echo $_SESSION['username']; ?></span></p>
			<img src="./assets/images/waving.png" class="w-[50px]  md:flex" />
		</div>
		
		<div class="flex items-center gap-3">
			<div class="hidden md:flex itms-center gap-3 mr-7">
				<button class="cashin text-sm text-blue-500 font-bold px-6 py-[5px] rounded-full bg-blue-100 tracking-wide" id = "cashin">&plus; Cash In</button>
				<button class="cashout text-sm text-red-500 font-bold px-6 py-[5px] rounded-full bg-red-100 tracking-wide mr-10" id = "cashout">&minus; Cash Out</button>
				
				<!-- money -->
				<form method="POST" target="_blank" action="print/printMoneyonhandCollector.php" id="frmgeneratereport">
					<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
					<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
					<button type = "button" class="sbmtSummaryReport text-sm text-green-500 font-bold px-6 py-[5px] rounded-full bg-green-100 tracking-wide mr-10" id = "sbmtSummaryReport">&dollar; MONEY ON HAND</button>
				</form>
			</div>

			<button class="md:outline-none noneOutlineBtn" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class='bx bx-menu-alt-right text-3xl' ></i>
			</button>
			<!-- Dropdown - User Information -->
			<div class="dropdown-menu dropdown-menu-right shadow mt-2" aria-labelledby="userDropdown">
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
		</div>

	</header>




	<main class="flex-1 overflow-x-auto overflow-y-auto p-3 h-[90vh] w-screen">
		<div class="flex items-center justify-between mr-4">
			<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
			<p>Bet/ </p>
			<span class="font-semibold text-blue-500">Place-Bet's </span>
			</div>
			<small><?php echo $currentDate ?></small>
		</div>
		
		<div class="w-full flex items-center justify-between mb-3 px-4">
			<p class="text-xl text-black font-bold mb-2">Bet Collector</p>
			<button class = "noneOutlineBtn text-lg text-blue-500 font-bold flex items-center gap- mr-4" id = "btnRefreshPage" >
				<i class='bx bx-rotate-right text-xl' ></i>	
				Refresh
			</button>
		</div>





		<?php
			if($display == 1){
				if($isBetting == 1 || $isBetting == 4){
				echo'<div class=" h-full w-full flex items-center justify-center">
						<input type = "hidden" id = "hiddenBetFightNumber" value = "<?php echo $currentFightNumber; ?>" />
						<input type = "hidden" id = "hiddenBetFightID" value = "<?php echo $currentFightID; ?>" />
						<input type = "hidden" id = "hiddenBetType"/>
						<input type = "hidden" id = "hiddenWinnerID"/>
					
						<div class="max-w-[500px] w-full py-10 px-4 rounded-xl shadow-md shadow-slate-200 border bg-white rounded-2xl flex flex-col gap-2">
							<p class="text-center text-xl font-bold text-black mb-4">Bet Amount</p>
					
							<form>
								<input id="txtBetAmount" type="text" class="form-control auto text-sm font-medium py-6" value = ""placeholder="Enter Amount Here.." AUTOCOMPLETE = "OFF" AUTOFOCUS>	
							</form>		
							<div class="w-full flex flex-col md:flex-row items-center justify-center gap-3 mt-4 mb-1">
								<button class = "text-base text-white font-bold w-full md:w-1/2 h-[45px] rounded-full bg-blue-500 flex items-center justify-center gap-1" id = "btnBetWala"  value = "WALA">
									<i class="bx bx-plus text-xl" ></i>
									BET WALA
								</button>
								<button class = "text-base text-white font-bold w-full md:w-1/2 h-[45px] rounded-full bg-red-500 flex items-center justify-center gap-1" id = "btnBetMeron" value = "MERON">
									<i class="bx bx-plus text-xl" ></i>
									BET MERON</button>
							</div>
						</div>			
					</div>
					';
				}else{
				
				}
			}
		?>
			<div class="flex flex-col mt-4 mb-4 gap-3 md:hidden">
				<button class="cashin md:hidden text-sm text-blue-500 font-bold w-full py-3 rounded-full bg-blue-100 tracking-wide" id="cashin">&plus; Cash In</button>
				<button class="cashout md:hidden text-sm text-red-500 font-bold w-full py-3 rounded-full bg-red-100 tracking-wide mr-10" id="cashout">&minus; Cash Out</button>
				<!-- money -->
				<form method="POST" target="_blank" action="print/printMoneyonhandCollector.php" id="frmgeneratereport">
					<input type="hidden" name="hiddenTellerUserID" value = "<?php echo $_SESSION['companyID']; ?>">
					<input type = "submit" name = "generate_summaryreport" id = "generate_summaryreport" style = "display:none;" value = "GENERATE">
					<button type = "button" class="sbmtSummaryReport md:hidden text-sm text-green-500 font-bold w-full py-3 rounded-full bg-green-100 tracking-wide mr-10" id = "sbmtSummaryReport">&dollar; MONEY ON HAND</button>
				</form>
			</div>

		
		
			
	</main>
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
			
			$(".sbmtSummaryReport").click(function(){
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