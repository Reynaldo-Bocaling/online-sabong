<?php
session_start();
require 'includes/connection.php';

if ($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 2 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 6 || $_SESSION['roleID'] == 7 || $_SESSION['roleID'] == 8 || $_SESSION['roleID'] == 10 || $_SESSION['roleID'] == 12) { // 2 = ODDS VIEWING
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

    $qfight = $mysqli->query("SELECT a.`id`, a.`fightCode`, a.`fightNumber` as fightNum, a.`fightDate`, a.`isBetting`, a.`isWinner`,  a.`payoutMeron`, a.`payoutWala`, b.`isBetting` as bettingStatus, d.`winner` FROM `tblfights` a
		LEFT JOIN `tblbettingstatus` b ON a.isBetting = b.id
		LEFT JOIN `tblwinner` d ON a.isWinner = d.id
		WHERE a.id = (select max(id) from tblfights);");
    $queryPercent = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
    $rowPercent = $queryPercent->fetch_assoc();
    $percentToLess = $rowPercent['percentToLess'];
    if ($qfight->num_rows > 0) {
        //isBetting = 1 means OPEN, isBetting = 2 means CLOSED
        while ($rfight = $qfight->fetch_assoc()) {
            $currentFightID = $rfight['id'];
            $currentFightCode = $rfight['fightCode'];
            $currentFightNumber = $rfight['fightNum'];
            $curdate = $rfight['fightDate'];
            $isBetting = $rfight['isBetting'];
            $winner = $rfight['winner'];

            $winnerFightID = $rfight['id'];
            $winnerID = $rfight['isWinner'];
            if ($isBetting == 1) {
                $isBettingText = $rfight['bettingStatus'];
            } else if ($isBetting == 3 || $isBetting == 6) {
                $isBettingText = $rfight['bettingStatus'];
                $isBettingWinner = $rfight['isWinner'];

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
        if ($isBetting == 1 || $isBetting == 2 || $isBetting == 4) {
            $qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetliststemp` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '" . $currentFightCode . "' AND a.isCancelled = '0' GROUP BY betType");
        } else if ($isBetting == 3 || $isBetting == 5 || $isBetting == 6) {
            $qbets = $mysqli->query("SELECT a.betType, SUM(betAmount) as bets FROM `tblbetlists` a LEFT JOIN `tblfights` b ON a.fightCode = b.fightCode WHERE b.fightCode = '" . $currentFightCode . "' AND a.isCancelled = '0' GROUP BY betType");
        }
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
        $display = 1;
    } else {
        $display = 0;
    }

} else {
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
        <!-- <link href="design/staffDashboard/staffDashboard.css" rel="stylesheet" > -->
		<link rel="stylesheet" href="design/dist/sweetalert.css">
		<script src="design/dist/sweetalert.js"></script>
		<script src="https://cdn.tailwindcss.com"></script>
        	  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
		
    </head>

<body>
    <div>
        <header class="py-[15px] w-full flex items-center justify-between px-7">
            <span class="text-lg font-bold">MENDEZ SPORTS COMPLEX</span>
            <a href="administrator.php" class="px-6 py-2 bg-green-500 text-sm text-white rounded-full shadow-lg shadow-green-50">Back to Administrator</a>
        </header>
        <?php if ($_SESSION['oddsSettings'] == 1) {?>
            <div class="grid grid-cols-6">
                <!-- left -->
                <div class="col-start-1 col-end-4 row-start-1 row-end-2 md:col-start-1 md:col-end-3 flex flex-col items-center justify-center py-5 px-3"> 
                    <h1 class="text-[3rem] font-bold">
                        <?php
                            $qbanner = $mysqli->query("SELECT * FROM `tblbanner` WHERE isDefault = '1' LIMIT 1");
                            if ($qbanner->num_rows > 0) {
                                while ($rbanner = $qbanner->fetch_assoc()) {
                                    $bannerName = $rbanner['eventName'];
                                    echo ' <p class="relative z-0 text-black text-[3rem] font-bold mt-[400px] md:mt-0">' . $bannerName . '</p>';
                                }
                            } else {}
                        ?>
                    </h1>

                    <div class=" max-w-[350px] w-full  overflow-hidden mt-12">
                        <div class="flex justify-center bg-blue-500 rounded-br-full  w-full py-3 text-white text-[2rem] font-bold">Wala</div>
                        <p class=" text-[6rem] font-bold text-center"><?php echo number_format($walaTotalBetAmount); ?></p>

                        <!-- payout -->
                        <div class="overflow-hidden mt-3">
                            <div class=" flex justify-center bg-blue-500 rounded-br-full  w-full py-3 px-5 text-white text-[2rem] font-bold">Payout</div>
                            <p class=" text-[6rem] font-bold text-center"><?php echo number_format($payoutWala, 2); ?></p>
                        </div>
                    </div>
                </div>
            

                <!-- main -->
                <div class="relative col-start-1 col-end-7 row-start-2 row-end-3  md:row-start-1 row-end-2 md:col-start-3 md:col-end-5 flex flex-col items-center justify-start py-5 px-3">
                    <div class="relative -mt-7">
                        <img src="./assets/images/dashboardMainHeader.png" class="max-w-[550px] w-full"  />
                        <span class="text-white text-[5rem] font-bold absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2"><?php echo $currentFightNumber; ?></span>
                    </div>

                    <div class="w-full flex items-center justify-center mt-10">
                        <div>
                            <i class='bx bx-chevron-left text-red-500 text-2xl'></i>
                            <i class='bx bx-chevron-left text-red-500 text-[2rem]'></i>
                        </div>
                        <div class="relative mx-5 flex flex-col items-center justify-center gap-4">
                            <input type = "hidden" id = "hiddenBetFightNumber" value = "<?php echo $currentFightNumber; ?>" />
                            <input type = "hidden" id = "hiddenBetFightID" value = "<?php echo $currentFightID; ?>" />
                            <input type = "hidden" id = "hiddenBetType"/>
                            <input type = "hidden" id = "hiddenWinnerID"/>
                            <p class="text-[2rem] font-bold mb-3">Fight #</p>
                            <a href= "dashboardtv.php">
                                <?php
                                if ($isBetting == 1) {
                                        echo '<span class="bg-green-500 text-white px-7 py-2 rounded-full font-bold text-2xl cursor-pointer">OPEN</span>';
                                    } else if ($isBetting == 4) {
                                        echo '<span class="bg-green-500 text-white px-7 py-2 rounded-full font-bold text-2xl cursor-pointer">LAST CALL</span>';
                                    } else {
                                        echo '<span class="bg-red-500 text-white px-7 py-2 rounded-full font-bold text-2xl cursor-pointer">CLOSE</span>';
                                    }
                                ?>
                            </a>
                        </div>
                        <div>
                            <i class='bx bx-chevron-right text-blue-500 text-[2rem]'></i>
                            <i class='bx bx-chevron-right text-blue-500 text-2xl'></i>
                        </div>
                    </div>

                    <div class=" text-[5rem] font-bold mt-8">Results</div>
                </div>



                <!-- right -->
                <div class="col-start-4 col-end-7 row-start-1 row-end-2 md:col-start-5 md:col-end-7 flex flex-col items-center justify-center py-5 px-3">
                    <h1 class="text-[3rem] font-bold">
                    <?php
                        $qpromoter = $mysqli->query("SELECT * FROM `tblpromoters` WHERE isDefault = '1' LIMIT 1");
                            if ($qpromoter->num_rows > 0) {
                                while ($rpromoter = $qpromoter->fetch_assoc()) {
                                    $promoterName = $rpromoter['promoterName'];
                                    echo '<p class="relative z-0 text-black text-[3rem] font-bold">' . $promoterName . '</p>';
                                }
                            } else {
                            }
                    ?>
                    </h1>

                    <div class=" max-w-[350px] w-full  overflow-hidden mt-12">
                        <div class="flex justify-center bg-red-500 rounded-bl-full  w-full py-3 text-white text-[2rem] font-bold">Meron</div>
                        <p class=" text-[6rem] font-bold text-center"><?php echo number_format($meronTotalBetAmount); ?></p>

                        <!-- payout -->
                        <div class="overflow-hidden mt-3">
                            <div class=" flex justify-center bg-red-500 rounded-bl-full  w-full py-3 px-5 text-white text-[2rem] font-bold">Payout</div>
                            <p class=" text-[6rem] font-bold text-center"><?php echo number_format($payoutMeron, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>

        <?php } else {?>
            <div class="grid grid-cols-6">
                <!-- left -->
                <div class="col-start-1 col-end-4 row-start-1 row-end-2 md:col-start-1 md:col-end-3 flex flex-col items-center justify-center py-5 px-3"> 
                    <h1 class="text-[3rem] font-bold">
                        <?php
                            $qbanner = $mysqli->query("SELECT * FROM `tblbanner` WHERE isDefault = '1' LIMIT 1");
                            if ($qbanner->num_rows > 0) {
                                while ($rbanner = $qbanner->fetch_assoc()) {
                                    $bannerName = $rbanner['eventName'];
                                    echo ' <p class="relative z-0 text-black text-[3rem] font-bold mt-[400px] md:mt-0">' . $bannerName . '</p>';
                                }
                            } else {}
                        ?>
                    </h1>

                    <div class=" max-w-[350px] w-full  overflow-hidden mt-12">
                        <div class="flex justify-center bg-red-500 rounded-br-full  w-full py-3 text-white text-[2rem] font-bold">Meron</div>
                        <p class=" text-[6rem] font-bold text-center"><?php echo number_format($meronTotalBetAmount); ?></p>

                        <!-- payout -->
                        <div class="overflow-hidden mt-3">
                            <div class=" flex justify-center bg-red-500 rounded-br-full  w-full py-3 px-5 text-white text-[2rem] font-bold">Payout</div>
                            <p class=" text-[6rem] font-bold text-center"><?php echo number_format($payoutMeron, 2); ?></p>
                        </div>
                    </div>
                </div>
            

                <!-- main -->
                <div class="relative col-start-1 col-end-7 row-start-2 row-end-3  md:row-start-1 row-end-2 md:col-start-3 md:col-end-5 flex flex-col items-center justify-start py-5 px-3">
                    <div class="relative -mt-7">
                        <img src="./assets/images/dashboardMainHeader.png" class="max-w-[550px] w-full"  />
                        <span class="text-white text-[5rem] font-bold absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2"><?php echo $currentFightNumber; ?></span>
                    </div>

                    <div class="w-full flex items-center justify-center mt-10">
                        <div>
                            <i class='bx bx-chevron-left text-red-500 text-2xl'></i>
                            <i class='bx bx-chevron-left text-red-500 text-[2rem]'></i>
                        </div>
                        <div class="relative mx-5 flex flex-col items-center justify-center gap-4">
                            <input type = "hidden" id = "hiddenBetFightNumber" value = "<?php echo $currentFightNumber; ?>" />
                            <input type = "hidden" id = "hiddenBetFightID" value = "<?php echo $currentFightID; ?>" />
                            <input type = "hidden" id = "hiddenBetType"/>
                            <input type = "hidden" id = "hiddenWinnerID"/>
                            <p class="text-[2rem] font-bold mb-3">Fight #</p>
                            <a href= "dashboardtv.php">
                                <?php
                                if ($isBetting == 1) {
                                        echo '<span class="bg-green-500 text-white px-7 py-2 rounded-full font-bold text-2xl cursor-pointer">OPEN</span>';
                                    } else if ($isBetting == 4) {
                                        echo '<span class="bg-green-500 text-white px-7 py-2 rounded-full font-bold text-2xl cursor-pointer">LAST CALL</span>';
                                    } else {
                                        echo '<span class="bg-red-500 text-white px-7 py-2 rounded-full font-bold text-2xl cursor-pointer">CLOSE</span>';
                                    }
                                ?>
                            </a>
                        </div>
                        <div>
                            <i class='bx bx-chevron-right text-blue-500 text-[2rem]'></i>
                            <i class='bx bx-chevron-right text-blue-500 text-2xl'></i>
                        </div>
                    </div>

                    <div class=" text-[5rem] font-bold mt-8">Results</div>
                </div>



                <!-- right -->
                <div class="col-start-4 col-end-7 row-start-1 row-end-2 md:col-start-5 md:col-end-7 flex flex-col items-center justify-center py-5 px-3">
                    <h1 class="text-[3rem] font-bold">
                    <?php
                        $qpromoter = $mysqli->query("SELECT * FROM `tblpromoters` WHERE isDefault = '1' LIMIT 1");
                            if ($qpromoter->num_rows > 0) {
                                while ($rpromoter = $qpromoter->fetch_assoc()) {
                                    $promoterName = $rpromoter['promoterName'];
                                    echo '<p class="relative z-0 text-black text-[3rem] font-bold">' . $promoterName . '</p>';
                                }
                            } else {
                            }
                    ?>
                    </h1>

                    <div class=" max-w-[350px] w-full  overflow-hidden mt-12">
                        <div class="flex justify-center bg-blue-500 rounded-bl-full  w-full py-3 text-white text-[2rem] font-bold">Wala</div>
                        <p class=" text-[6rem] font-bold text-center"><?php echo number_format($walaTotalBetAmount); ?></p>

                        <!-- payout -->
                        <div class="overflow-hidden mt-3">
                            <div class=" flex justify-center bg-blue-500 rounded-bl-full  w-full py-3 px-5 text-white text-[2rem] font-bold">Payout</div>
                            <p class=" text-[6rem] font-bold text-center"><?php echo number_format($payoutWala, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>


        <!-- table -->
        <div class="bg-white mt-7 px-7">
        <table class="w-full myp" style="border:2px solid #000;">
					<tr style="padding:none; margin:none; border:2px solid #000;">
				<?php
                        $fightHisQuery = $mysqli->query("SELECT `fightNumber` FROM (SELECT * FROM `tblfights` ORDER BY id DESC  LIMIT 11 ) sub ORDER BY id ASC");
                        if ($fightHisQuery->num_rows > 0) {
                            while ($fightHisRow = $fightHisQuery->fetch_assoc()) {
                                echo '<td class="myp" style="font-weight: 800; text-align:center; font-size:45px; border:2px solid #000;">' . $fightHisRow['fightNumber'] . '</td>';
                            }
                        }
                        echo '</tr><tr>';
                        $fightHisQuery1 = $mysqli->query("SELECT `isWinner`,`isBetting` FROM (SELECT * FROM `tblfights` ORDER BY id DESC  LIMIT 11 ) sub ORDER BY id ASC");
                        if ($fightHisQuery1->num_rows > 0) {
                            while ($fightHisRow1 = $fightHisQuery1->fetch_assoc()) {
                                $isWinner = $fightHisRow1['isWinner'];
                                $isBetting = $fightHisRow1['isBetting'];
                                if ($isBetting == 6) {
                                    if ($isWinner == 1) {
                                        echo '<td style="border:2px solid #000; padding:5px;"><p class="m-auto h-[50px] w-[50px] rounded-full bg-red-500 p-[20px]"></p></td>';
                                    } else if ($isWinner == 2) {
                                        echo '<td style="border:2px solid #000; padding:5px"><p class="m-auto h-[50px] w-[50px] rounded-full bg-blue-500 p-[20px]"></p></td>';
                                    } else {
                                        echo '<td style="border:2px solid #000;"><p style="margin:auto; border-radius:45px; background:#3fa423; padding:20px; width:50px; height:50px;"></p></td>';
                                    }
                                } else if ($isBetting == 5) {
                                    echo '<td style="border:2px solid #000;"><p class="m-auto h-[50px] w-[50px] rounded-full bg-slate-400 p-[20px]"></p></td>';
                                }
                            }
                        }
                        echo '</tr>';
                        ?>
				</table>
        </div>
    </div>

    <script type="text/javascript">
        setTimeout(function() { window.location=window.location;},3000);
    </script>';
    <script src="design/vendor/jquery/jquery.min.js"></script>
    <script src="design/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
