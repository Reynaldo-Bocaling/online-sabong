<?php
session_start();
require 'includes/connection.php';
if ($_SESSION['roleID'] == 3) {
    $qaccount = $mysqli->query("SELECT * FROM `tblaccounts` WHERE id = '" . $_SESSION['accountID'] . "' ");
    if ($qaccount->num_rows > 0) {
        while ($raccount = $qaccount->fetch_assoc()) {
            $points = $raccount['balance'];
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
	<link href="design/css/sb-admin-2.min.css" rel="stylesheet">
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="./assets/styles/table.css">

	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper" class="fixed top-0 left-0 w-screen h-screen overflow-y-auto">
	<!-- msidebar for mobile -->
	<div id="sidebar" class="hide-scrollbar overflow-hidden fixed z-50  w-screen h-screen bg-[rgba(0,0,0,0.3)] hidden transition-all">
		<div class="relative h-screen bg-white border-r shadow-lg shadow-slate-100 px-[20px] py-10 transition-all w-[270px] overflow-y-auto">
			<button id="closeBtn" class="text-red-500 text-3xl absolute top-0 right-0 m-4">&times;</button>
			<span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
			<div class="flex flex-col gap-3 mt-9 px-2 ">
			<a class="text-sm  p-3 font-normal rounded-lg" href="index.php">
				<i class="fas fa-home mr-2 text-gray-400"></i>
				Dashboard
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal " href="accountBetAddPoints.php">
				<i class="fas fa-plus mr-2 text-gray-400"></i>
				<span>Add Points</span>
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal" href="accountBetWithdrawPoints.php">
				<i class="fas fa-minus mr-2 text-gray-400"></i>
				Withdraw Points
			</a>
			<a class="text-sm rounded-lg  bg-blue-50 text-blue-500 p-3 font-normal" href="accountBetHistory.php">
				<i class="fas fa-clipboard-list mr-2"></i>
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
			<a class="text-sm  p-3 font-normal rounded-lg" href="index.php">
				<i class="fas fa-home mr-2 text-gray-400"></i>
				Dashboard
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal " href="accountBetAddPoints.php">
				<i class="fas fa-plus mr-2 text-gray-400"></i>
				<span>Add Points</span>
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal" href="accountBetWithdrawPoints.php">
				<i class="fas fa-minus mr-2 text-gray-400"></i>
				Withdraw Points
			</a>
			<a class="text-sm rounded-lg  bg-blue-50 text-blue-500 p-3 font-normal" href="accountBetHistory.php">
				<i class="fas fa-clipboard-list mr-2"></i>
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





		<main class="flex-1 overflow-x-hidden overflow-y-auto p-3">

			<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
				<p>BetHistory/ </p>
				<span class="font-semibold text-blue-500">Bet-History</span>
			</div>
			<div class="flex flex-col pl-2 pr-5 mb-3">
				<span class="text-lg text-black font-semibold">Finished Fights Bets History: Current Fight Bets is not yet displayed here</span>

			</div>



			<div class="p-3 bg-white rounded-lg border overflow-x-auto">
				<input type = "hidden" id = "hiddenMobileNumber" />
								<input type = "hidden" id = "hiddenAccountID" />
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th style="text-align:center;">#</th>
											<th style="text-align:center;">Fight Code</th>
											<th style="text-align:center;">Bet Code</th>
											<th style="text-align:center;">Bet Under</th>
											<th style="text-align:center;">Status</th>
											<th style="text-align:center;">Amount</th>
											<th style="text-align:center;">Result</th>
											<th style="text-align:center;">Odds</th>
											<th style="text-align:center;">Payout</th>
											<th style="text-align:center;">Is Claimed?</th>
											<th style="text-align:center;">Is Returned?</th>
										</tr>
									</thead>
									<tbody>
									<?php
$oddsMeron = 0;
$oddsWala = 0;
$totalPayout = 0;
$qbets = $mysqli->query("SELECT a.`betCode`, a.`betType`, a.`betAmount`, a.`isClaim`, a.`betRoleID`, a.`accountID`, a.`isReturned`, b.`fightCode`, b.`fightNumber`, b.`isWinner`, b.`isBetting`, b.`payoutMeron`, b.`payoutWala`, c.`betType` as betTypeStatus, d.`isBetting` as bettingStatus, e.`winner`, ev.`eventDate` FROM `tblbetlists` a
									   LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode
									   LEFT JOIN `tblbettypes` c ON a.betType = c.id
									   LEFT JOIN `tblbettingstatus` d ON b.isBetting = d.id
									   LEFT JOIN `tblwinner` e ON b.isWinner = e.id
									   LEFT JOIN tblaccounts f ON a.accountID = f.id
									   LEFT JOIN `tblevents` ev ON b.eventID = ev.id
										WHERE a.accountID = '" . $_SESSION['accountID'] . "'
									   ORDER BY a.id DESC ");
if ($qbets->num_rows > 0) {
    $count = 1;
    while ($rbets = $qbets->fetch_assoc()) {
        $oddsMeron = $rbets['payoutMeron'];
        $oddsWala = $rbets['payoutWala'];
        echo '
											 <tr>
												<td style="text-align:center;">' . $count . '</td>
												<td style="text-align:center;">' . $rbets['fightCode'] . '</td>
												<td>' . $rbets['betCode'] . '</td>
												<td style="text-align:center;">' . $rbets['betTypeStatus'] . '</td>
												<td style="text-align:right;">' . number_format($rbets['betAmount']) . '</td>
												<td style="text-align:center;">' . $rbets['bettingStatus'] . '</td>';
        if ($rbets['isWinner'] == 0) {
            if ($rbets['bettingStatus'] == "CANCELLED") {
                echo '
															<td style="text-align:center;">CANCELLED</td>';
            } else {
                echo '
															<td style="text-align:center;">UNSETTLED</td>';
            }
            echo '
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>';
        } else if ($rbets['isWinner'] == 3) {
            echo '
															<td style="text-align:center;">' . $rbets['winner'] . '</td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>';
        } else {
            if ($rbets['betTypeStatus'] == $rbets['winner']) {
                echo '
															<td style="text-align:center;">WIN</td>';
                if ($rbets['betType'] == 1) {
                    echo '
																<td style="text-align:center;">' . number_format($oddsMeron, 2) . '</td>';
                    $totalPayout = ($rbets['betAmount'] / 100) * $oddsMeron;
                } else if ($rbets['betType'] == 2) {
                    echo '<td style="text-align:center;">' . number_format($oddsWala, 2) . '</td>';
                    $totalPayout = ($rbets['betAmount'] / 100) * $oddsWala;
                }
                echo '
																<td style="text-align:center;">' . number_format($totalPayout, 2) . '</td>';
            } else {
                echo '
															<td style="text-align:center;">LOST</td>';
                if ($rbets['betType'] == 1) {
                    echo '
																<td style="text-align:center;">' . number_format($oddsMeron, 2) . '</td>';
                } else if ($rbets['betType'] == 2) {
                    echo '
																<td style="text-align:center;">' . number_format($oddsWala, 2) . '</td>';
                } else {
                    echo '<td style="text-align:center;"></td>';
                }
                echo '<td style="text-align:center;"></td>';
            }
        }

        if ($rbets['betRoleID'] == 3) {
            if ($rbets['isClaim'] == 0) {
                echo '
													<td style="text-align:center;">NO</td>';
            } else {
                echo '
													<td style="text-align:center;">YES</td>';
            }
        } else {
            echo '
													<td style="text-align:center;">WALK-IN</td>';
        }

        if ($rbets['bettingStatus'] == "CANCELLED" || $rbets['isWinner'] == 3) {
            if ($rbets['isReturned'] == 1) {
                echo '
														<td style="text-align:center;">RETURNED</td>';
            } else {
                echo '
														<td style="text-align:center;">FOR REFUND</td>';
            }
        } else {
            echo '
													<td style="text-align:center;"></td>';
        }
        echo '
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
	<script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="design/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="design/js/sb-admin-2.min.js"></script>
	<script src="design/vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="design/vendor/datatables/dataTables.bootstrap4.min.js"></script>
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

			$("#btnPlaceBet").click(function(){
				$('#modal_placeBet').modal("show");
			});
			$('#modal_placeBet').on('shown.bs.modal', function () {
				setTimeout(function (){
					$('#txtBetAmount').focus();
				}, 100);
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
