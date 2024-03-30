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
		<style>
		.blinking{
			animation:blinkingText 1.2s infinite;
		}
        .mainHeader{
             background-image: url('../assets/images/dashboardMainHeader.png');
               background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
        }
		</style>
    </head>

<body>
<div>
    <header class="py-[15px] w-full flex items-center justify-between px-7">
        <span class="text-lg font-bold">MENDEZ SPORTS COMPLEX</span>
        <a href="administrator.php" class="px-6 py-2 bg-green-500 text-sm text-white rounded-full shadow-lg shadow-green-50">Back to Administrator</a>
    </header>

    <div class="grid grid-cols-6">
        <!-- left -->
        <div class="col-start-1 col-end-4 row-start-1 row-end-2 md:col-start-1 md:col-end-3 flex flex-col items-center justify-center py-5 px-3"> 
            <h1 class="text-[3rem] font-bold">2 HITS ULUTAN</h1>

            <div class=" max-w-[350px] w-full  overflow-hidden mt-12">
                <div class="flex justify-center bg-red-500 rounded-br-full  w-full py-3 text-white text-4xl font-bold">Meron</div>
                <p class=" text-[6rem] font-bold text-center">0 </p>

                <!-- payout -->
                <div class="overflow-hidden mt-3">
                    <div class=" flex justify-center bg-red-500 rounded-br-full  w-full py-3 px-5 text-white text-4xl font-bold">Payout</div>
                    <p class=" text-[6rem] font-bold text-center">0.00 </p>
                </div>
            </div>
        </div>
    

        <!-- main -->
        <div class="relative col-start-1 col-end-7 row-start-2 row-end-3  md:row-start-1 row-end-2 md:col-start-3 md:col-end-5 flex flex-col items-center justify-start py-5 px-3">
            <div class="relative -mt-7">
                <img src="../assets/images/dashboardMainHeader.png" class="max-w-[550px] w-full"  />
                <span class="text-white text-[5rem] font-bold absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2">86</span>
            </div>

            <div class="w-full flex items-center justify-center mt-10">
                <div>
                    <i class='bx bx-chevron-left text-red-500 text-2xl'></i>
                    <i class='bx bx-chevron-left text-red-500 text-4xl'></i>
                </div>
                <div class="relative mx-5 flex flex-col items-center justify-center gap-4">
                    <input type = "hidden" id = "hiddenBetFightNumber" value = "<?php echo $currentFightNumber; ?>" />
                    <input type = "hidden" id = "hiddenBetFightID" value = "<?php echo $currentFightID; ?>" />
                    <input type = "hidden" id = "hiddenBetType"/>
                    <input type = "hidden" id = "hiddenWinnerID"/>
                    <p class="text-4xl font-bold mb-3">Fight #</p>
                    <a href= "dashboardtv.php">
                        <span class="bg-green-500 text-white px-7 py-2 rounded-full font-bold text-2xl cursor-pointer">Open</span>
                    </a>
                </div>
                <div>
                    <i class='bx bx-chevron-right text-blue-500 text-4xl'></i>
                    <i class='bx bx-chevron-right text-blue-500 text-2xl'></i>
                </div>
            </div>

            <div class=" text-[5rem] font-bold mt-8">Results</div>
        </div>



        <!-- right -->
        <div class="col-start-4 col-end-7 row-start-1 row-end-2 md:col-start-5 md:col-end-7 flex flex-col items-center justify-center py-5 px-3">
            <h1 class="text-[3rem] font-bold">MANAGEMENT</h1>

            <div class=" max-w-[350px] w-full  overflow-hidden mt-12">
                <div class="flex justify-center bg-blue-500 rounded-bl-full  w-full py-3 text-white text-4xl font-bold">Wala</div>
                <p class=" text-[6rem] font-bold text-center">0 </p>

                <!-- payout -->
                <div class="overflow-hidden mt-3">
                    <div class=" flex justify-center bg-blue-500 rounded-bl-full  w-full py-3 px-5 text-white text-4xl font-bold">Payout</div>
                    <p class=" text-[6rem] font-bold text-center">0.00 </p>
                </div>
            </div>
        </div>
    </div>





    <!-- table -->
    <div class="bg-yellow-500 mt-7">table</div>
</div>




</body>