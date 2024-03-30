<script>
	$(document).ready(function(){
		$(".changePassword").click(function(){
			$("#passwordOld").val("");
			$("#passwordNew").val("");
			$("#passwordConfirm").val("");
			$("#modal_changePassword").modal("show");
		});
		$("aside ul .panel").click(function(){
			//$("aside ul .panel a").removeClass("active");
			$(this).children("a").addClass("active");
		});
		
		$("#closeModalPassword").click(function(){
			$("aside ul .changePassword").children("a").removeClass("active");
		});
		$(".changePercentage").click(function(){
			$("#modal_changePercentage").modal("show");
		});
		$("#modal_changePercentage").on('shown.bs.modal', function () {
			setTimeout(function (){
				$('#txtPercentage').val("");
				$('#txtPercentage').focus();
			}, 100);
		});	
	});
</script>
<div class="container" >
	<div class="modal fade" id="modal_changePassword" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#smbtPassword").click(function(){
				pOld = $("#passwordOld").val();
				pNew = $("#passwordNew").val();
				pConfirm = $("#passwordConfirm").val();
				
				if(pOld == ""){
					$("#passwordOld").focus();
					swal("Old Password is required!", "", "error");
				}else if(pNew == ""){
					$("#passwordNew").focus();
					swal("New Password is required!", "", "error");
				}else if(pConfirm == ""){
					$("#passwordConfirm").focus();
					swal("You must confirm your new password!", "", "error");
				}else if(pNew != pConfirm){
					$("#passwordNew").focus();
					swal("Passwords did not match!", "", "error");
				}else if(pOld == pNew){
					
				}else{
					$("#loader").show();
					showModal();
					$.post("password/changePassword.php", {newPassword:pNew, oldPassword:pOld}, function(res){
						$("#loader").hide();
						hideModal();
						if(res == 1){
							$("#modal_changePassword").modal("hide");
							swal({
								title: "Password has been changed successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								$("aside ul .changePassword").children("a").removeClass("active");
								
							});
						}else if(res == 2){
							$("#passwordOld").focus();
							swal("Old Password is incorrect!", "", "error");
						}else{
							swal("Password cannot be changed at this time. Refresh the page and try again.", "", "error");
						}
					});
				}
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="flex items-center justify-between px-3 pt-3">
					<h4 class="modal-title text-lg  text-black font-bold">Change Password</h4>
					<button type="button" class="text-3xl text-red-500 font-bold" id="closeModalPassword" data-dismiss="modal" >&times;</button>
				</div>
				<div class="modal-body">

					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="well">
								<div class="mb-3 mt-1" style="margin:1px;">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Old Password:</p>
										<input type="password" class="form-control" id="passwordOld" maxlength = "64" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" placeholder="Your Old Password Here..."/>
									</div>
								</div>
							</div>
							<div class="well">
								<div class="my-4" style="margin:1px;">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">New Password:</p>
										<input type="text" class="form-control" id="passwordNew" maxlength = "64" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" placeholder="New Password Here..."/>
									</div>
								</div>
							</div>
							<div class="well">
								<div class="my-3" style="margin:1px;">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Confirm New Password:</p>
										<input type="text" class="form-control" id="passwordConfirm" maxlength = "64" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" placeholder="Confirm New Password Here..."/>
									</div>
								</div>
							</div>
							<input type="button" id = "smbtPassword" class="text-base text-white font-semibold w-full py-[12px] rounded-full mt-3 mb-3 bg-blue-600" value = "Change Password">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_changePercentage" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		 function isNumberKey(txt, evt) {
		  var charCode = (evt.which) ? evt.which : evt.keyCode;
		  if (charCode == 46) {
			//Check if the text already contains the . character
			if (txt.value.indexOf('.') === -1) {
			  return true;
			} else {
			  return false;
			}
		  } else {
			if (charCode > 31 &&
			  (charCode < 48 || charCode > 57))
			  return false;
		  }
		  return true;
		}
		$(document).ready(function(){
			perc = document.getElementById("txtPercentage");

			// Execute a function when the user releases a key on the keyboard
			perc.addEventListener("keyup", function(event) {
			  // Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
				// Cancel the default action, if needed
					event.preventDefault();
				// Trigger the button element with a click
					document.getElementById("smbtPercentage").click();
				}
			});
			$("#smbtPercentage").click(function(){
				percentVal = $("#txtPercentage").val();
				
				if(percentVal == ""){
					$("#txtPercentage").focus();
					swal("Percentage is required!", "", "error");
				}else{
					$("#loader").show();
					showModal();
					$.post("admin/savePercentage.php", {newPercent:percentVal}, function(res){
						$("#loader").hide();
						hideModal();
						$("#modal_changePercentage").modal("hide");
						if(res == 1){
							
							swal({
								title: "Bet Percentage has been changed successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();	
							});
						}else if(res == 3){
							swal("Unable to change bet percentage due to current active fight. Refresh the page and try again.", "", "error");
						}else{
							swal("Bet Percentage cannot be changed at this time. Refresh the page and try again.", "", "error");
						}
					});
				}
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="p-3 flex items-center justify-between">
					<h5 class="modal-title text-lg text-black font-bold">Change Bet Percentage</h5>
					<button type="button" class="text-3xl text-red-500 font-bold" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 -mt-4">
							<div class="well">
								<div class="row pl-3 flex justify-cnter">
									<div class="">
									<?php
										$qperc = $mysqli->query("SELECT `percentToLess` FROM `tblpercentless` ORDER BY id DESC LIMIT 1");
										if($qperc->num_rows > 0){
											$rperc = $qperc->fetch_assoc();
											$curPercentage = $rperc['percentToLess'] * 100;
											if(floor($curPercentage) == $curPercentage) {
												echo '<div class="text-lg text-black font-semibold">Current Bet Percentage: <span class="text-blue-500 font-bold">'.number_format($curPercentage).'%</span></div>';
											} else {
												echo '<div class="text-lg text-black font-semibold">Current Bet Percentage: <span class="text-blue-500 font-bold">'.number_format($curPercentage, 2).'%</span></div>';
											}
										}
									?>


										
									</div>
								</div><br/>
								<div class="row">
									<div class="col-md-12 px-4">
										<p class="text-sm text-black font-bold mb-2">New Bet Percentage:</p>
										<input type="text" class="form-control py-4" id="txtPercentage"  maxlength = "5" onkeypress="return isNumberKey(this, event);" placeholder="New Bet Percentage"/>
									</div>
								</div>
							</div>
							
							<div class="row" style="margin-top:10px; text-align:center;">
								<div class="col-md-12">
									<input type="button" id = "smbtPercentage" class="text-base text-white font-semibold w-full py-[12px] my-3 rounded-full bg-blue-500" value = "Save New Bet Percentage">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="container" >
	<div class="modal fade" id="modalMessage" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<button type="button" class="close" id="closeModal" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Message</h4>
				</div>
				<div class="modal-body">
					<div class="well">
						<p><h3 class="text-center"><span id = "modalMessageHere"></span></h3></p>
					</div>
				</div>
				<div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_addStaffAccount" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#smbtAddStaffAccount").click(function(){
				usernameVal = $("#addStaffUsername").val();
				systemnameVal = $("#addStaffSystemname").val();
				bettypeVal = $("#addStaffBetType").val();
				
				if(usernameVal  == ""){
					$("#addStaffUsername").focus();
					swal("Username is required!", "", "error");
				}else if(systemnameVal == ""){
					$("#addStaffSystemname").focus();
					swal("Fullname is required!", "", "error");
				}else if(bettypeVal == 0){
					$("#addStaffBetType").focus();
					swal("Select Bet Type to handle", "", "error");
				}else{
					$("#loader").show();
					showModal();
					$.post("admin/addStaffAccount.php", {username:usernameVal, systemname:systemnameVal, bettype:bettypeVal}, function(res){
						$("#loader").hide();
						hideModal();
						if(res == 1){
							$("#modal_addStaffAccount").modal("hide");
							swal({
								title: "Added Account Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
						}else if(res == 2){
							swal("Username is not available!.", "", "error");
						}else{
							swal("error on adding new account! Refresh the page and try again!.", "", "error");
						}
					});
				}
			});
			$(".btnBetCancel").click(function(){
				$("#modal_addStaffAccount").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="p-3 flex items-center justify-between">
					<h3 class="modal-title" style="font-weight:bold;">Add Staff Account</h3>
					<button type="button" class="text-2xl text-red-500 font-bold btnBetCancel">&times;</button>
				</div>
				<div class="modal-body">
					<div class="well">
						<div class="row -mt-3">
							<div class="flex flex-col justify-center gap-4 px-7">
								<div class="row" class="mx-4 my-4">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Username</p>
										<input type="text" class="form-control py-4" id="addStaffUsername" maxlength="32" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="Enter Username">
									</div>
								</div>
								<div class="row" class="mx-3 py-3">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Fullname</p>
										<input type="text" class="form-control py-4" id="addStaffSystemname" maxlength = "32" onKeyUp="caps(this);" placeholder="Enter Fullname">
									</div>
								</div>
								<div class="row" class="mx-3 py-3">
									<div class="col-md-12">
										<span  class="text-sm text-black font-bold mb-1">Handled Bet Type (Cashier for Bet Meron or Bet Wala)</span>
										<select  class="form-control" id = "addStaffBetType">
										<?php
											$qb = $mysqli->query("SELECT * FROM `tblbettypes`");
											if($qb->num_rows > 0){
												echo '<option value = "">Select Here...</option>';
												while($rb = $qb->fetch_assoc()){	
													echo '<option value = "'.$rb['id'].'">'.$rb["betType"].'</option>';
												}
											}else{	
											}
										?>
										</select>
									</div>
								</div>
								<div  class="-mt-5 ">
									<div class="col-md-12"><br/>
										<span class="text-red-500 text-sm font-bold">Teller Default Password is account username, please change account password upon login!</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mt-3 px-4 pb-5" style="text-align:center;">
					<button type="button" id = "smbtAddStaffAccount" class="text-base text-white bg-blue-500 w-full py-[12px] rounded-full " style="font-weight:bold; width:100%;">Save Staff Account Credentials</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_addTicketAccount" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#smbtAddTicketAccount").click(function(){
				usernameVal = $("#addAdminUsername").val();
				systemnameVal = $("#addAdminSystemname").val();
				if(usernameVal  == ""){
					$("#addAdminUsername").focus();
					swal("Username is required!", "", "error");
				}else if(systemnameVal == ""){
					$("#addAdminSystemname").focus();
					swal("Fullname is required!", "", "error");
				}else{
					$("#loader").show();
					showModal();
					$.post("admin/addTicketAccount.php", {username:usernameVal, systemname:systemnameVal}, function(res){
						$("#loader").hide();
						hideModal();
						if(res == 1){
							$("#modal_addTicketAccount").modal("hide");
							swal({
								title: "Added Account Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
						}else if(res == 2){
							swal("Username is not available!.", "", "error");
						}else{
							swal("error on adding new account! Refresh the page and try again!.", "", "error");
						}
					});
				}
			});
			$(".btnBetCancel").click(function(){
				$("#modal_addTicketAccount").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="p-3 flex items-center justify-between">
					<h3 class="modal-title" style="font-weight:bold;">Add Ticket Handler Account</h3>
					<button type="button" class="text-2xl text-red-500 font-bold btnBetCancel">&times;</button>
				</div>
				<div class="modal-body">
					<div class="well">
						<div class="row -mt-3">
							<div class="flex flex-col justify-center gap-4 px-7">
								<div class="row" class="mx-4 my-4">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Username</p>
										<input type="text" class="form-control py-4" id="addAdminUsername" maxlength="32" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="Enter Username">
									</div>
								</div>
								<div class="row" class="mx-3 py-3">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Fullname</p>
										<input type="text" class="form-control py-4" id="addAdminSystemname" maxlength = "32" onKeyUp="caps(this);" placeholder="Enter Fullname">
									</div>
								</div>
								
								<div  class="-mt-5 ">
									<div class="col-md-12"><br/>
										<span class="text-red-500 text-sm font-bold">Teller Default Password is account username, please change account password upon login!</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mt-3 px-4 pb-5" style="text-align:center;">
					<button type="button" id = "smbtAddTicketAccount" class="text-base text-white bg-blue-500 w-full py-[12px] rounded-full " style="font-weight:bold; width:100%;">Save Ticket Handler Credentials</button>
                </div>
			</div>		
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_addFightControllerAccount" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#smbtAddFightControllerAccount").click(function(){
				usernameVal = $("#addFightControllerUsername").val();
				systemnameVal = $("#addFightControllerFullname").val();
				if(usernameVal  == ""){
					$("#addFightControllerUsername").focus();
					swal("Username is required!", "", "error");
				}else if(systemnameVal == ""){
					$("#addFightControllerFullname").focus();
					swal("Fullname is required!", "", "error");
				}else{
					$("#loader").show();
					showModal();
					$.post("admin/addFightControllerAccount.php", {username:usernameVal, systemname:systemnameVal}, function(res){
						$("#loader").hide();
						hideModal();
						if(res == 1){
							$("#modal_addFightControllerAccount").modal("hide");
							swal({
								title: "Added Fight Controller Account Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
						}else if(res == 2){
							swal("Username is not available!.", "", "error");
						}else{
							swal("error on adding new account! Refresh the page and try again!.", "", "error");
						}
					});
				}
			});
			$(".btnBetCancel").click(function(){
				$("#modal_addFightControllerAccount").modal("hide");
			});
		});
	</script>


		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="p-3 flex items-center justify-between">
					<h3 class="modal-title" style="font-weight:bold;">Add Fight Controller Accountt</h3>
					<button type="button" class="text-2xl text-red-500 font-bold btnBetCancel">&times;</button>
				</div>
				<div class="modal-body">
					<div class="well">
						<div class="row -mt-3">
							<div class="flex flex-col justify-center gap-4 px-7">
								<div class="row" class="mx-4 my-4">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Username</p>
										<input type="text" class="form-control py-4" id="addFightControllerUsername" maxlength="32" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="Enter Username">
									</div>
								</div>
								<div class="row" class="mx-3 py-3">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Fullname</p>
										<input type="text" class="form-control py-4" id="addFightControllerFullname" maxlength = "32" onKeyUp="caps(this);" placeholder="Enter Fullname">
									</div>
								</div>
								
								<div  class="-mt-5 ">
									<div class="col-md-12"><br/>
										<span class="text-red-500 text-sm font-bold">Teller Default Password is account username, please change account password upon login!</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mt-3 px-4 pb-5" style="text-align:center;">
					<button type="button" id = "smbtAddFightControllerAccount" class="text-base text-white bg-blue-500 w-full py-[12px] rounded-full " style="font-weight:bold; width:100%;">Save Fight Controller Credentials</button>
                </div>
			</div>		
		</div>

	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_addCashoutHandler" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#smbtAddCashoutHandler").click(function(){
				chu = $("#addCashoutHandlerUsername").val();
				chf = $("#addCashoutHandlerFullname").val();
				if(chu  == ""){
					$("#addCashoutHandlerUsername").focus();
					swal("Username is required!", "", "error");
				}else if(chf == ""){
					$("#addCashoutHandlerFullname").focus();
					swal("Fullname is required!", "", "error");
				}else{
					$("#loader").show();
					showModal();
					$.post("admin/addCashoutHandlerAccount.php", {cashoutUsername:chu, cashoutFullname:chf}, function(res){
						$("#loader").hide();
						hideModal();
						if(res == 1){
							$("#modal_addCashoutHandler").modal("hide");
							swal({
								title: "Added Cash Handler Account Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
						}else if(res == 2){
							swal("Username is not available!.", "", "error");
						}else{
							swal("error on adding new account! Refresh the page and try again!.", "", "error");
						}
					});
				}
			});
			$(".btnCashoutCancel").click(function(){
				$("#modal_addCashoutHandler").modal("hide");
			});
		});
	</script>


		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="p-3 flex items-center justify-between">
					<h3 class="modal-title" style="font-weight:bold;">Add Cash Handler Account</h3>
					<button type="button" class="text-2xl text-red-500 font-bold btnCashoutCancel">&times;</button>
				</div>
				<div class="modal-body">
					<div class="well">
						<div class="row -mt-3">
							<div class="flex flex-col justify-center gap-4 px-7">
								<div class="row" class="mx-4 my-4">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Username</p>
										<input type="text" class="form-control py-4" id="addCashoutHandlerUsername" maxlength="32" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="Enter Username">
									</div>
								</div>
								<div class="row" class="mx-3 py-3">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Fullname</p>
										<input type="text" class="form-control py-4" id="addCashoutHandlerFullname" maxlength = "32" onKeyUp="caps(this);" placeholder="Enter Fullname">
									</div>
								</div>
								
								<div  class="-mt-5 ">
									<div class="col-md-12"><br/>
										<span class="text-red-500 text-sm font-bold">Teller Default Password is account username, please change account password upon login!</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mt-3 px-4 pb-5" style="text-align:center;">
					<button type="button" id = "smbtAddCashoutHandler" class="text-base text-white bg-blue-500 w-full py-[12px] rounded-full " style="font-weight:bold; width:100%;">Save Cash Handler Credentials</button>
                </div>
			</div>		
		</div>

	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_addReportSupervisorAccount" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#smbtAddReportSupervisorAccount").click(function(){
				usernameVal = $("#addReportSupervisorUsername").val();
				systemnameVal = $("#addReportSupervisorFullname").val();
				if(usernameVal  == ""){
					$("#addReportSupervisorUsername").focus();
					swal("Username is required!", "", "error");
				}else if(systemnameVal == ""){
					$("#addReportSupervisorFullname").focus();
					swal("Fullname is required!", "", "error");
				}else{
					$("#loader").show();
					showModal();
					$.post("admin/addReportSupervisorAccount.php", {username:usernameVal, systemname:systemnameVal}, function(res){
						$("#loader").hide();
						hideModal();
						if(res == 1){
							$("#modal_addReportSupervisorAccount").modal("hide");
							swal({
								title: "Added Report Supervisor Account Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
						}else if(res == 2){
							swal("Username is not available!.", "", "error");
						}else{
							swal("error on adding new account! Refresh the page and try again!.", "", "error");
						}
					});
				}
			});
			$(".btnBetCancel").click(function(){
				$("#modal_addReportSupervisorAccount").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="p-3 flex items-center justify-between">
					<h3 class="modal-title" style="font-weight:bold;">Add Report Supervisor Account</h3>
					<button type="button" class="text-2xl text-red-500 font-bold btnBetCancel">&times;</button>
				</div>
				<div class="modal-body">
					<div class="well">
						<div class="row -mt-3">
							<div class="flex flex-col justify-center gap-4 px-7">
								<div class="row" class="mx-4 my-4">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Username</p>
										<input type="text" class="form-control py-4" id="addReportSupervisorUsername" maxlength="32" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" placeholder="Enter Username">
									</div>
								</div>
								<div class="row" class="mx-3 py-3">
									<div class="col-md-12">
										<p class="text-sm text-black font-bold mb-1">Fullname</p>
										<input type="text" class="form-control py-4" id="addReportSupervisorFullname" maxlength = "32" onKeyUp="caps(this);" placeholder="Enter Fullname">
									</div>
								</div>
								
								<div  class="-mt-5 ">
									<div class="col-md-12"><br/>
										<span class="text-red-500 text-sm font-bold">Teller Default Password is account username, please change account password upon login!</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mt-3 px-4 pb-5" style="text-align:center;">
					<button type="button" id = "smbtAddReportSupervisorAccount" class="text-base text-white bg-blue-500 w-full py-[12px] rounded-full " style="font-weight:bold; width:100%;">Save Report Supervisor Credentials</button>
                </div>
			</div>		
		</div>


	</div>
</div>


<div class="container" >
	<div class="modal fade" id="modal_addEvent" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnSaveEvent").click(function(){
				//esVal = $('input[name="optEventStatus"]:checked').val();
				usVal = $('input[name="optUserAccess"]:checked').val();
				$.post("admin/addEvent.php", {userStatus:usVal}, function(res){
					if(res == 1){
						
						swal({
							title: "Added Event Successfully!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});
					}else if(res == 2){
						swal("You must close the existing event before opening a new one!","","error");
					}else{
						swal("unable to add new event! Refresh the page and try again!","","error");
					}
				});
			});
			$(".btnBetCancel").click(function(){
				$("#modal_addEvent").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="p-3 flex items-center justify-between">
					<h3 class="modal-title" style="font-weight:bold;">Add Event</h3>
					<button type="button" class="text-2xl text-red-500 font-bold btnBetCancel">&times;</button>
				</div>
				<form class="user">
				<div class="modal-body">
					<div class="well">
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="row" style="margin:5px;">
									<div class="col-md-12 -mt-4">
										<span class="text-base text-black font-bold">User/Client Access to System</span>
											<div class="container mt-3 text-base font-semibold ">
												<label class="radio-inline">
												<input type="radio" name="optUserAccess" checked> OPEN
												</label><br/>
												<label class="radio-inline">
													<input type="radio" name="optUserAccess"> CLOSE
												</label>
											</div>
										
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<div class="mt-2 mb-4 mx-4" style="text-align:center;">
					<button type="button" id = "btnSaveEvent" class="text-base text-white font-semibold py-[13px] w-full rounded-full bg-blue-500" style="font-weight:bold; width:100%;">CREATE EVENT</button>
                </div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="modal fade" id="modal_placeBet" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnBetMeron").click(function(){
				betType = $("#btnBetMeron").val();
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				pnts = $("#hiddenPoints").val();
				if(betAmount < 100){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Minimum bet amount is 100 Points!","","error");
				}else if(betAmount == ""){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else if(betAmount1 > pnts){
					$("#txtBetAmount").focus();
					swal("The Bet Amount exceeded your current points.!","","error");
				}else{
					$("#txtBetTypeText").html(betType);
					$("#modal_isBet").modal("show");
					$("#betConfirmAmount").html(betAmount);
				}
			});
			
			$("#btnBetWala").click(function(){
				betType = $("#btnBetWala").val();
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				pnts = $("#hiddenPoints").val();
				if(betAmount < 100){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Minimum bet amount is 100 Points!","","error");
				}else if(betAmount == ""){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else if(betAmount1 > pnts){
					$("#txtBetAmount").focus();
					swal("The Bet Amount exceeded your current points.!","","error");
				}else{
					$("#txtBetTypeText").html(betType);
					$("#modal_isBet").modal("show");
					$("#betConfirmAmount").html(betAmount);
				}
				
			});	
			$(".btnBetCancel").click(function(){
				$("#txtBetAmount").val("");
				$("#modal_placeBet").modal("hide");
			});
			
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h6 class="modal-title" style="font-weight:bold;">PLACE A BET</h6>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<input id="txtBetAmount" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:20px; letter-spacing:2px;" value = "" placeholder = "ENTER AMOUNT HERE" AUTOCOMPLETE = "OFF">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type = "button" class="btn btn-lg btn-raised btn-primary" id = "btnBetMeron" value = "MERON" >BET MERON</button>
					<button type="button" class="btn btn-lg btn-success" id = "btnBetWala" value = "WALA">BET WALA</button>
					<button type="button" class="btn btn-lg btn-danger btnBetCancel" value = "WALA">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="modal fade" id="modal_isBet" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnIsBetYes").click(function(){
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				betType = $("#txtBetTypeText").html();

				$.post("bets/accountSaveBets.php", {amount:betAmount1, bettingType:betType}, function(res){
					$("#txtBetAmount").val("");
					$("#modal_placeBet").modal("hide");
					if(res == 1){
						$("#modal_isBet").modal("hide");
						swal({
							html: true,
							title: "Bets Successfully Placed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 0){		
						swal("Unable to place a bet for the  current Fight. Refresh the page and try again.", "", "error");		
					}else if(res == 2){	
						swal("Unable to place your bet due to Betting Status is already CLOSED. Refresh the page and try again.", "", "error");	
					}else if(res == 3){	
						swal("Unable to place your bet due to Betting Status is already DONE. Refresh the page and try again.", "", "error");	
					}else if(res == 5){	
						swal("Unable to place your bet due to FIGHT CANCELLATION. Refresh the page and try again.", "", "error");	
					}
				});
				
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h6 class="modal-title" style="font-weight:bold;">BET AMOUNT CONFIRMATION</h5>
				</div>
				<div class="modal-body">
					
					<div class="well" style="text-align:center;">
						<h3 style="font-weight:bold;"><span>BET UNDER: </span><span id = "txtBetTypeText" style="color:red;"></span></h3>
						<h3 style="font-weight:bold;">BET AMOUNT: &nbsp;P <span id ="betConfirmAmount" style="color:red;"></span></h3>
					</div>
					
					
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type = "button" class="btn btn-lg btn-raised btn-primary" id = "btnIsBetYes">CONFIRM</button>
					<button type="button" class="btn btn-lg btn-danger" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<!-- ADMIN MODALs -->

<div class="container" >
	<div class="modal fade" id="modal_confirmLastCall" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmLastCall").click(function(){
				$("#modal_confirmLastCall").modal("hide");
				id = 1;
				$.post("admin/saveLastCall.php", {lastcall:id}, function(res){
					if(res == 1){
						swal({
							html: true,
							title: "Last Call for Betting is Applied!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if (res == 2){
						swal({
							html: true,
							title: "Unable to change fight status to LAST CALL! System will refresh your page to update available FIGHT STATUS OPTIONS!",
							text: "",
							type: "error",
							confirmButtonClass: "btn-danger",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else{
						swal("an error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content  w-full py-4 px-3">
				<div>	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div>	
					<div class="mt-4" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">CALL FOR LAST BETTING?</h3>
					</div>
				</div>
				<div class="flex justify-center items-center gap-3 mt-12 mb-2" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold bg-blue-500 rounded-full px-8 py-2" id = "btnConfirmLastCall">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold bg-red-500 rounded-full px-9 py-2" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_confirmCancelBets" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmCancelBets").click(function(){
				$("#modal_confirmCancelBets").modal("hide");
				id = 1;
				txtpassword = $("#txtConfirmPassword").val();
				$.post("admin/saveCancelBets.php", {cancelBets:id, userPassword:txtpassword}, function(res){
					if(res == 2){
						swal({
							html: true,
							title: "Current Fight is now Cancelled! No Bets has been placed for this fight and no points to be returned.",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});	
					}else if(res == 1){
						swal({
							html: true,
							title: "Current Fight is now Cancelled! Bet points returned Successfully!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 3){
						swal("Unable to cancel the fight due to incorrect user account password", "", "error");	
					}else{
						swal("Error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content w-full py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="mt-4">	
					<div class="well" style="text-align:center;">
						<h3 class="text-xl font-bold text-black">To Confirm the Cancellation of Fight</h3>
						<span class="text-sm text-black tracking-wide">you must enter your password for security purposes.</span>
						<input type = "password" id="txtConfirmPassword" class="form-control text-sm py-4 mt-4 rounded-lg max-w-[400px] w-full mx-auto" placeholder = "PASSWORD HERE..."/>
					</div>
				</div>
				<div class="mt-9" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold bg-blue-500 rounded-full px-8 py-2 " id = "btnConfirmCancelBets">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold bg-red-500 rounded-full px-8 py-2" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_confirmClose" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmClose").click(function(){
				id = 1;
				$.post("admin/saveClose.php", {bettingClose:id}, function(res){
					$("#modal_confirmClose").modal("hide");
					if(res == 1){
						swal({
							html: true,
							title: "The betting has been closed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 3){
						swal({
							html: true,
							title: "System will refresh your page to update available FIGHT STATUS OPTIONS!",
							text: "",
							type: "error",
							confirmButtonClass: "btn-danger",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});
					}else{
						swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="">	
					<div class="mt-4" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">Close the fight betting?</h3>
					</div>
				</div>
				<div class="mt-12" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold bg-blue-500 px-8 py-2 rounded-full" id = "btnConfirmClose">CLOSE</button>
					<button type="button" class="text-base text-white font-semibold bg-red-500 px-8 py-2 rounded-full" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="modal fade" id="modal_declareWinner" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnWinnerMeron").click(function(){
				id = $(this).val();
				swal({
					title: "FIGHT RESULT: WINNER BETS FOR MERON!",
					text: "Are you sure?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				 },
				 function(isConfirm){

				   if (isConfirm){
					 $.post("admin/saveWinner.php", {winnerID:id}, function(res){
						$("#modal_declareWinner").modal("hide");
						if(res == 1){
							swal({
								html: true,
								title: "The betting winner has been declared!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});		
						}else{
							swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
						}
					});

					} else {
					swal("Cancelled", "Declaration of fight winner has been cancelled!", "error");
					}
				 });
				
			});
			
			$("#btnWinnerWala").click(function(){
				id = $(this).val();
				swal({
					title: "FIGHT RESULT: WINNER BETS FOR WALA!",
					text: "Are you sure?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				},
				function(isConfirm){

					if (isConfirm){
						$.post("admin/saveWinner.php", {winnerID:id}, function(res){
							$("#modal_declareWinner").modal("hide");
							if(res == 1){
								swal({
									html: true,
									title: "The betting winner has been declared!",
									text: "",
									type: "success",
									confirmButtonClass: "btn-success",
									confirmButtonText: "OK",
									closeOnConfirm: true
								},
								function(){
									location.reload();
								});		
							}else{
								swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
							}
						});

					} else {
						swal("Cancelled", "Declaration of fight winner has been cancelled!", "error");
					}	
				});
				
			});	
			$("#btnWinnerDraw").click(function(){
				id = $(this).val();
				swal({
					title: "FIGHT RESULT: DRAW!",
					text: "Are you sure?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, I am sure!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				 },
				 function(isConfirm){

				   if (isConfirm){
					 $.post("admin/saveWinner.php", {winnerID:id}, function(res){
						$("#modal_declareWinner").modal("hide");
						if(res == 2){
							swal({
								html: true,
								title: "The betting winner has been declared! DRAW! No Bets has been placed for this fight and no points to be returned.",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});	
						}else if(res == 1){
							swal({
								html: true,
								title: "The betting winner has been declared! DRAW!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});		
						}else{
							swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
						}
					});

					} else {
						swal("Cancelled", "Declaration of fight winner has been cancelled!", "error");
					}
				 });
				
			});
			
			$(".btnCloseCancel").click(function(){
				$("#modal_declareWinner").modal("hide");
			});
			
		});
	</script>
		<div class="modal-dialog ">
			<div class="modal-content py-3 px-3">
				<div class="lw-full flex items-center justify-between mb-3">
					<h6 class="modal-title text-base">WINNER DECLARATION</h6>
					<button type="button" class="text-3xl font-bold text-red-500 btnCloseCancel ">&times;</button>
				</div>
				<div class="">
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">CLICK THE FIGHT WINNER</h3>
					</div>
				</div>
				<div class="mt-9 mb-4 flex items-center justify-center gap-3" style="text-align:center;">
					<button type = "button" class="text-sm text-white font-semibold py-2 px-7 rounded-full bg-red-500" id = "btnWinnerMeron"  value = "1" >WINNER MERON</button>
					<button type="button" class="text-sm text-white font-semibold py-2 px-7 rounded-full bg-yellow-500 btn-warning" id = "btnWinnerDraw" value = "3">DRAW</button>
					<button type="button" class="text-sm text-white font-semibold py-2 px-7 rounded-full bg-blue-500" id = "btnWinnerWala"  value = "2">WINNER WALA</button>
                </div>
			</div>
		</div>
	</div>
</div>


<div class="container" >
	<div class="modal fade" id="modal_confirmReleasePayout" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmReleasePayout").click(function(){
				id = 1;
				showModal();
				$("#loader").show();
				$.post("admin/saveReleasePayout.php", {releasePayout:id}, function(res){
					$("#modal_confirmReleasePayout").modal("hide");
					hideModal();
					$("#loader").hide();
					if(res == 1){
						
						swal({
							html: true,
							title: "Current Fight Winner Payout has been made!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 2){
						swal({
							html: true,
							title: "Payout for tickets has been released!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 5){
						swal("An error occured! Please refresh the page and try again!", "", "error");	
					}else{
						swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog ">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="modal-body">	
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">Confirm Payout?</h3>
					</div>
				</div>
				<div class="mt-9 flex items-center justify-center gap-2" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmReleasePayout">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>



<div class="container">
	<div class="modal fade" id="modal_confirmNew" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmNew").click(function(){
				id = 1;
				$.post("admin/saveNew.php", {startNew:id}, function(res){
					$("#modal_confirmNew").modal("hide");
					if(res == 1){
						
						swal({
							html: true,
							title: "New Fight Betting Started Successfully!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 2){
						swal("Unable to start a new fight due to existing fight. Please refresh the page to update fight status!.", "", "error");		
					}else if(res == 4){
						swal("Unable to start new fight, event already closed.", "", "error");
					}else{
						swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="mt-2">	
					<div class="text-2xl font-bold text-black" style="text-align:center;">
						<h3 style="font-weight:bold;">Start a new fight?</h3>
					</div>
				</div>
				<div class="mt-9 flex items-center justify-center gap-2" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmNew">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>



<div class="container">
	<div class="modal fade" id="modal_adminAddBalance" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmAddBalance").click(function(){
				mobileNumberVal = $("#hiddenMobileNumber").val();
				accountIDVal = $("#hiddenAccountID").val();			
				pointsVal = $("#txtAddBalance").val();
				pointsVal1 = parseFloat(pointsVal.replace(/,/g,""));
				if(pointsVal1 < 100){
					$("#txtAddBalance").focus();
					swal("Please Input Amount to Load! Minimum amount is 100 Points!","","error");
				}else if(pointsVal == ""){
					$("#txtAddBalance").focus();
					swal("Please Input Points to Load!","","error");
				}else{
					$.post("admin/saveAddBalance.php", {mobileNumber:mobileNumberVal, accountID:accountIDVal, points:pointsVal1}, function(res){
						$("#modal_adminAddBalance").modal("hide");
						if(res == 1){
							swal({
								html: true,
								title: "Added Points Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});		
						}else{
							swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
						}
					});
				}
				
			});	
			$("#btnCancelAddBalance").click(function(){
				$("#modal_adminAddBalance").modal("hide");
			});
			
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h6 class="modal-title" style="font-weight:bold;">ADD BALANCE</h6>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<h6>CURRENT POINTS: <span id = "spanAddBalance" style="font-weight:bold;"></span> </h6>
										<h6>NAME: <span id = "spanAddLname" style="font-weight:bold;"></span>, <span id = "spanAddFname"  style="font-weight:bold;"></span> </h6>
										<h6>MOBILE NUMBER: <span id = "spanAddMobileNumber"  style="font-weight:bold;"></span></h6>
										<input id="txtAddBalance" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:20px; letter-spacing:2px;" value = "" placeholder = "ENTER AMOUNT TO ADD" AUTOCOMPLETE = "OFF">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type = "button" class="btn btn-md btn-raised btn-primary" id = "btnConfirmAddBalance">ADD BALANCE</button>
					<button type="button" class="btn btn-md btn-danger" id = "btnCancelAddBalance" value = "WALA">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="modal fade" id="modal_adminMinusBalance" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmMinusBalance").click(function(){
				mobileNumberVal = $("#hiddenMobileNumber").val();
				accountIDVal = $("#hiddenAccountID").val();			
				pointsVal = $("#txtMinusBalance").val();
				pointsVal1 = parseFloat(pointsVal.replace(/,/g,""));
				if(pointsVal1 < 100){
					$("#txtMinusBalance").focus();
					swal("Please Input Amount to Withdraw! Minimum amount is 100 Points!","","error");
				}else if(pointsVal == ""){
					$("#txtMinusBalance").focus();
					swal("Please Input Points to Withdraw!","","error");
				}else{
					$.post("admin/saveMinusBalance.php", {mobileNumber:mobileNumberVal, accountID:accountIDVal, points:pointsVal1}, function(res){
						$("#modal_adminMinusBalance").modal("hide");
						if(res == 1){
							swal({
								html: true,
								title: "Withdraw Points Successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});		
						}else if(res == 2){
							$("#txtMinusBalance").val("");
							swal("Error! Points to withdraw is greater than your current balance.", "", "error");	
						}else{
							$("#txtMinusBalance").val("");
							swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
						}
					});
					
				}
				
			});	
			$("#btnCancelMinusBalance").click(function(){
				$("#modal_adminMinusBalance").modal("hide");
			});
			
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h6 class="modal-title" style="font-weight:bold;">WITHDRAW BALANCE</h6>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<h6>CURRENT POINTS: <span id = "spanMinusBalance" style="font-weight:bold;"></span> </h6>
										<h6>NAME: <span id = "spanMinusLname" style="font-weight:bold;"></span>, <span id = "spanMinusFname"  style="font-weight:bold;"></span> </h6>
										<h6>MOBILE NUMBER: <span id = "spanMinusMobileNumber"  style="font-weight:bold;"></span></h6>
										<input id="txtMinusBalance" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:20px; letter-spacing:2px;" value = "" placeholder = "ENTER AMOUNT TO WITHDRAW" AUTOCOMPLETE = "OFF">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type = "button" class="btn btn-md btn-raised btn-primary" id = "btnConfirmMinusBalance">WITHDRAW BALANCE</button>
					<button type="button" class="btn btn-md btn-danger" id = "btnCancelMinusBalance" value = "WALA">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_confirmCloseEvent" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmCloseEvent").click(function(){
				id = 1;
				$.post("admin/saveCloseEvent.php", {closeEventID:id}, function(res){
					$("#modal_confirmCloseEvent").modal("hide");
					if(res == 1){
						swal({
							html: true,
							title: "The event has been closed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 2){
						swal("You must first closed/finished the current fight betting before closing an event!.", "", "error");	
					}else{
						swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="mt-3">	
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl text-black font-bold">Close this event?</h3>
					</div>
				</div>
				<div class="mt-9 mb-3 flex items-center justify-center gap-2" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmCloseEvent">CLOSE</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_confirmCloseSystem" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmCloseSystem").click(function(){
				idVal = $("#hiddenEventID").val();
				$.post("admin/saveCloseSystem.php", {eventID:idVal}, function(res){
					$("#modal_confirmCloseSystem").modal("hide");
					if(res == 1){
						swal({
							html: true,
							title: "The system has been closed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if(res == 2){
						swal("You must first closed/finished the current fight betting before closing an access to the system!.", "", "error");	
					}else{
						swal("An error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="modal-body">	
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">Close the system for client/user access?</h3>
					</div>
				</div>
				<div class="mt-9 flex items-center justify-center gap-3" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmCloseSystem">CLOSE</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_addBanner" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#closeBanner").click(function(){
				$("#modal_addBanner").modal("hide");
			});
			
			$("#btnSaveBanner").click(function(){
				sBanner = $("#txtBanner").val();
				if(sBanner == ""){
					swal("Event Name must not be blank!","","error");
				}else if(sBanner.length < 3){
					swal("Check the number of characters of your Event Name!","","error");
				}else{
					showModal();
					$("#loader").show();
					$.post("banners/saveBanner.php", {banner:sBanner}, function(res){
						hideModal();
						$("#loader").hide();
						$("#modal_addBanner").modal("hide");
						if(res == 1){
							swal({
								title: "Event Name added successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
									location.reload();
							});
						}else if(res == 2){
							swal("Error! Event Name already exist.","","error");
						}else if(res == 3){
							swal("Error! Check the number of characters of your Event Name.","","error");
						}else{
							swal("Error! Unable to save Event Name. Please refresh the page and try again.","","error");
						}
					});
				}
			});
			
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="flex items-center justify-between p-3">
					<h4 class="modal-title text-lg text-black font-bold">Add Event Name</h4>
					<button type="button" class="text-3xl text-red-500 font-bold" id = "closeBanner">&times;</button>
				</div>
				<div id = "modal_bodyBanner ">
				
					<div class="widget-body px-5 py-3">
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control py-4" id="txtBanner" oninput="this.value = this.value.replace(/[^a-zA-Z-'0-9 ]/g, '');" maxlength= "70" placeholder="Event Name Here" AUTOFOCUS>
							</div>
							<button id="btnSaveBanner" class="text-base text-white font-semibold w-full py-[12px] rounded-full bg-blue-500 mt-3" style="width:100%;">Save Event Name</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_addPromoter" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#closePromoter").click(function(){
				$("#modal_addPromoter").modal("hide");
			});
			
			$("#btnSavePromoter").click(function(){
				sPromoter = $("#txtPromoter").val();
				if(sPromoter == ""){
					swal("Event Promoter must not be blank!","","error");
				}else if(sPromoter.length < 3){
					swal("Check the number of characters of your Event Promoter!","","error");
				}else{
					showModal();
					$("#loader").show();
					
					$.post("promoters/savePromoter.php", {promoter:sPromoter}, function(res){
						hideModal();
						$("#loader").hide();
						$("#modal_addPromoter").modal("hide");
						if(res == 1){
							swal({
								title: "Event Promoter added successfully!",
								text: "",
								type: "success",
								confirmButtonClass: "btn-success",
								confirmButtonText: "OK",
								closeOnConfirm: true
							},
							function(){
								location.reload();
							});
						}else if(res == 2){
							swal("Error! Event Promoter already exist.","","error");
						}else if(res == 3){
							swal("Error! Check the number of characters of your Event Promoter.","","error");
						}else{
							swal("Error! Unable to save Event Promoter. Please refresh the page and try again.","","error");
						}
					});
				}
			});
			
		});
	</script>

<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="flex items-center justify-between p-3">
					<h4 class="modal-title text-lg text-black font-bold">Add Event Promote</h4>
					<button type="button" class="text-3xl text-red-500 font-bold" id = "closePromoter">&times;</button>
				</div>
				<div id = "modal_body " id="modal_bodyPromoter">
				
					<div class="widget-body px-5 py-3">
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control py-4" id="txtPromoter" oninput="this.value = this.value.replace(/[^a-zA-Z-'0-9 ]/g, '');" maxlength= "70" placeholder="Event Name Here" AUTOFOCUS>
							</div>
							<button id="btnSavePromoter" class="text-base text-white font-semibold w-full py-[12px] rounded-full bg-blue-500 mt-3" style="width:100%;">Save Event Promoter</button>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- <div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title pull-left">Add Event Promoter</h4>
					<button type="button" class="btn-danger pull-right" id = "closePromoter">&times;</button>
				</div>
				<div class="modal-body" id = "modal_bodyPromoter">
				
					<div class="widget-body">
						<div class="form-group">
							<div class="col-sm-12" style="padding:5px;">
								<div class="input-group">
									<input type="text" class="form-control" id="txtPromoter" oninput="this.value = this.value.replace(/[^a-zA-Z-'0-9 ]/g, '');" maxlength= "70" placeholder="Event Promoter Here" AUTOFOCUS>
								</div>
							</div>
							<button id="btnSavePromoter" class="btn btn-success btn-lg text-uppercase rounded-sm shadow-l mb-3 mt-4 font-900" style="width:100%;">Save Event Promoter</button>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_confirmCloseWala" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmCloseWala").click(function(){
				$("#modal_confirmCloseWala").modal("hide");
				id = 1;
				$.post("admin/saveCloseWala.php", {closeWala:id}, function(res){
					if(res == 1){
						swal({
							html: true,
							title: "The Bettings for WALA is temporarily Closed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if (res == 2){
						swal({
							html: true,
							title: "Unable to CLose the Bettings for WALA! System will refresh your page to update available FIGHT BETTINGS OPTIONS!",
							text: "",
							type: "error",
							confirmButtonClass: "btn-danger",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else{
						swal("an error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="mt-3">	
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">CLOSE THE BETTINGS FOR<br/>WALA?</h3>
					</div>
				</div>
				<div class="mt-9 flex items-center justify-center gap-2" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmCloseWala">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>


<div class="container" >
	<div class="modal fade" id="modal_confirmCloseMeron" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmCloseMeron").click(function(){
				$("#modal_confirmCloseMeron").modal("hide");
				id = 1;
				$.post("admin/saveCloseMeron.php", {closeMeron:id}, function(res){
					if(res == 1){
						swal({
							html: true,
							title: "The Bettings for MERON is temporarily Closed!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if (res == 2){
						swal({
							html: true,
							title: "Unable to Close the Bettings for MERON! System will refresh your page to update available FIGHT BETTINGS OPTIONS!",
							text: "",
							type: "error",
							confirmButtonClass: "btn-danger",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else{
						swal("an error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="momt-3">	
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">CLOSE THE BETTINGS FOR<br/>MERON?</h3>
					</div>
				</div>
				<div class="mt-9 flex items-center justify-center gap-2" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmCloseMeron">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>


<div class="container" >
	<div class="modal fade" id="modal_confirmOpenWala" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmOpenWala").click(function(){
				$("#modal_confirmOpenWala").modal("hide");
				id = 1;
				$.post("admin/saveOpenWala.php", {openWala:id}, function(res){
					if(res == 1){
						swal({
							html: true,
							title: "The Bettings for WALA is now Open!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if (res == 2){
						swal({
							html: true,
							title: "Unable to Open the Bettings for WALA! System will refresh your page to update available FIGHT BETTINGS OPTIONS!",
							text: "",
							type: "error",
							confirmButtonClass: "btn-danger",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else{
						swal("an error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="mt-3">	
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">OPEN THE BETTINGS FOR<br/>WALA?</h3>
					</div>
				</div>
				<div class="mt-9 flex items-center justify-center gap-2" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmOpenWala">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>


<div class="container" >
	<div class="modal fade" id="modal_confirmOpenMeron" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnConfirmOpenMeron").click(function(){
				$("#modal_confirmOpenMeron").modal("hide");
				id = 1;
				$.post("admin/saveOpenMeron.php", {openMeron:id}, function(res){
					if(res == 1){
						swal({
							html: true,
							title: "The Bettings for MERON is now Open!",
							text: "",
							type: "success",
							confirmButtonClass: "btn-success",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else if (res == 2){
						swal({
							html: true,
							title: "Unable to Open the Bettings for MERON! System will refresh your page to update available FIGHT BETTINGS OPTIONS!",
							text: "",
							type: "error",
							confirmButtonClass: "btn-danger",
							confirmButtonText: "OK",
							closeOnConfirm: true
						},
						function(){
							location.reload();
						});		
					}else{
						swal("an error occured! Refresh the page and try again or system developer assistance is required!.", "", "error");	
					}
				});
			});
		});
	</script>
		<div class="modal-dialog ">
			<div class="modal-content py-4 px-3">
				<div class="">	
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="mt-3">	
					<div class="well" style="text-align:center;">
						<h3 class="text-2xl font-bold text-black">OPEN THE BETTINGS FOR<br/>MERON?</h3>
					</div>
				</div>
				<div class="mt-9 flex items-center justify-center gap-3" style="text-align:center;">
					<button type = "button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-blue-500" id = "btnConfirmOpenMeron">CONFIRM</button>
					<button type="button" class="text-base text-white font-semibold px-8 py-2 rounded-full bg-red-500" data-dismiss="modal">CANCEL</button>
                </div>
			</div>
		</div>
	</div>
</div>
