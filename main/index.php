<?php
session_start();
require 'includes/connection.php';
if ($_SESSION['roleID'] == 3) {
    if ($_SESSION['accountID']) {
        $qaccount = $mysqli->query("SELECT * FROM `tblaccounts` WHERE id = '" . $_SESSION['accountID'] . "' ");
        if ($qaccount->num_rows > 0) {
            while ($raccount = $qaccount->fetch_assoc()) {
                $points = $raccount['balance'];
                // $points = 800;
                $mobileNumber = $raccount['mobileNumber'];
            }
        }
        $queryPercent = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
        $rowPercent = $queryPercent->fetch_assoc();
        $percentToLess = $rowPercent['percentToLess'];

        $qfight = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`fightNumber` as fightNum, ev.`eventDate`, a.`isBetting`, a.`isWinner`, b.`isBetting`  as bettingStatus, d.`winner` FROM `tblfights` a
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id
		LEFT JOIN `tblwinner` d ON a.isWinner = d.id
		LEFT JOIN `tblevents` ev ON a.eventID = ev.id
		WHERE a.id = (select max(id) from tblfights);");
        if ($qfight->num_rows > 0) {
            //isBetting = 1 means OPEN, isBetting = 2 means CLOSED
            while ($rfight = $qfight->fetch_assoc()) {
                $currentFightID = $rfight['id'];
                $currentFightNumber = $rfight['fightNum'];
                $currentFightCode = $rfight['fightCode'];
                $curdate = $rfight['eventDate'];
                $isBetting = $rfight['isBetting'];
                $winner = $rfight['winner'];
                $isBettingWinner = $rfight['isWinner'];
                if ($isBetting == 1) {
                    $isBettingText = $rfight['bettingStatus'];
                } else if ($isBetting == 3 || $isBetting == 6) {
                    $isBettingText = $rfight['bettingStatus'];
                } else {
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

            // date
            $currentDate = date('F j, Y');

            if ($isBetting == 1 || $isBetting == 2 || $isBetting == 4) {
                $qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '" . $currentFightCode . "' AND a.isCancelled = '0' GROUP BY a.betType");
                if ($qbets->num_rows > 0) {
                    while ($rbets = $qbets->fetch_assoc()) {
                        $betType = $rbets['betType'];
                        if ($betType == 1) {
                            $totalBetAmount += $rbets['bets'];
                            $meronTotalBetAmount = $rbets['bets'];
                        } else {
                            $totalBetAmount += $rbets['bets'];
                            $walaTotalBetAmount = $rbets['bets'];
                        }
                    }
                    if ($meronTotalBetAmount > 0 && $walaTotalBetAmount > 0) {
                        $threePercent = ($totalBetAmount * $percentToLess);
                        $totalAmountLessThreePercent = ($totalBetAmount - $threePercent);
                        $totalAmountIfMeronWins = ($totalAmountLessThreePercent - $meronTotalBetAmount);
                        $pesoEquivalentIfMeronWins = ($totalAmountIfMeronWins / $meronTotalBetAmount);
                        $payoutMeron = (($pesoEquivalentIfMeronWins * 100) + 100);

                        $totalAmountIfWalaWins = ($totalAmountLessThreePercent - $walaTotalBetAmount);
                        $pesoEquivalentIfWalaWins = ($totalAmountIfWalaWins / $walaTotalBetAmount);
                        $payoutWala = (($pesoEquivalentIfWalaWins * 100) + 100);
                    } else {
                    }
                }
            } else if ($isBetting == 3 || $isBetting == 5 || $isBetting == 6) {
                $qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '" . $currentFightCode . "' AND a.isCancelled = '0' GROUP BY a.betType");
                if ($qbets->num_rows > 0) {
                    while ($rbets = $qbets->fetch_assoc()) {
                        $betType = $rbets['betType'];
                        if ($betType == 1) {
                            $totalBetAmount += $rbets['bets'];
                            $meronTotalBetAmount = $rbets['bets'];
                        } else {
                            $totalBetAmount += $rbets['bets'];
                            $walaTotalBetAmount = $rbets['bets'];
                        }
                    }
                    if ($meronTotalBetAmount > 0 && $walaTotalBetAmount > 0) {
                        $threePercent = ($totalBetAmount * $percentToLess);
                        $totalAmountLessThreePercent = ($totalBetAmount - $threePercent);
                        $totalAmountIfMeronWins = ($totalAmountLessThreePercent - $meronTotalBetAmount);
                        $pesoEquivalentIfMeronWins = ($totalAmountIfMeronWins / $meronTotalBetAmount);
                        $payoutMeron = (($pesoEquivalentIfMeronWins * 100) + 100);

                        $totalAmountIfWalaWins = ($totalAmountLessThreePercent - $walaTotalBetAmount);
                        $pesoEquivalentIfWalaWins = ($totalAmountIfWalaWins / $walaTotalBetAmount);
                        $payoutWala = (($pesoEquivalentIfWalaWins * 100) + 100);
                    } else {
                    }
                }
            }

            $display = 1;
        } else {
            $display = 0;
        }

    } else {

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
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body id="page-top">
<div id="wrapper" class="fixed top-0 left-0 w-screen">
	<div id="content-wrapper" class="flex h-screen overflow-hidden">
		<!-- sidebar -->
	<div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-10 transition-all hidden md:flex md:flex-col w-[270px]">
		<span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
		<div class="flex flex-col gap-5 mt-9 px-2">
			<a class="text-sm text-gray-600  p-3 font-normal bg-blue-50 rounded-lg" href="index.php">
				<i class="fas fa-home mr-2 text-blue-500"></i>
				<span class="text-blue-500">Dashboard</span>
			</a>
			<a class="text-sm text-gray-600  p-3 font-normal" href="accountBetAddPoints.php">
				<i class="fas fa-plus mr-2 text-gray-400"></i>
				Add Points
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
					<p>Dashboard/ </p>
					<span class="font-semibold text-blue-500">Overview</span>
				</div>
				<div class="flex gap-3 w-full">
					<div class="w-[28%] flex flex-col gap-7	bg-gradient-to-r from-red-400 to-red-500 px-4 py-3 rounded-2xl shadow-xl shadow-red-50">
						<div class="flex items-center justify-between">
							<div class="flex flex-col gap-1">
								<small class="text-white">Total bets</small>
								<span class="text-white text-4xl font-semibold">&#8369 <?php echo number_format($meronTotalBetAmount) ?></span>
							</div>
							<p class="py-1 px-4 bg-red-100 rounded-full text-red-500 text-xs font-medium">Meron</p>
						</div>


						<div class="flex justify-start  items-start w-full">
							<div class="flex flex-col gap-1 w-1/2">
								<small class="text-xs text-white">Payout</small>
								<span class="text-white text-xl font-semibold"> <?php echo number_format($payoutMeron) ?></span>
							</div>
							<div class="flex flex-col gap-1 w-1/2">
								<small class="text-xs text-white">Result</small>
								<span class="text-white text-xl font-semibold">
									<?php
if ($isBetting == 3 || $isBetting == 6) {
    if ($isBettingWinner == 1) {
        echo 'WIN';
    } else if ($isBettingWinner == 2) {
        echo 'LOST';
    } else {
        echo 'DRAW';
    }
} else if ($isBetting == 5) {
    echo 'CANCELLED';
} else {
    echo 'UNSETTLED';
}?>
								</span>
							</div>
						</div>
					</div>

					<div class="w-[28%] flex flex-col gap-7 bg-gradient-to-r from-blue-400 to-blue-500 px-4 py-3 rounded-2xl shadow-xl shadow-blue-50">
						<div class="flex items-center justify-between">
							<div class="flex flex-col gap-1">
								<small class="text-white">Total bets</small>
								<span class="text-white text-4xl font-semibold">&#8369 <?php echo number_format($walaTotalBetAmount) ?></span>
							</div>
							<p class="py-1 px-4 bg-blue-100 rounded-full text-blue-500 text-xs font-medium">Wala</p>
						</div>


						<div class="flex items-start w-full">
							<div class="flex flex-col gap-1 w-1/2">
								<small class="text-xs text-white">Payout</small>
								<span class="text-white text-xl font-semibold"><?php echo number_format($payoutWala) ?></span>
							</div>
							<div class="flex flex-col gap-1 w-1/2">
								<small class="text-xs text-white">Result</small>
								<span class="text-white text-xl font-semibold">
									<?php
if ($isBetting == 3 || $isBetting == 6) {
    if ($isBettingWinner == 1) {
        echo 'WIN';
    } else if ($isBettingWinner == 2) {
        echo 'LOST';
    } else {
        echo 'DRAW';
    }
} else if ($isBetting == 5) {
    echo 'CANCELLED';
} else {
    echo 'UNSETTLED';
}?>
								</span>
							</div>
						</div>
					</div>


					<div class="bg-white px-3 py-2 w-[44%] bg-white rounded-2xl shadow-md shadow-slate-100 flex items-center">
						<table class="w-full">
							<thead>
								<tr class="h-12">
									<th class="font-bold text-sm text-center border-r">Fight #</th>
									<th class="font-bold text-sm text-center border-r">Betting Status</th>
									<th class="font-bold text-sm text-center">Date</th>
								</tr>
							</thead>
							<tbody>
								<tr class="h-12 mt-2">
									<td class="text-center text-sm border-r"><?php echo $currentFightNumber ?></td>
									<td class="text-center text-sm border-r"><?php echo $isBettingText ?></td>
									<td class="text-center text-sm"><?php echo DATE('M d, Y', strtotime($curdate)) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>





				<!-- last table -->
				<div class="bg-white px-3 py-5 w-full bg-white rounded-2xl shadow-md shadow-slate-200 flex flex-col items-start justify-center mt-4">
					<p class="mx-auto text-sm font-medium mb-7 text-blue-500">Your current bets for this fight</p>
					<table class="w-full">
					<thead>
						<tr style="text-align:center;">
							<th class="text-sm text-center font-bold border-r w-[25%]">Bet Under</th>
							<th class="text-sm text-center font-bold border-r w-[25%]">Bet Amount</th>
							<th class="text-sm text-center font-bold border-r w-[25%]">Possible Winning Amount</th>
							<th class="text-sm text-center font-bold border-r w-[25%]">Result</th>
						</tr>
					</thead>
					<tbody>
					<?php
if ($display == 1) {
    if ($isBetting == 1 || $isBetting == 2 || $isBetting == 4) {
        $qbets2 = $mysqli->query("SELECT a.`betType` as betTypeID, a.betAmount, b.betType FROM `tblbetliststemp` a LEFT JOIN `tblbettypes` b ON a.betType = b.id WHERE a.fightCode = '" . $currentFightCode . "' AND a.accountID = '" . $_SESSION['accountID'] . "'  ");
        if ($qbets2->num_rows > 0) {
            $possibleWinning = 0;
            while ($rbets2 = $qbets2->fetch_assoc()) {
                if ($rbets2['betType'] == "MERON") {
                    $possibleWinning = ($rbets2['betAmount'] / 100) * $payoutMeron;
                    echo '
																		<tr class="h-12">';
                } else {
                    $possibleWinning = ($rbets2['betAmount'] / 100) * $payoutWala;
                    echo '
																		<tr class="h-12">';
                }

                echo '
																		<td class="text-sm text-center font-bold border-r w-[25%]">' . $rbets2['betType'] . '</td>
																		<td class="text-sm text-center font-bold border-r w-[25%]">' . number_format($rbets2['betAmount']) . '</td>
																		<td class="text-sm text-center font-bold border-r w-[25%]">' . number_format($possibleWinning) . '</td>
																		<td class="text-sm text-center font-bold border-r w-[25%]">';

                if ($isBetting == 3 || $isBetting == 6) {
                    if ($isBettingWinner == 1) {
                        if ($rbets2['betTypeID'] == 1) {
                            echo 'WIN';
                        } else if ($rbets2['betTypeID'] == 2) {
                            echo 'LOST';
                        } else {
                            echo 'DRAW';
                        }

                    } else if ($isBettingWinner == 2) {
                        if ($rbets2['betTypeID'] == 1) {
                            echo 'LOST';
                        } else if ($rbets2['betTypeID'] == 2) {
                            echo 'WIN';
                        } else {
                            echo 'DRAW';
                        }
                    } else {
                        echo 'DRAW';
                    }
                } else if ($isBetting == 5) {
                    echo 'CANCELLED';
                } else {
                    echo 'UNSETTLED';
                }
                echo '
																	</td>
																	</tr>';
            }
        }

    } else if ($isBetting == 3 || $isBetting == 5 || $isBetting == 6) {
        $qbets2 = $mysqli->query("SELECT a.`betType` as betTypeID, a.betAmount, b.betType FROM `tblbetlists` a LEFT JOIN `tblbettypes` b ON a.betType = b.id WHERE a.fightCode = '" . $currentFightCode . "' AND a.accountID = '" . $_SESSION['accountID'] . "'  ");
        if ($qbets2->num_rows > 0) {
            $possibleWinning = 0;
            while ($rbets2 = $qbets2->fetch_assoc()) {
                if ($rbets2['betType'] == "MERON") {
                    $possibleWinning = ($rbets2['betAmount'] / 100) * $payoutMeron;
                    echo '
																		<tr class="h-12">';
                } else {
                    $possibleWinning = ($rbets2['betAmount'] / 100) * $payoutWala;
                    echo '
																		<tr class="h-12">';
                }
                echo '
																		<td ctext-sm text-center font-bold border-r w-[25%]">' . $rbets2['betType'] . '</td>
																		<td ctext-sm text-center font-bold border-r w-[25%]">' . number_format($rbets2['betAmount']) . '</td>
																		<td ctext-sm text-center font-bold border-r w-[25%]">' . number_format($possibleWinning) . '</td>
																		<td ctext-sm text-center font-bold border-r w-[25%]">';

                if ($isBetting == 3 || $isBetting == 6) {
                    if ($isBettingWinner == 1) {
                        if ($rbets2['betTypeID'] == 1) {
                            echo 'WIN';
                        } else if ($rbets2['betTypeID'] == 2) {
                            echo 'LOST';
                        } else {
                            echo 'DRAW';
                        }

                    } else if ($isBettingWinner == 2) {
                        if ($rbets2['betTypeID'] == 1) {
                            echo 'LOST';
                        } else if ($rbets2['betTypeID'] == 2) {
                            echo 'WIN';
                        } else {
                            echo 'DRAW';
                        }
                    } else {
                        echo 'DRAW';
                    }
                } else if ($isBetting == 5) {
                    echo 'CANCELLED';
                } else {
                    echo 'UNSETTLED';
                }
                echo '
																	</td>
																	</tr>';
            }
        }
    }

} else {}

?>
					</tbody>
				</table>
				</div>

			</main>
		</div>
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
		function reloadPage(){
			location.reload();
		}
		function checkRefresh(){
			bettingStatusID = $("#hiddenBettingStatusID").val();
			if(bettingStatusID == 2 || bettingStatusID == 3 || bettingStatusID == 5 || bettingStatusID == 6){
				setInterval(function(){
					location.reload();
				}, 5000);
			}else{

			}

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
			<?php
if ($isBetting == 1 || $isBetting == 2 || $isBetting == 4) {
    ?>
				setInterval(function(){
					$("#betsContainer").load("bets/accountBetsLoadLatestData.php");

				}, 5000);

			<?php
} else {
    ?>
				checkRefresh();
			<?php
}
?>
		});
	</script>
	<?php
include "modalboxes.php";
include "accountModals.php";
?>
</body>
</html>
