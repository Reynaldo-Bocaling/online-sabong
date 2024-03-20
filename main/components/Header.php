<nav class="header h-[60px] bg-white shadow-md shadow-slate-100 flex items-center justify-between px-7">			
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow mx-1" style="text-align:center;">

        <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?> <br/> POINTS: &nbsp;<span style="color:red;"><?php  echo number_format($points,2); ?></span><input type = "hidden" id = "hiddenPoints" value = "<?php echo $points; ?>"/>&nbsp;	
        </li>
            <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><i class="fas fa-star"></i> <?php echo $_SESSION['systemName']; ?> <i class="fas fa-star"></i></span>
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-star"></i><i class="fa fa-bars"></i><i class="fa fa-star"></i>
            </button>
        </a>
        
        </li>
    </ul>
</nav>