<div class="bg-white border-r shadow-lg shadow-slate-100  px-[20px] py-8 transition-all hidden md:flex md:flex-col w-[270px]">
    <span class="text-sm font-bold mx-auto"><?php echo $_SESSION['systemName']; ?></span>
	<div class="flex flex-col gap-3">
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
</div>