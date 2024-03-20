<?php
session_start();
require('../includes/connection.php');
if(!isset($_SESSION['accountID'])){
	header('location: ../../index.php');
}else{	
	$qfight = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`fightNumber` as fightNum, a.`fightDate`, a.`isBetting`, a.`isWinner`, b.`isBetting`  as bettingStatus, d.`winner` FROM `tblfights` a 
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
		LEFT JOIN `tblwinner` d ON a.isWinner = d.id
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
			$winner = $rfight['winner'];
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
		if($isBetting == 1 || $isBetting == 2 ||  $isBetting == 4){
			$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isCancelled = '0' GROUP BY a.betType");
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
		}else if($isBetting == 3 || $isBetting == 5 || $isBetting == 6){
			$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isCancelled = '0' GROUP BY a.betType");
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
			
			
		}
		$display = 1;
	}else{
		$display = 0;
	}
	
	if($display == 1){
	echo '
	<input type = "hidden" id = "hiddenBettingStatusID" value = "'.$isBetting.'"/>
		<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">BETS FOR MERON</div>
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr style="text-align:center;">
						<th style="background-color:#f34141; color:#FFF; font-weight:bold;">Total Bets</th>
						<th style="background-color:#f34141; color:#FFF; font-weight:bold;">Payout</th>
						<th style="background-color:#f34141; color:#FFF; font-weight:bold;">Result</th>
					</tr>
				</thead>
				<tbody>
					<tr style="text-align:center;">
						<td style="background-color:#000; color:yellow; font-weight:bold; font-size:15px;">'.number_format($meronTotalBetAmount).'</td>
						<td style="background-color:#000; color:#FFF; font-weight:bold; font-size:15px;">'.number_format($payoutMeron).'</td>
						<td style="background-color:#000; color:#FFF; font-weight:bold; font-size:11px;">';
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
				</tbody>
			</table>
		</div>
		<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">BETS FOR WALA</div>
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr style="text-align:center;">
						<th style="background-color:#4e73df; color:#FFF; font-weight:bold;">Total Bets</th>
						<th style="background-color:#4e73df; color:#FFF; font-weight:bold;">Payout</th>
						<th style="background-color:#4e73df; color:#FFF; font-weight:bold;">Result</th>
					</tr>
				</thead>
				<tbody>
					<tr style="text-align:center;">
						<td style="background-color:#000; color:yellow; font-weight:bold; font-size:15px;">'.number_format($walaTotalBetAmount).'</td>
						<td style="background-color:#000; color:#FFF; font-weight:bold; font-size:15px;">'.number_format($payoutWala).'</td>
						<td style="background-color:#000; color:#FFF; font-weight:bold; font-size:11px;">';
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
		
		<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">YOUR CURRENT BETs FOR THIS FIGHT</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr style="text-align:center;">
						<th style="font-weight:bold;">Bet Under</th>
						<th style="font-weight:bold;">Bet Amount</th>
						<th style="font-weight:bold;">Possible Winning Amount</th>
						<th style="font-weight:bold;">Result</th>
					</tr>
				</thead>
				<tbody>';
				if($isBetting == 1 || $isBetting == 2 ||  $isBetting == 4){
					$qbets2 = $mysqli->query("SELECT a.`betType` as betTypeID, a.betAmount, b.betType FROM `tblbetliststemp` a LEFT JOIN `tblbettypes` b ON a.betType = b.id WHERE a.fightCode = '".$currentFightCode."' AND a.isCancelled = '0' AND a.accountID = '".$_SESSION['accountID']."' ");
					if($qbets2->num_rows > 0){
						$possibleWinning = 0;
						while($rbets2 = $qbets2->fetch_assoc()){
							if($rbets2['betType'] == "MERON"){
								$possibleWinning = ($rbets2['betAmount'] /100) * $payoutMeron;
								echo '	
								<tr style="text-align:center; background-color: #f34141; color:#fff;">';
							}else{
								$possibleWinning = ($rbets2['betAmount'] /100) * $payoutWala;
								echo '	
								<tr style="text-align:center; background-color:#4e73df; color:#fff;">';
							}
							echo '	
								<td style="font-weight:bold; font-size:12px;">'.$rbets2['betType'].'</td>
								<td style="font-weight:bold; font-size:12px;">'.number_format($rbets2['betAmount']).'</td>
								<td style="font-weight:bold; font-size:12px;">'.number_format($possibleWinning).'</td>
								<td style="font-weight:bold; font-size:12px;">';
								if($isBetting == 3 || $isBetting == 6){
									if($isBettingWinner == 1){
										if($rbets2['betTypeID'] == 1){
											echo 'WIN';
										}else if($rbets2['betTypeID'] == 2){
											echo 'LOST';
										}else{
											echo 'DRAW';
										}
									}else if($isBettingWinner == 2){
										if($rbets2['betTypeID'] == 1){
											echo 'LOST';
										}else if($rbets2['betTypeID'] == 2){
											echo 'WIN';
										}else{
											echo 'DRAW';
										}
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
							</tr>';
						}
					}
				}else if($isBetting == 3 || $isBetting == 5 || $isBetting == 6){
					$qbets2 = $mysqli->query("SELECT a.`betType` as betTypeID, a.betAmount, b.betType FROM `tblbetlists` a LEFT JOIN `tblbettypes` b ON a.betType = b.id WHERE a.fightCode = '".$currentFightCode."' AND a.isCancelled = '0' AND a.accountID = '".$_SESSION['accountID']."' ");
					if($qbets2->num_rows > 0){
						$possibleWinning = 0;
						while($rbets2 = $qbets2->fetch_assoc()){
							if($rbets2['betType'] == "MERON"){
								$possibleWinning = ($rbets2['betAmount'] /100) * $payoutMeron;
								echo '	
								<tr style="text-align:center; background-color: #f34141; color:#fff;">';
							}else{
								$possibleWinning = ($rbets2['betAmount'] /100) * $payoutWala;
								echo '	
								<tr style="text-align:center; background-color:#4e73df; color:#fff;">';
							}
							echo '	
								<td style="font-weight:bold; font-size:12px;">'.$rbets2['betType'].'</td>
								<td style="font-weight:bold; font-size:12px;">'.number_format($rbets2['betAmount']).'</td>
								<td style="font-weight:bold; font-size:12px;">'.number_format($possibleWinning).'</td>
								<td style="font-weight:bold; font-size:12px;">';
								if($isBetting == 3 || $isBetting == 6){
									if($isBettingWinner == 1){
										if($rbets2['betTypeID'] == 1){
											echo 'WIN';
										}else if($rbets2['betTypeID'] == 2){
											echo 'LOST';
										}else{
											echo 'DRAW';
										}
									}else if($isBettingWinner == 2){
										if($rbets2['betTypeID'] == 1){
											echo 'LOST';
										}else if($rbets2['betTypeID'] == 2){
											echo 'WIN';
										}else{
											echo 'DRAW';
										}
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
							</tr>';
						}
					}
				}
				echo '
				</tbody>
			</table>
		</div>
		<script>
			$(document).ready(function(){
				checkRefresh();
			});
		</script>';
	}else{
		echo '<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">NO BETS DATA AVAILABLE!</div>';
	}
}
?>