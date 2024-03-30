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
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="./assets/styles/table.css">

	<title><?php echo $_SESSION['systemName']; ?></title>

	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

	<script src="design/dist/sweetalert.js"></script>

	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">



	<div id="wrapper" class="fixed top-0 left-0 w-screen h-screen overflow-y-auto">

	<div id="sidebar" class="hide-scrollbar overflow-hidden fixed z-50  w-screen h-screen bg-[rgba(0,0,0,0.3)] hidden transition-all">
			<div class="relative h-screen bg-white border-r shadow-lg shadow-slate-100 px-[20px] py-10 transition-all w-[270px] overflow-y-auto">
				<button id="closeBtn" class="text-red-500 text-3xl absolute top-0 right-0 m-4">&times;</button>
				<span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
				<div class="flex flex-col gap-3 mt-9 px-2 ">
				<a class="text-sm text-gray-600  p-3 font-normal rounded-lg" href="index.php">
				<i class="fas fa-home mr-2 text-gray-400"></i>
				Dashboard
				</a>
				<a class="text-sm text-gray-600 p-3 font-normal" href="accountBetAddPoints.php">
					<i class="fas fa-plus mr-2 text-gray-400"></i>
					<span >Add Points</span>
				</a>
				<a class="text-sm   bg-blue-50 text-blue-500  p-3 font-normal rounded-lg" href="accountBetWithdrawPoints.php">
					<i class="fas fa-minus mr-2 text-blue-500"></i>
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
				<a class="changePassword text-sm text-gray-600  p-3 font-normal" id = "changePassword">
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
		</div>


	<div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-10 transition-all hidden md:flex md:flex-col w-[270px] h-screen">
		<span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
		<div class="flex flex-col gap-3 mt-9 px-2">
			<a class="text-sm text-gray-600  p-3 font-normal rounded-lg" href="index.php">
				<i class="fas fa-home mr-2 text-gray-400"></i>
				Dashboard
				</a>
				<a class="text-sm text-gray-600 p-3 font-normal" href="accountBetAddPoints.php">
					<i class="fas fa-plus mr-2 text-gray-400"></i>
					<span >Add Points</span>
				</a>
				<a class="text-sm   bg-blue-50 text-blue-500  p-3 font-normal rounded-lg" href="accountBetWithdrawPoints.php">
					<i class="fas fa-minus mr-2 text-blue-500"></i>
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
				<a class=" changePassword text-sm text-gray-600  p-3 font-normal" id = "changePassword">
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
		<nav class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7 ">
			<button id="openBtn" class="w-[30px] flex flex-col gap-[5px] border-none focus:outline-none md:hidden py-[10px]">
				<div class="w-full h-[3px] rounded-full bg-black"></div>
				<div class="w-full h-[3px] rounded-full bg-black"></div>
			</button>
			<div class="text-base font-mdium text-gray-700 flex items-center gap-2">
				<p class="hidden md:flex">Welcome,</p>
				<span class="text-black tracking-tighter font-semibold"><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?> </span>
				<img src="./assets/images/waving.png" class="w-[50px] hidden md:flex" />
			</div>

			<div class=" flex items-center gap-2">
				<p class="text-sm text-gray-700">Your Points:</pc>
				<p class="<?php echo ($points < 10) ? 'text-red-500' : 'text-green-500'; ?> text-sm font-semibold">&#8369;<?php echo number_format($points, 2); ?></p>
			<div>
		</nav>


		<!-- main conutent -->
		<main class="flex-1 overflow-x-hidden overflow-y-auto p-3">


			<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
				<p>withdrawPoints/ </p>
				<span class="font-semibold text-blue-500">Withdraw-Points&Withdraw-History</span>
			</div>
			<div class="flex flex-col pl-2 pr-5 mb-3">
				<span class="text-lg text-black font-semibold">Withdraw Points and Withdraw History</span>
				<div class="max-w-[600px] w-full py-1"><?php
if ($points < 100) {
    echo '<h6 class="text-xs font-medium text-red-500">Take Note: Points must be 100 or more to submit request for withdrawal of points! Only One Withdrawal Request at a time!</h6>';
} else {
    echo '
														<button class="text-xs font-medium px-4 py-2 rounded-full bg-red-500 text-white btnWithdrawBalance" data-accountID = "' . $accountID . '" data-fname = "' . $firstname . '" data-lname = "' . $lastname . '" data-balance = "' . number_format($points) . '" value = "' . $mobileNumber . '"><i class="fas fa-minus-circle"></i> CLICK TO WITHDRAW POINTS</button><br/><br/>';
}?>
				</div>
			</div>



			<div class="p-3 bg-white rounded-lg border overflow-x-auto">
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
					<?php
$qtrans = $mysqli->query("SELECT * FROM `tblnewbalance` WHERE `accountID` = '" . $accountID . "' AND transID = '2' ORDER BY id DESC");
if ($qtrans->num_rows > 0) {
    $count = 1;
    while ($rtrans = $qtrans->fetch_assoc()) {
        if ($rtrans['isProcess'] == 0) {
            $isProcess = "Proceed to Cashier";
        } else if ($rtrans['isProcess'] == 1) {
            $isProcess = "Withdrawn Successfully";
        } else if ($rtrans['isProcess'] == 5) {
            $isProcess = "Cancelled";
        }
        echo '
								<tr  style="text-align:center;">
									<td>' . $count . '</td>

									<td>' . $rtrans['transCode'] . '</td>
									<td style="text-align:right;">' . number_format($rtrans['transAmount']) . '</td>
									<td>' . $isProcess . '</td>
									<td>' . $rtrans['transDate'] . '</td>
									<td><button class="px-4 py-2 rounded-full bg-blue-500 text-white withdrawShowBarcode" value = "' . $rtrans['transCode'] . '" data-accountID = "' . $rtrans['accountID'] . '" data-id = "' . $rtrans['id'] . '">SHOW CODE</button</td>
								</tr>
								';

        $count++;
    }
}
?>
                  <tbody>

                  </tbody>
                </table>

			</div>




		</main>

	</div>




</div>








  <!-- End of Page Wrapper -->
  <!-- Bootstrap core JavaScript-->
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
	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
	<script src="design/js/JsBarcode.all.min.js"></script>
	<link rel="stylesheet" href="design/dist/sweetalert.css">
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
		$(document).ready(function(){
			$("#btnPlaceBet").click(function(){
				$('#modal_placeBet').modal("show");
			});
			$('#modal_placeBet').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtBetAmount').focus();
				}, 100);
			});

			$(".btnWithdrawBalance").click(function(){
				mobileNumberVal = $(this).val();
				accountIDVal = $(this).attr("data-accountID");
				fnameVal = $(this).attr("data-fname");
				lnameVal = $(this).attr("data-lname");
				balanceVal = $(this).attr("data-balance");
				$("#hiddenMobileNumber").val(mobileNumberVal);
				$("#hiddenAccountID").val(accountIDVal);

				$("#modal_accountWithdrawBalance").modal("show");

				$("#spanWithdrawFname").text(fnameVal);
				$("#spanWithdrawLname").text(lnameVal);
				$("#spanWithdrawMobileNumber").text(mobileNumberVal);
				$("#spanWithdrawBalance").text(balanceVal);
			});
			$("#modal_accountWithdrawBalance").on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtWithdrawBalance').val("");
					$('#txtWithdrawBalance').focus();
				}, 100);
			});

			$(".withdrawShowBarcode").click(function(){
				tcode = $(this).val();
				accountIDVal = $(this).attr("data-accountID");
				idVal = $(this).attr("data-id");
				$.post("accounts/showBarcodeWithdraw.php", {transCode:tcode, accountID:accountIDVal, id:idVal}, function(res){
					if(res == 0){
						swal("An error occured! Refresh the page and try again or system developer assistance is required!", "", "error");
					}else if(res == 2){
						swal("Please logout and relogin your account!", "", "error");
					}else if(res == 3){
						swal("Transaction Code does not exist! Refresh the page and try again.", "", "error");
					}else{
						$("#modal_barcodeWithdraw").modal("show");
						$("#barcodeValWithdraw").html(res);

					}
				});
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
include "accountModals.php";
?>
</body>

</html>
