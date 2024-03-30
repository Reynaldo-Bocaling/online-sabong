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
	<script src="https://cdn.tailwindcss.com"></script>
	  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body id="page-top">
<style>

::-webkit-scrollbar {
  width: 0;
}

</style>



<div id="wrapper" class="fixed top-0 left-0 w-screen h-screen overflow-y-auto">
    <div id="content-wrapper" class="flex h-screen overflow-hidden">

        <!-- sidebar for mobile -->
        <div id="sidebar" class="hide-scrollbar overflow-hidden fixed z-50  w-screen h-screen bg-[rgba(0,0,0,0.3)] hidden transition-all">
            <div class="relative h-screen bg-white border-r shadow-lg shadow-slate-100 px-[20px] py-10 transition-all w-[270px] overflow-y-auto">
                <button id="closeBtn" class="text-red-500 text-3xl absolute top-0 right-0 m-4">&times;</button>
                <span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
                <div class="flex flex-col  mt-9 px-2 overflow-y-auto">
               		<?php
						$links = [
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ?
							'<a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="administrator.php"><i class="fas fa-home mr-2"></i>Home</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="dashboard.php"><i class="bx bxs-plus-circle text-gray-400 mr-2"></i>Betting Odds Display</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminDashboardEvent.php"><i class="bx bxs-dashboard text-gray-400 mr-2" ></i>Dashboard Configuration</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Bettings Management</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
							($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
							($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 12) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
							($_SESSION['roleID'] == 13) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="cashHandler.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Cash INs and OUTs</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						];
						foreach ($links as $link) {
							if (!empty($link)) {
								echo $link;
							}
						}

						?>
                </div>
            </div>
        </div>

        <!-- sidebar for desktop size -->
        <div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-10 transition-all hidden md:flex md:flex-col max-w-[270px] w-full h-screen">
            <span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
            <div class="flex flex-col overflow-y-auto mt-9 px-2">
				

				<?php
					$links = [
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ?
						'<a class="text-sm text-blue-500 bg-blue-50 rounded-lg  p-3 font-normal" href="administrator.php"><i class="fas fa-home mr-2"></i>Home</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="dashboard.php"><i class="bx bxs-plus-circle text-gray-400 mr-2"></i>Betting Odds Display</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminDashboardEvent.php"><i class="bx bxs-dashboard text-gray-400 mr-2" ></i>Dashboard Configuration</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageBettings.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Bettings Management</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminManageSystem.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Users Management</a><a class="text-sm text-gray-600  p-3 font-normal" href="adminManageReports.php"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Reports Management</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminListAccounts.php"><i class="fas fa-users mr-2 text-gray-400"></i>Client Accounts</a>' : '',
						($_SESSION['roleID'] == 1) ? '<a class="changePercentage text-sm text-gray-600  p-3 font-normal" id="changePercentage"><i class="fa fa-edit mr-2 text-gray-400"></i>Change Bet Percentage</a>' : '',
						($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6) ? '<a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 9) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 10) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminTicketCancellation.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Ticket Cancellation</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 12) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="adminReportsManagement.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Reports Management</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
						($_SESSION['roleID'] == 13) ? '<a class="text-sm text-gray-600  p-3 font-normal" href="cashHandler.php"><i class="fas fa-trash mr-2 text-gray-400"></i>Cash INs and OUTs</a><a class="changePassword text-sm text-gray-600  p-3 font-normal" id="changePassword"><i class="fa fa-lock mr-2 text-gray-400"></i>Change Password</a><div class="dropdown-divider"></div><a class="text-sm text-gray-600  p-3 font-normal" href="includes/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>' : '',
					];
					foreach ($links as $link) {
						if (!empty($link)) {
							echo $link;
						}
					}
					?>
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
				<span><i class='bx bx-calendar-star text-blue-500 text-lg'></i> <?php echo date('F j, Y') ?></span>

			</nav>




        	<main class="flex-1 overflow-x-hidden overflow-y-auto p-3">
			<div class="flex items-center justify-between">
				<div class="flex items-center text-sm mb-3 tracking-wide gap-1">
				<p>Dashboard/ </p>
				<span class="font-semibold text-blue-500">Overview</span>
				</div>
				<small><?php echo $currentDate ?></small>
			</div>




			
			<!-- content -->
				<!-- <div class="flex flex-col md:flex-row gap-3  w-full">
					<div class=" w-full md:w-[28%] flex flex-col gap-7	bg-gradient-to-r from-red-400 to-red-500 px-4 py-3 rounded-2xl shadow-xl shadow-red-50">
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

					<div class="max-w-full w-full md:w-[28%] flex flex-col gap-7 bg-gradient-to-r from-blue-400 to-blue-500 px-4 py-3 rounded-2xl shadow-xl shadow-blue-50">
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


					<div class="bg-white px-3 py-2 max-w-full w-full md:w-[44%] bg-white rounded-2xl shadow-md shadow-slate-100 flex items-center">
						<input type = "hidden" id = "hiddenPayoutMeron" value = "' . number_format((float) $payoutMeron, 2, '.', '') . '" />
						<input type = "hidden" id = "hiddenPayoutWala" value = "' . number_format((float) $payoutWala, 2, '.', '') . '" />
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
				</div> -->



				<?php
if($display == 1) {
    echo '
    <div class="flex flex-col md:flex-row gap-3  w-full">
        <div class=" w-full md:w-[28%] flex flex-col gap-7	bg-gradient-to-r from-red-400 to-red-500 px-4 py-3 rounded-2xl shadow-xl shadow-red-50">
            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <small class="text-white">Total bets</small>
                    <span class="text-white text-4xl font-semibold">&#8369; ' . number_format($meronTotalBetAmount) . '</span>
                </div>
                <p class="py-1 px-4 bg-red-100 rounded-full text-red-500 text-xs font-medium">Meron</p>
            </div>

            <div class="flex justify-start  items-start w-full">
                <div class="flex flex-col gap-1 w-1/2">
                    <small class="text-xs text-white">Payout</small>
                    <span class="text-white text-xl font-semibold"> &#8369; ' . number_format($payoutMeron) . '</span>
                </div>
                <div class="flex flex-col gap-1 w-1/2">
                    <small class="text-xs text-white">Result</small>
                    <span class="text-white text-xl font-semibold">
                        ' . ($isBetting == 3 || $isBetting == 6 ? ($isBettingWinner == 1 ? "WIN" : ($isBettingWinner == 2 ? "LOST" : "DRAW")) : ($isBetting == 5 ? "CANCELLED" : "UNSETTLED")) . '
                    </span>
                </div>
            </div>
        </div>

        <div class="max-w-full w-full md:w-[28%] flex flex-col gap-7 bg-gradient-to-r from-blue-400 to-blue-500 px-4 py-3 rounded-2xl shadow-xl shadow-blue-50">
            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <small class="text-white">Total bets</small>
                    <span class="text-white text-4xl font-semibold">&#8369; ' . number_format($walaTotalBetAmount) . '</span>
                </div>
                <p class="py-1 px-4 bg-blue-100 rounded-full text-blue-500 text-xs font-medium">Wala</p>
            </div>

            <div class="flex items-start w-full">
                <div class="flex flex-col gap-1 w-1/2">
                    <small class="text-xs text-white">Payout</small>
                    <span class="text-white text-xl font-semibold">&#8369; ' . number_format($payoutWala) . '</span>
                </div>
                <div class="flex flex-col gap-1 w-1/2">
                    <small class="text-xs text-white">Result</small>
                    <span class="text-white text-xl font-semibold">
                        ' . ($isBetting == 3 || $isBetting == 6 ? ($isBettingWinner == 1 ? "WIN" : ($isBettingWinner == 2 ? "LOST" : "DRAW")) : ($isBetting == 5 ? "CANCELLED" : "UNSETTLED")) . '
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white px-3 py-2 max-w-full w-full md:w-[44%] bg-white rounded-2xl shadow-md shadow-slate-100 flex items-center">
            <input type="hidden" id="hiddenPayoutMeron" value="' . number_format((float) $payoutMeron, 2, '.', '') . '" />
            <input type="hidden" id="hiddenPayoutWala" value="' . number_format((float) $payoutWala, 2, '.', '') . '" />
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
                        <td class="text-center text-sm border-r">' . $currentFightNumber . '</td>
                        <td class="text-center text-sm border-r">' . $isBettingText . '</td>
                        <td class="text-center text-sm">' . DATE("M d, Y", strtotime($curdate)) . '</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    ';
}
?>






















				<div class="flex flex-col md:flex-row md:items-start mt-7 bg-white rounded-xl border max-w-full w-full py-2 px-3">
					<!-- FIGHT CONTROL OPTIONS -->
					<div class="w-full md:w-1/2 md:border-r p-3">
						<p class=" text-sm font-medium  text-blue-500">FIGHT CONTROL OPTIONS</p>
						<div class=" mt-6">
							<?php if ($isBetting == 1): ?>
								<div class="flex items-center gap-1">
									<button class="text-sm text-white px-[25px] py-[10px] font-semibold rounded-full flex items-center bg-gradient-to-r from-purple-500 to-purple-700 " id="btnBettingLast"><i class='bx bx-rotate-right text-xl'></i> Last Call</button>&nbsp;
									<button class="text-sm text-white px-[25px] py-[10px] font-semibold rounded-full flex items-center bg-red-500" id="btnBettingCancel"><i class='bx bx-chevron-left text-xl'></i> CANCEL FIGHT </button>
								</div>
							<?php elseif ($isBetting == 4): ?>
								<div class="flex items-center gap-3">
								<?php if ($meronTotalBetAmount > 100 && $walaTotalBetAmount > 100): ?>
									<button class="text-sm text-white px-[25px] py-[10px] font-semibold rounded-full flex items-center bg-blue-500" id="btnBettingClose"><i class='bx bx-chevron-left text-xl'></i> Close Bettings</button>
								<?php endif;?>
								<button class="text-sm text-white px-[25px] py-[10px] font-semibold rounded-full flex items-center bg-red-500" id="btnBettingCancel"><i class='bx bx-chevron-left text-xl'></i> CANCEL FIGHT </button>
								</div>

							<?php elseif ($isBetting == 2): ?>
								<button class="text-sm text-white px-[25px] py-[10px] font-semibold rounded-full flex items-center bg-blue-500" id="btnBettingWinner"><i class="fas fa-check mr-2"> </i> DECLARE FIGHT WINNER</button>
							<?php elseif ($isBetting == 3): ?>
								<button class="text-sm text-white px-[25px] py-[10px] font-semibold rounded-full flex items-center bg-blue-500" id="btnBettingPayout"><i class="fas fa-check mr-2"> </i> &nbsp;RELEASED PAYOUT </button>
							<?php elseif ($isBetting == 5 || $isBetting == 6): ?>
								<button class="text-sm text-white px-[25px] py-[10px] font-semibold rounded-full flex items-center bg-blue-500" id="btnBettingNew"><i class="fas fa-plus mr-2"></i> &nbsp;START NEW FIGHT</button>
							<?php endif;?>
						</div>


					</div>



					<!-- FIGHT BETTING OPTIONS -->
					<div class="w-full md:w-1/2 p-3">
						<?php if ($isBetting == 1 || $isBetting == 4): ?>

						<p class=" text-sm font-medium  text-blue-500">FIGHT BETTING OPTIONS</p>

						<div class="flex items-center gap-2 mt-6">
							
							<?php if ($closeWala == 0): ?>
								<button class=" text-white px-[25px] py-[10px] rounded-full flex items-center gap-2 bg-blue-500" id="btnCloseWala"><i class="fa fa-lock"> </i> Close Wala Bettings</button>&nbsp;
							<?php else: ?>
								<button class=" text-white px-[25px] py-[10px] rounded-full flex items-center gap-2 bg-blue-500" id="btnOpenWala"><i class="fa fa-lock-open"> </i> Open Wala Bettings </button>&nbsp;
							<?php endif;?>
							<?php if ($closeMeron == 0): ?>
								<button class=" text-white px-[25px] py-[10px] rounded-full flex items-center gap-2 bg-red-500" id="btnCloseMeron"><i class="fa fa-lock"></i> Close Meron Bettings </button>
							<?php else: ?>
								<button class=" text-white px-[25px] py-[10px] rounded-full flex items-center gap-2 bg-red-500" id="btnOpenMeron"><i class="fa fa-lock-open"></i> Open Meron Bettings </button>
							<?php endif;?>
						</div>

						<?php else: ?>

							<p class=" text-sm font-medium  text-blue-500">FIGHT CONTROL OPTIONS</p>
							<div class="card-body">
								<a href="#" class="text-white px-[25px] py-[10px] rounded-full flex items-center gap-2 bg-blue-500 w-[230px]" id="btnBettingNew"><i class="fas fa-plus"> </i> &nbsp;START NEW FIGHT</a>
							</div>
						<?php endif;?>
					</div>
				</div>
        	</main>
    </div>
</div>

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