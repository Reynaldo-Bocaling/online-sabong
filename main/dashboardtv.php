<?php
	session_start();
	require('includes/connection.php');
if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 2 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 6 || $_SESSION['roleID'] == 7 || $_SESSION['roleID'] == 8 || $_SESSION['roleID'] == 10 || $_SESSION['roleID'] == 12){ // 2 = ODDS VIEWING
		$currentFightNumber = 0;
		$isBetting = 0;
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
		$qfight = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`fightNumber` as fightNum, a.`fightDate`, a.`isBetting`, a.`isWinner`,  a.`payoutMeron`, a.`payoutWala`, b.`isBetting` as bettingStatus, c.`percentToLess`, d.`winner` FROM `tblfights` a 
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id 
		LEFT JOIN `tblpercentless` c ON a.percentlessID = c.id 
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
				$qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '".$currentFightCode."' AND a.isCancelled = '0' GROUP BY betType");
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
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $_SESSION['systemName']; ?></title>
		<script src="assets/js/all.js" crossorigin="anonymous"></script>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="design/staffDashboard/staffDashboard.css" rel="stylesheet" >
		<link rel="stylesheet" href="design/dist/sweetalert.css">
		<script src="design/dist/sweetalert.js"></script>
		<style>
		.blinking{
			animation:blinkingText 1.2s infinite;
		}
		@keyframes blinkingText{
			0%{     color: #FFF;    }
			49%{    color: #FFF; }
			60%{    color: transparent; }
			99%{    color:transparent;  }
			100%{   color: #FFF;    }
		}
		</style>
    </head>
    <body id="page-top" style="background-color:#FFF;">
		<div class="row">
			<div class="col-md-12">			
			<?php
			if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
				echo '<a href="administrator.php"><img src = "banners/banner.png" style="width:100%; height:85px;"/></a>	';
				
			}else if($_SESSION['roleID'] == 2){
				echo ' <a href="staffBets.php"><img src = "banners/banner.png" style="width:100%;height:85px;"/></a>';
			}else{
				echo '<a href="includes/logout.php"><img src = "banners/banner.png" style="width:100%;height:85px;"/></a>';
			}
			?>			
			</div>
		</div>
		<div class="row" style="padding:10px;">
			<div class="col-md-6">
			  <?php
				$qbanner = $mysqli->query("SELECT * FROM `tblbanner` WHERE isDefault = '1' LIMIT 1");
				if($qbanner->num_rows > 0){
					while($rbanner = $qbanner->fetch_assoc()){
						$bannerName = $rbanner['eventName'];
						echo ' <p style="text-align:center; background-color:yellow; color:#000;  font-size:70px; font-weight:bold; font-family:\'Lucida sans\'; -webkit-text-stroke: 2px black;  border-radius: 10px; ">'.$bannerName.'</p>';
					}
				}else{}
			?>
			</div>
			<div class="col-md-6">
			<?php
				$qpromoter = $mysqli->query("SELECT * FROM `tblpromoters` WHERE isDefault = '1' LIMIT 1");
				if($qpromoter->num_rows > 0){
					while($rpromoter = $qpromoter->fetch_assoc()){
						$promoterName = $rpromoter['promoterName'];
						echo ' <p style="text-align:center; background-color:yellow; color:#000;  font-size:70px; font-weight:bold; font-family:\'Lucida sans\'; -webkit-text-stroke: 2px black;  border-radius: 10px; ">'.$promoterName.'</p>';
						}
				}else{}
			?>
			</div>
		</div>	
        <div class="row" style=" margin-top:20px;">
			<div class="col-md-12">
				<input type = "hidden" id = "hiddenBetFightNumber" value = "<?php echo $currentFightNumber; ?>" />
				<input type = "hidden" id = "hiddenBetFightID" value = "<?php echo $currentFightID; ?>" />
				<input type = "hidden" id = "hiddenBetType"/>
				<input type = "hidden" id = "hiddenWinnerID"/>
				<?php
				if($_SESSION['oddsSettings'] == 1){
				?>
					<div class="row" style="padding:50px; padding-top:5px;">
						<div class="col-md-4" >
							<p style="text-align:center; background-color:blue; color:#FFF;  font-size:80px; font-weight:bold; font-family:'Lucida sans'; -webkit-text-stroke: 2px black;  border-radius: 10px; ">WALA</p>
						</div>					
						<div class="col-md-4" style="margin-top:-30px;">
							<a href= "dashboard.php" >
								<div  style=" text-align:center; font-weight:bolder;">								
										<span style="border-radius: 10px; background-color:#000; color:#FFF; padding:20px; font-size:70px; font-family:'times new roman'">
										<span style="font-size:50px; ">FIGHT#:</span> <?php echo $currentFightNumber; ?><br/>
										</span>		
											<?php	
												if($isBetting == 1){
													echo '<span style="border-radius: 10px;background-color:green; color:#FFF; padding:20px; font-size:60px; font-family:\'times new roman\'">
													OPEN</span>';
												}else if($isBetting == 4){
													echo '<span style="border-radius: 10px;background-color:green; color:#FFF; padding:20px; font-size:60px; font-family:\'times new roman\'">
													LAST CALL</span>';
												}else{
													echo '<span style="border-radius: 10px;background-color:red; color:#FFF; padding:20px; font-size:60px; font-family:\'times new roman\'">
													CLOSE</span>';
												
												}	
											?>
										</span>
								</div>
							</a>
						</div>					
						<div class="col-md-4">
							<p style="border-radius: 10px; text-align:center; background-color:red; color:#FFF;  font-size:80px; font-weight:bold;font-family:'lucida sans';  -webkit-text-stroke: 2px black;  border-radius: 10px; ">MERON</p>
						</div>
					</div>					
					<div class="row" style="margin-top:-100px;">
						<div class="col-md-4" >
							<p style="font-weight:bolder; text-align:center; font-size:100px; color:#000;" id = "txtWala"><?php echo number_format($walaTotalBetAmount); ?></p>
						</div>					
						<div class="col-md-4"></div>						
						<div class="col-md-4">
							<p style="font-weight:bolder; text-align:center; font-size:100px; color:#000;" id = "txtMeron"><?php echo number_format($meronTotalBetAmount); ?></p>
						</div>
					</div>
					<div class="row" style="padding:50px; padding-top:5px;">
						<div class="col-md-4"></div>					
						<div class="col-md-4" style="margin-top:-100px;">					
							<?php
							if($isBetting == 3 || $isBetting == 6){
								echo '<p style="text-align:center; color:#000; font-size:50px; font-weight:bold; font-family:\'Lucida sans\';">RESULT</p>';
								if($isBettingWinner == 1){
									echo '
									<div  class="blinking" style="border-radius:10px; width:100%; padding:10px; text-align:center; background-color:red; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">MERON</div>';	
									
									
								}else if($isBettingWinner == 2){
									echo '
									<div class="blinking" style="border-radius:10px; width:100%; padding:10px; text-align:center;  background-color:blue; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">WALA</div>';	
								}else{
									echo '
									<div class="blinking" style="border-radius:10px; width:100%; padding:10px; text-align:center; background-color:#000; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">DRAW</div>';	
								}
							}else{}							
							?>
							<?php 
								if($isBetting == 5){
									echo '
									<p class="blinking" style="border-radius:10px; text-align:center; background-color:#000; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">BETTING CANCELLED</p>';	
								}else{
									
								}
							?>
						</div>
						<div class="col-md-4"></div>
					</div>		
					<div class="row" style="margin-top:-80px;">
						<div class="col-md-4" style="text-align:center;">
							<span style="font-weight:bolder; background-color:blue; color:#FFF; text-align:center; font-size:40px;  font-family:'Lucida sans'; padding:5px; padding-left:20px; padding-right:20px; border-radius: 10px;" id = "txtWala">PAYOUT</span>
						</div>
						<div class="col-md-4"></div>
						<div class="col-md-4" style="text-align:center;">	
							<span style="font-weight:bolder; background-color:red; color:#FFF; text-align:center; font-size:40px;  font-family:'Lucida sans'; padding:5px; padding-left:20px; padding-right:20px; border-radius: 10px;" id = "txtMeron">PAYOUT</span>
						</div>
					</div>
					<div class="row" style="margin-top:-30px;">
						<div class="col-md-4">
							<p style="font-weight:bolder; text-align:center; font-size:40px; color:blue; font-family:'Lucida sans';"><span style="font-size:100px; margin-top:-100px;"><?php echo number_format($payoutWala,2); ?></span></p>
						</div>
						<div class="col-md-4"></div>
						<div class="col-md-4" >	
							<p style="font-weight:bolder; text-align:center; font-size:40px; color:red; font-family:'Lucida sans';"><span style="font-size:100px; margin-top:-100px;"><?php echo number_format($payoutMeron,2); ?></span></p>
						</div>
					</div>
				<?php
				}else{
				?>
					<div class="row" style="padding:50px; padding-top:5px;">
						<div class="col-md-4" >
							<p style="border-radius: 10px; text-align:center; background-color:red; color:#FFF;  font-size:80px; font-weight:bold;font-family:'lucida sans';  -webkit-text-stroke: 2px black;  border-radius: 10px; ">MERON</p>
						</div>		
						<div class="col-md-4" style="margin-top:-30px;">
							<a href= "dashboard.php" >
								<div  style=" text-align:center; font-weight:bolder;">
									
										<span style="border-radius: 10px; background-color:#000; color:#FFF; padding:20px; font-size:70px; font-family:'times new roman'">
										<span style="font-size:50px; ">FIGHT#:</span> <?php echo $currentFightNumber; ?><br/>
										</span>
										
											<?php	
												
												if($isBetting == 1){
													echo '<span style="border-radius: 10px;background-color:green; color:#FFF; padding:20px; font-size:60px; font-family:\'times new roman\'">
													OPEN</span>';
												}else if($isBetting == 4){
													echo '<span style="border-radius: 10px;background-color:green; color:#FFF; padding:20px; font-size:60px; font-family:\'times new roman\'">
													LAST CALL</span>';
												}else{
													echo '<span style="border-radius: 10px;background-color:red; color:#FFF; padding:20px; font-size:60px; font-family:\'times new roman\'">
													CLOSE</span>';
												
												}	
												
											?>
										</span>
								</div>
							</a>
						</div>
						<div class="col-md-4" >
							<p style="text-align:center; background-color:blue; color:#FFF;  font-size:80px; font-weight:bold; font-family:'Lucida sans'; -webkit-text-stroke: 2px black;  border-radius: 10px; ">WALA</p>
						</div>
					</div>					
					<div class="row" style="margin-top:-100px;">
						<div class="col-md-4" >
							<p style="font-weight:bolder; text-align:center; font-size:100px; color:#000;" id = "txtMeron"><?php echo number_format($meronTotalBetAmount); ?></p>
						</div>
						<div class="col-md-4"></div>	
						<div class="col-md-4" >
							<p style="font-weight:bolder; text-align:center; font-size:100px; color:#000;" id = "txtWala"><?php echo number_format($walaTotalBetAmount); ?></p>	
						</div>
					</div>
					<div class="row" style="padding:50px; padding-top:5px;">
						<div class="col-md-4"></div>
						<div class="col-md-4" style="margin-top:-100px;">
							
							<?php
							if($isBetting == 3 || $isBetting == 6){
								echo '<p style="text-align:center; color:#000; font-size:50px; font-weight:bold; font-family:\'Lucida sans\';">RESULT</p>';
								if($isBettingWinner == 1){
									echo '
									<div  class="blinking" style="border-radius:10px; width:100%; padding:10px; text-align:center; background-color:red; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">MERON</div>';	
									
									
								}else if($isBettingWinner == 2){
									echo '
									<div class="blinking" style="border-radius:10px; width:100%; padding:10px; text-align:center;  background-color:blue; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">WALA</div>';	
								}else{
									echo '
									<div class="blinking" style="border-radius:10px; width:100%; padding:10px; text-align:center; background-color:#000; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">DRAW</div>';	
								}
							}else{
								
							}
							
							?>
							<?php 
								if($isBetting == 5){
									echo '
									<p class="blinking" style="border-radius:10px; text-align:center; background-color:#000; color:#FFF; font-weight:bold; font-size:50px; font-family:\'calibri\' ">BETTING CANCELLED</p>';	
								}else{
									
								}
							?>
						</div>
						<div class="col-md-4"></div>
					</div>
					<div class="row" style="margin-top:-80px;">
						<div class="col-md-4" style="text-align:center;">
							<span style="font-weight:bolder; background-color:red; color:#FFF; text-align:center; font-size:40px;  font-family:'Lucida sans'; padding:5px; padding-left:20px; padding-right:20px; border-radius: 10px;" id = "txtMeron">PAYOUT</span>
						</div>
						<div class="col-md-4"></div>
						<div class="col-md-4" style="text-align:center;">	
							<span style="font-weight:bolder; background-color:blue; color:#FFF; text-align:center; font-size:40px;  font-family:'Lucida sans'; padding:5px; padding-left:20px; padding-right:20px; border-radius: 10px;" id = "txtWala">PAYOUT</span>
						</div>
					</div>
					<div class="row" style="margin-top:-30px;">
						<div class="col-md-4">
							<p style="font-weight:bolder; text-align:center; font-size:40px; color:red; font-family:'Lucida sans';"><span style="font-size:100px; margin-top:-100px;"><?php echo number_format($payoutMeron,2); ?></span></p>
						</div>
						<div class="col-md-4"></div>
						<div class="col-md-4" >	
							<p style="font-weight:bolder; text-align:center; font-size:40px; color:blue; font-family:'Lucida sans';"><span style="font-size:100px; margin-top:-100px;"><?php echo number_format($payoutWala,2); ?></span></p>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
		<div class="row" style="margin:20px; margin-top:-30px;">
			<div class="col-md-12">	
				<table class="table table-bordered myp" style="border:3px solid #000;">
					<tr style="padding:none; margin:none; border:2px solid #000;">
				<?php
				$fightHisQuery = $mysqli->query("SELECT `fightNumber` FROM (SELECT * FROM `tblfights` ORDER BY id DESC  LIMIT 11 ) sub ORDER BY id ASC");
				if($fightHisQuery->num_rows > 0){
					while($fightHisRow = $fightHisQuery->fetch_assoc()){
						echo '<td class="myp" style="font-weight: 900; text-align:center; font-size:50px; border:3px solid #000;">'.$fightHisRow['fightNumber'].'</td>';
					}
				}
				echo '</tr><tr>';
					$fightHisQuery1 = $mysqli->query("SELECT `isWinner`,`isBetting` FROM (SELECT * FROM `tblfights` ORDER BY id DESC  LIMIT 11 ) sub ORDER BY id ASC");
					if($fightHisQuery1->num_rows > 0){
						while($fightHisRow1 = $fightHisQuery1->fetch_assoc()){
							$isWinner = $fightHisRow1['isWinner'];
							$isBetting = $fightHisRow1['isBetting'];
							if($isBetting == 6){
								if($isWinner == 1){
									echo '<td style="border:3px solid #000;"><p style="margin:auto; border-radius:45px; background:#B10101; padding:20px; width:50px; height:50px;"></p></td>';
								}else if($isWinner == 2){
									echo '<td style="border:3px solid #000;"><p style="margin:auto; border-radius:45px; background:#093aa5; padding:20px; width:50px; height:50px;"></p></td>';
								}else{
									echo '<td style="border:3px solid #000;"><p style="margin:auto; border-radius:45px; background:#3fa423; padding:20px; width:50px; height:50px;"></p></td>';
								}
							}else if($isBetting == 5){	
								echo '<td style="border:3px solid #000;"><p style="margin:auto; border-radius:45px; background:gray; padding:20px; width:50px; height:50px;"></p></td>';
							}
						}
					}
				echo '</tr>';
				?>
				</table>
			</div>
		</div>
		<?php	
			echo '
			<script type="text/javascript">
				setTimeout(function() { window.location=window.location;},3000);
			</script>';
		?>
  	<script src="design/vendor/jquery/jquery.min.js"></script>
	<script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>