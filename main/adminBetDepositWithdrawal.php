<?php
session_start();
require 'includes/connection.php';
$currentDate = date('F j, Y');

if ($_SESSION['roleID'] == 1) {

    $qaccounts = $mysqli->query("SELECT * FROM `tblaccounts` ORDER BY lastname ASC, firstname ASC, balance DESC ");

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

	<!-- Custom fonts for this template-->
	<link href="design/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<script src="design/dist/sweetalert.js"></script>

	<!-- Custom styles for this template-->
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	 <!-- Custom styles for this page -->
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <!-- or -->
  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet" href="./assets/styles/table.css">
</head>

<body id="page-top">
	<div id="wrapper" class="fixed top-0 left-0 w-screen h-screen overflow-y-auto">
		<div id="content-wrapper" class="flex h-screen overflow-hidden">

			<!-- sidebar for mobile -->
			<div id="sidebar" class="hide-scrollbar overflow-hidden fixed z-50  w-screen h-screen bg-[rgba(0,0,0,0.3)] hidden transition-all">
				<div class="relative h-screen bg-white border-r shadow-lg shadow-slate-100 px-[20px] py-10 transition-all w-[270px] overflow-y-auto">
					<button id="closeBtn" class="text-red-500 text-3xl absolute top-0 right-0 m-4">&times;</button>
					<span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
					<div class="flex flex-col gap-3 mt-9 px-2 ">
					<a id="closeBtn"  class="text-sm text-blue-500 p-3 font-normal bg-blue-50 rounded-lg" href="administrator.php">
					<i class="fas fa-home mr-2 "></i>
					Dashboard
					</a>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="adminBetHistory.php">
						<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
						Bets History
					</a>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="adminBetDepositWithdrawal.php">
						<i class="fas fa-money-bill-alt mr-2 text-gray-400"></i>
						Deposit and Withdrawal History
					</a>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="adminListAccounts.php">
						<i class="fas fa-users mr-2 text-gray-400"></i>
						Accounts
					</a>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="adminReports.php">
						<i class="fa fa-pie-chart-o mr-2 text-gray-400"></i>
						Reports
					</a>
					<div class="dropdown-divider"></div>
						<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="includes/logout.php">
							<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
							Logout
						</a>
					</div>
				</div>
			</div>

			<!-- sidebar for desktop size -->

			<div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-10 transition-all hidden md:flex md:flex-col w-[270px] h-screen">
				<span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
				<div class="flex flex-col gap-2 mt-9 px-2">
					<a id="closeBtn"  class="text-sm p-3 font-normal text-gray-600" href="administrator.php">
						<i class="fas fa-home mr-2 "></i>
						Dashboard
					</a>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="adminBetHistory.php">
						<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
						Bets History
					</a>
					<a id="closeBtn"  class="text-sm text-blue-500  bg-blue-50 rounded-lg p-3 font-normal" href="adminBetDepositWithdrawal.php">
						<i class="fas fa-money-bill-alt mr-2 text-gray-400"></i>
						Deposit and Withdrawal
					</a>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="adminListAccounts.php">
						<i class="fas fa-users mr-2 text-gray-400"></i>
						Accounts
					</a>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="adminReports.php">
						<i class="fa fa-pie-chart-o mr-2 text-gray-400"></i>
						Reports
					</a>
					<div class="dropdown-divider"></div>
					<a id="closeBtn"  class="text-sm text-gray-600 p-3 font-normal" href="includes/logout.php">
						<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
						Logout
					</a>
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
				<span><i class='bx bx-calendar-star text-blue-500 text-lg'></i> <?php echo $currentDate ?></span>

			</nav>




			<main class="flex-1 overflow-x-hidden overflow-y-auto p-3 ">
				<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
					<p>Deposit-and-Withdrawal/ </p>
					<span class="font-semibold text-blue-500">History</span>
				</div>
				<span class="text-lg text-black font-semibold">Deposit and Withdrawal History</span>



				<!-- table -->
<div class="p-3 bg-white rounded-lg border overflow-x-auto mt-3">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align:center;">#</th>
							<th style="text-align:center;">Mobile Number</th>
							<th style="text-align:center;">Type of Transaction</th>
							<th style="text-align:left;">Transaction Details</th>
						</tr>
						</thead>
						<tbody>
							<?php $query = $mysqli->query("SELECT c.`transaction`, b.`mobileNumber`, a.`transactionDetails` FROM `tbltransactionlogs` a
								LEFT JOIN `tblaccounts` b ON a.accountID = b.id
								LEFT JOIN `tbltransaction` c ON a.transactionID = c.id
								ORDER BY a.id DESC");if ($query->num_rows > 0) {
    $count = 1;
    while ($row = $query->fetch_assoc()) {
        echo '
											<tr>
												<td style="text-align:center;">' . $count . '</td>
												<td style="text-align:center;">' . $row['mobileNumber'] . '</td>
												<td style="text-align:center;">' . $row['transaction'] . '</td>
												<td style="text-align:left;" class="max-w-[300px] w-full">' . $row['transactionDetails'] . '</td>
											</tr>';
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

	<script type="text/javascript" src="design/js/autoNumeric.js"></script>
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
			$(".btnAddBalance").click(function(){
				mobileNumberVal = $(this).val();
				accountIDVal = $(this).attr("data-accountID");
				fnameVal = $(this).attr("data-fname");
				lnameVal = $(this).attr("data-lname");
				balanceVal = $(this).attr("data-balance");
				$("#hiddenMobileNumber").val(mobileNumberVal);
				$("#hiddenAccountID").val(accountIDVal);

				$("#modal_adminAddBalance").modal("show");

				$("#spanAddFname").text(fnameVal);
				$("#spanAddLname").text(lnameVal);
				$("#spanAddMobileNumber").text(mobileNumberVal);
				$("#spanAddBalance").text(balanceVal);
			});
			$("#modal_adminAddBalance").on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtAddBalance').focus();
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

				$("#modal_adminMinusBalance").modal("show");

				$("#spanMinusFname").text(fnameVal);
				$("#spanMinusLname").text(lnameVal);
				$("#spanMinusMobileNumber").text(mobileNumberVal);
				$("#spanMinusBalance").text(balanceVal);
			});
			$("#modal_adminMinusBalance").on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtMinusBalance').focus();
				}, 100);
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
		});
	</script>
	<?php
include "modalboxes.php";
include "adminModals.php";
?>
</body>

</html>