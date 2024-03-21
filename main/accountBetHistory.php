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
	<link href="design/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
	<div id="wrapper">
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item dropdown no-arrow mx-1" style="text-align:center;">
						<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?> <br/> POINTS: &nbsp;<span style="color:red;"><?php echo number_format($points, 2); ?></span><input type = "hidden" id = "hiddenPoints" value = "<?php echo $points; ?>"/>&nbsp;
						</li>
						 <div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
							<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
								<i class="fa fa-star"></i><i class="fa fa-bars"></i><i class="fa fa-star"></i>
							</button>
						</a>
						  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
							<a class="dropdown-item" href="index.php">
								<i class="fas fa-home mr-2 text-gray-400"></i>
								Dashboard
							</a>
							<a class="dropdown-item" href="accountBetAddPoints.php">
								<i class="fas fa-plus mr-2 text-gray-400"></i>
								Add Points
							</a>
							<a class="dropdown-item" href="accountBetWithdrawPoints.php">
								<i class="fas fa-minus mr-2 text-gray-400"></i>
								Withdraw Points
							</a>
							<a class="dropdown-item" href="accountBetHistory.php">
								<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
								Bets History
							</a>
							<a class="dropdown-item" href="accountLogs.php">
								<i class="fas fa-money-bill-alt mr-2 text-gray-400"></i>
								Account Logs
							</a>
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
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Finished Fights Bets History: Current Fight Bets is not yet displayed here 2</h6>
						</div>
						<div class="card-body">
							<div class="table-responsive">
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
	</script>
	<?php
include "modalboxes.php";
include "accountModals.php";
?>
</body>
</html>
