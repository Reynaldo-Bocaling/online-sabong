<?php
session_start();
require 'includes/connection.php';
if ($_SESSION['roleID'] == 3) {
    $qaccounts = $mysqli->query("SELECT `id`, `mobileNumber`, `firstname`, `lastname`, `balance` FROM `tblaccounts` WHERE id = '" . $_SESSION['accountID'] . "' ");

    if ($qaccounts->num_rows > 0) {
        while ($raccounts = $qaccounts->fetch_assoc()) {
            $points = $raccounts['balance'];
            $accountID = $raccounts['id'];
            $firstname = $raccounts['firstname'];
            $lastname = $raccounts['lastname'];
            $mobileNumber = $raccounts['mobileNumber'];
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
	<link rel="stylesheet" href="design/dist/sweetalert.css">
	<link rel="stylesheet" href="./assets/styles/table.css">
	<script src="design/dist/sweetalert.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top" onload = "addBal()">

<div id="wrapper" class="fixed top-0 left-0 w-screen">
	<div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-10 transition-all hidden md:flex md:flex-col w-[270px] h-screen">
		<span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
		<div class="flex flex-col gap-5 mt-9 px-2">
			<a class="text-sm text-gray-600  p-3 font-normal rounded-lg" href="index.php">
				<i class="fas fa-home mr-2"></i>
				Dashboard
			</a>
			<a class="text-sm text-gray-600 bg-blue-50 p-3 font-normal rounded-lg" href="accountBetAddPoints.php">
				<i class="fas fa-plus mr-2 text-blue-500"></i>
				<span class="text-blue-500">Add Points</span>
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal" href="accountBetWithdrawPoints.php">
				<i class="fas fa-minus mr-2 text-gray-400"></i>
				Withdraw Points
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal" href="accountBetHistory.php">
				<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
				Bets History
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal" href="accountLogs.php">
				<i class="fas fa-money-bill-alt mr-2 text-gray-400"></i>
				Account Logs
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal" id = "changePassword">
				<i class="fa fa-lock mr-2 text-gray-400"></i>
				Change Password
			</a>
			<div class="dropdown-divider"></div>
			<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php">
				<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
				Logout
			</a>
			</div>
	</div>

	<div id="content" class="flex-1 flex flex-col overflow-hidden gap-2 bg-[#F6F8FA]">
		<nav class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7">
			<p class="text-base font-mdium text-gray-700 flex items-center gap-2">
				Welcome,
				<span class="text-black tracking-tighter font-semibold"><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?> </span>
				<img src="./assets/images/waving.png" class="w-[50px]" />
			</p>

			<small><?php echo $currentDate ?></small>
			<div class=" flex items-center gap-2">
				<p class="text-sm text-gray-700">Your Points:</pc>
				<p class="<?php echo ($points < 10) ? 'text-red-500' : 'text-green-500'; ?> text-sm font-semibold">&#8369;<?php echo number_format($points, 2); ?></p>
			<div>
		</nav>

		<main class="flex-1 overflow-x-hidden overflow-y-auto p-3">


			<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
				<p>AddPoints/ </p>
				<span class="font-semibold text-blue-500">Points-and-Deposit-History</span>
			</div>
			<div class="flex items-center justify-between pl-2 pr-5 mb-3">
				<span class="text-lg text-black font-semibold">Add Points and Deposit History</span>
				<?php echo '<button data-accountID = "' . $accountID . '" data-fname = "' . $firstname . '" data-lname = "' . $lastname . '" data-balance = "' . number_format($points) . '" value = "' . $mobileNumber . '" class="btnAddBalance text-sm text-white bg-blue-500 px-4 py-2 rounded-full shadow-md shadow-blue-100">Click to deposit points</button> ' ?>

			</div>



			<div class="p-3 bg-white rounded-lg border">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="text-align:center;">#</th>
						<th style="text-align:center;">Transaction Code</th>
						<th style="text-align:center;">Amount/Points</th>
						<th style="text-align:center;">Status</th>
						<th style="text-align:center;">Date</th>
						<th style="text-align:center;">VIEW CODE</th>
					</tr>
				</thead>
				<tbody>
				<?php
$qtrans = $mysqli->query("SELECT * FROM `tblnewbalance` WHERE `accountID` = '" . $accountID . "' AND transID = '1' ORDER BY id DESC");
if ($qtrans->num_rows > 0) {
    $count = 1;
    while ($rtrans = $qtrans->fetch_assoc()) {
        if ($rtrans['isProcess'] == 0) {
            $isProcess = "Proceed to Cashier for Payment";
        } else {
            $isProcess = "Deposited Successfully";
        }
        echo '
																												<tr  style="text-align:center;">
																													<td>' . $count . '</td>
																													<td>' . $rtrans['transCode'] . '</td>
																													<td style="text-align:right;">' . number_format($rtrans['transAmount'], 2) . '</td>
																													<td>' . $isProcess . '</td>
																													<td>' . $rtrans['transDate'] . '</td>
																													<td><button class="btn btn-primary addShowBarcode" value = "' . $rtrans['transCode'] . '" data-accountID = "' . $rtrans['accountID'] . '" data-id = "' . $rtrans['id'] . '">SHOW CODE</button</td>
																												</tr>
																												';

        $count++;
    }
}
?>
				</tbody>
			</table>

			</div>




		</main>

	</div>




</div>










s
  <!-- End of Page Wrapper -->
  <!-- Bootstrap core JavaScript-->
  <!-- Bootstrap core JavaScript-->
	<script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="design/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
	<script src="design/vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="design/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
	<script src="design/js/demo/datatables-demo.js"></script>
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<script src="design/js/JsBarcode.all.min.js"></script>

	<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});

		function caps(element){
			element.value = element.value.toUpperCase();
		}
		function reloadPage(){
			location.reload();
		}
		//function addBal(){
			//$(".btnAddBalance").click();
	//	}
		$(document).ready(function(){

			$(".btnAddBalance").click(function(){
				mobileNumberVal = $(this).val();
				accountIDVal = $(this).attr("data-accountID");
				fnameVal = $(this).attr("data-fname");
				lnameVal = $(this).attr("data-lname");
				balanceVal = $(this).attr("data-balance");
				$("#hiddenMobileNumber").val(mobileNumberVal);
				$("#hiddenAccountID").val(accountIDVal);

				$("#modal_accountAddBalance").modal("show");

				$("#spanAddFname").text(fnameVal);
				$("#spanAddLname").text(lnameVal);
				$("#spanAddMobileNumber").text(mobileNumberVal);
				$("#spanAddBalance").text(balanceVal);
			});
			$("#modal_accountAddBalance").on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtAddBalance').val("");
					$('#txtAddBalance').focus();
				}, 100);
			});
			$(".addShowBarcode").click(function(){
				tcode = $(this).val();
				accIDVal = $(this).attr("data-accountID");
				idVal = $(this).attr("data-id");
				$.post("accounts/showBarcodeDeposit.php", {transCode:tcode, accountID:accIDVal, id:idVal}, function(res){
					if(res == 0){
						swal("An error occured! Refresh the page and try again or system developer assistance is required!", "", "error");
					}else if(res == 2){
						swal("Please logout and relogin your account!", "", "error");
					}else if(res == 3){
						swal("Transaction Code does not exist! Refresh the page and try again.", "", "error");
					}else{
						$("#modal_barcodeDeposit").modal("show");
						$("#barcodeValDeposit").html(res);
					}
				});
			});
		});
	</script>
	<?php
include "modalboxes.php";
include "accountModals.php";
?>
</body>
</html>
