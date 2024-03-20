<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
	<?php
	if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
		echo
		'<a class="dropdown-item" href="administrator.php">
			<i class="fas fa-home mr-2 text-gray-400"></i>
			Home									
		</a>';
	}
	if($_SESSION['roleID'] == 1){
		echo
		'<a class="dropdown-item" href="dashboard.php">
			<i class="fas mr-2 text-gray-400"></i>
			Betting Odds Display								
		</a>';
	}
	if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
		echo
		'<a class="dropdown-item" href="adminDashboardEvent.php">
			<i class="fas fa-setting mr-2 text-gray-400"></i>
			Dashboard Configuration
		</a>';
	}
	if($_SESSION['roleID'] == 1){
		echo
		'<a class="dropdown-item" href="adminTicketCancellation.php">
			<i class="fas fa-trash mr-2 text-gray-400"></i>
			Ticket Cancellation				
		</a>';
	}
	if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
		echo
		'<a class="dropdown-item" href="adminManageBettings.php">
			<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
			Bettings Management					
		</a>
		';
	}
	if($_SESSION['roleID'] == 1){
		echo
		'<a class="dropdown-item" href="adminManageSystem.php">
			<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
			Users Management					
		</a>
		<a class="dropdown-item" href="adminManageReports.php">
			<i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
			Reports Management					
		</a>';
	}
		if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4){
		echo
		'<a class="dropdown-item" href="adminListAccounts.php">
			<i class="fas fa-users mr-2 text-gray-400"></i>
			Client Accounts
		</a>
		';
	}
	if($_SESSION['roleID'] == 1){
		echo
		'<a class="dropdown-item" id = "changePercentage">
			<i class="fa fa-edit mr-2 text-gray-400"></i>
			Change Bet Percentage
		</a>';
	}
	if($_SESSION['roleID'] == 1 || $_SESSION['roleID'] == 4 || $_SESSION['roleID'] == 5 || $_SESSION['roleID'] == 6){
		echo
		'<a class="dropdown-item" id = "changePassword">
			<i class="fa fa-lock mr-2 text-gray-400"></i>
			Change Password
		</a>
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="includes/logout.php">
			<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
			Logout
		</a>';
	}
	if($_SESSION['roleID'] == 9){
		echo
		'
		<a class="dropdown-item" href="includes/logout.php">
			<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
			Logout
		</a>';
	}
	
	if($_SESSION['roleID'] == 10){
		
		echo
		'<a class="dropdown-item" href="adminTicketCancellation.php">
			<i class="fas fa-trash mr-2 text-gray-400"></i>
			Ticket Cancellation				
		</a>
		<a class="dropdown-item" id = "changePassword">
			<i class="fa fa-lock mr-2 text-gray-400"></i>
			Change Password
		</a>
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="includes/logout.php">
			<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
			Logout
		</a>';
	}
	
	if($_SESSION['roleID'] == 12){
		
		echo
		'<a class="dropdown-item" href="adminReportsManagement.php">
			<i class="fas fa-trash mr-2 text-gray-400"></i>
			Reports Management				
		</a>
		<a class="dropdown-item" id = "changePassword">
			<i class="fa fa-lock mr-2 text-gray-400"></i>
			Change Password
		</a>
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="includes/logout.php">
			<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
			Logout
		</a>';
	}
	if($_SESSION['roleID'] == 13){
		
		echo
		'<a class="dropdown-item" href="cashHandler.php">
			<i class="fas fa-trash mr-2 text-gray-400"></i>
			Cash INs and OUTs		
		</a>
		<a class="dropdown-item" id = "changePassword">
			<i class="fa fa-lock mr-2 text-gray-400"></i>
			Change Password
		</a>
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="includes/logout.php">
			<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
			Logout
		</a>';
	}
	?>
</div>