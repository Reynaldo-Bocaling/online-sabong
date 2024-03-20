<script>
	$(document).ready(function(){
		$("#changePassword").click(function(){
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
			$("aside ul #changePassword").children("a").removeClass("active");
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
								$("aside ul #changePassword").children("a").removeClass("active");
								
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
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title" style="font-weight:bold;">Change Password</h4><button type="button" class="btn btn-md btn-danger" id="closeModalPassword" data-dismiss="modal" >X</button>
				</div>
				<div class="modal-body">

					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
										<span style='font-weight:bolder; font-size:15px;'>Old Password:</span>
										<input type="password" class="form-control" id="passwordOld" maxlength = "32" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)"  placeholder="Your Old Password Here..."/>
									</div>
								</div>
							</div>
							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
										<span style='font-weight:bolder; font-size:15px;'>New Password:</span>
										<input type="password" class="form-control" id="passwordNew" maxlength = "32" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" placeholder="New Password Here..."/>
									</div>
								</div>
							</div>
							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
										<span style='font-weight:bolder; font-size:15px;'>Confirm New Password:</span>
										<input type="password" class="form-control" id="passwordConfirm" maxlength = "32" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" placeholder="Confirm New Password Here..."/>
									</div>
								</div>
							</div>
							<div class="row" style="margin:1px; text-align:center;">
								<div class="col-md-12">
									<input type="button" id = "smbtPassword" class="btn btn-raised btn-success" value = "Change Password">
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

<div class="container">
	<div class="modal fade" id="modal_placeBet" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnBetMeron").click(function(){
				betType = $("#btnBetMeron").val();
				betAmount = $("#txtBetAmount").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				pnts = $("#hiddenPoints").val();
				if(betAmount1 < 100){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Minimum bet amount is 100 Points!","","error");
				}else if(betAmount1 > 100000){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Maximum bet amount is 100,000!","","error");
				}else if(betAmount == ""){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else if(betAmount1 > pnts){
					$("#txtBetAmount").focus();
					swal("The Bet Amount exceeded your current points.!","","error");
				}else{
					//$("#btnBetMeron").attr("disabled","true");
					//$("#btnBetWala").attr("disabled","true");
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
				if(betAmount1 < 100){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Minimum bet amount is 100 Points!","","error");
				}else if(betAmount1 > 100000){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount! Maximum bet amount is 100,000!","","error");
				}else if(betAmount == ""){
					$("#txtBetAmount").focus();
					swal("Please Input Bet Amount!","","error");
				}else if(betAmount1 > pnts){
					$("#txtBetAmount").focus();
					swal("The Bet Amount exceeded your current points.!","","error");
				}else{
					//$("#btnBetMeron").attr("disabled","true");
					//$("#btnBetWala").attr("disabled","true");
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
					<h3 class="modal-title" style="font-weight:bold;">PLACE A BET</h3><button type="button" class="btn btn-md btn-danger btnBetCancel">X</button>
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
					<button type = "button" class="btn btn-lg" id = "btnBetMeron" value = "MERON" style="width:100%; background-color: #f34141; color:#FFF; font-weight:bold;">BET MERON</button>
					<button type="button" class="btn btn-lg" id = "btnBetWala" value = "WALA" style="width:100%; background-color:#4e73df; color:#FFF; font-weight:bold;">BET WALA</button>
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
				showModal();
				$("#loader").show();
				$.post("bets/accountSaveBets.php", {amount:betAmount1, bettingType:betType}, function(res){
					hideModal();
					$("#loader").hide();
					$("#txtBetAmount").val("");
					$("#modal_placeBet").modal("hide");
					$("#modal_isBet").modal("hide");
					
					if(res == 1){
						
						
					//	$("#btnBetMeron").attr("disabled","false");
						//$("#btnBetWala").attr("disabled","false");
						swal({
							html: true,
							title: "BET ACCEPTED!",
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
						$("#txtBetAmount").focus();		
						swal("ERROR! Unable to place a bet for the current Fight. Refresh the page and try again.", "", "error");		
					}else if(res == 2){	
						$("#txtBetAmount").focus();		
						swal("ERROR! Unable to place your bet due to Betting Status is already CLOSED. Refresh the page and try again.", "", "error");	
					}else if(res == 3){
						$("#txtBetAmount").focus();						
						swal("ERROR! Unable to place your bet due to Betting Status is already DONE. Refresh the page and try again.", "", "error");	
					}else if(res == 5){	
						$("#txtBetAmount").focus();
						swal("ERROR! Unable to place your bet due to FIGHT CANCELLATION. Refresh the page and try again.", "", "error");	
					}else if(res == 6){
						$("#txtBetAmount").focus();
						swal("ERROR! Unable to place your bet due to insufficient points. Refresh the page and try again.", "", "error");
					}else if(res == 7){
						$("#txtBetAmount").focus();
						swal("ERROR! Placing of bets with same bet amount at the same fight is not allowed! .", "", "error");
					}else if(res == 11){
						$("#txtBetAmount").focus();
						swal("ERROR! Fight Bettings for MERON is temporarily closed.", "", "error");
					}else if(res == 12){
						$("#txtBetAmount").focus();
						swal("ERROR! Fight Bettings for WALA is temporarily closed.", "", "error");
					}
					
				});
				
			});
			$(".btnIsBetCancel").click(function(){
				$("#modal_isBet").modal("hide");
				$("#btnBetMeron").removeAttr("disabled");
				$("#btnBetWala").removeAttr("disabled");
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h3 class="modal-title" style="font-weight:bold;">CONFIRM BET</h3><button type="button" class="btn btn-md btn-danger btnIsBetCancel">X</button>
				</div>
				<div class="modal-body">
					
					<div class="well" style="text-align:center;">
						<h3 style="font-weight:bold;"><span>BET UNDER:</span><br/><span id = "txtBetTypeText" style="color:red;"></span></h3>
						<h3 style="font-weight:bold;">BET AMOUNT:<br/><span id ="betConfirmAmount" style="color:red;"></span></h3>
					</div>
					
					
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type = "button" class="btn btn-lg btn-raised btn-primary" id = "btnIsBetYes" style="width:100%; font-weight:bold;">BET NOW</button>
                </div>
			</div>
		</div>
	</div>
</div>


<div class="container">
	<div class="modal fade" id="modal_accountAddBalance" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			input = document.getElementById("txtAddBalance");

			// Execute a function when the user releases a key on the keyboard
			input.addEventListener("keyup", function(event) {
			  // Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
				// Cancel the default action, if needed
					event.preventDefault();
				// Trigger the button element with a click
					document.getElementById("btnConfirmAddBalance").click();
				}
			});
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
					showModal();
					$("#loader").show();
					$.post("accounts/addBalance.php", {mobileNumber:mobileNumberVal, accountID:accountIDVal, points:pointsVal1}, function(res){
						hideModal();
						$("#loader").hide();
						$("#modal_accountAddBalance").modal("hide");
						if(res == 0){
							swal("Error! Refresh the page and try again or system developer assistance is required!", "", "error");	
						}else if(res == 2){
							swal("Please logout and relogin your account!", "", "error");	
						}else if(res == 5){
							swal("Error! System generation code error. Please refresh the page and try to deposit again!", "", "error");	
						}else if(res == 7){
							swal("Error! No Event for today!", "", "error");	
						}else{								
							$("#modal_barcodeDeposit").modal("show");
							$("#barcodeValDeposit").html(res);
							
						}
					});
				}
				
			});	
			$("#btnCancelAddBalance").click(function(){
				$("#modal_accountAddBalance").modal("hide");
			});
			
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h3 class="modal-title" style="font-weight:bold;">DEPOSIT POINTS </h3><button type="button" class="btn btn-md btn-danger" data-dismiss="modal" >X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<h5>CURRENT POINTS: &nbsp;<span id = "spanAddBalance" style="font-weight:bold;"></span> </h5>
										<h5>NAME: &nbsp;<span id = "spanAddFname"  style="font-weight:bold;"></span> <span id = "spanAddLname" style="font-weight:bold;"></span></h5>
										<h5>MOBILE NUMBER: &nbsp;<span id = "spanAddMobileNumber"  style="font-weight:bold;"></span></h5>
										<input id="txtAddBalance" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:20px; letter-spacing:2px;" value = "" placeholder = "AMOUNT TO DEPOSIT" AUTOCOMPLETE = "OFF">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type = "button" class="btn btn-lg btn-raised btn-primary" id = "btnConfirmAddBalance" style="width:100%; font-weight:bold;">SUBMIT DEPOSIT REQUEST</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="modal fade" id="modal_accountWithdrawBalance" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			
			input = document.getElementById("txtWithdrawBalance");

			// Execute a function when the user releases a key on the keyboard
			input.addEventListener("keyup", function(event) {
			  // Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
				// Cancel the default action, if needed
					event.preventDefault();
				// Trigger the button element with a click
					document.getElementById("btnConfirmWithdrawBalance").click();
				}
			});
			
			$("#btnConfirmWithdrawBalance").click(function(){
				mobileNumberVal = $("#hiddenMobileNumber").val();
				accountIDVal = $("#hiddenAccountID").val();			
				pointsVal = $("#txtWithdrawBalance").val();
				pointsVal1 = parseFloat(pointsVal.replace(/,/g,""));
				if(pointsVal == ""){
					$("#txtWithdrawBalance").focus();
					swal("Please Input Points to Withdraw!","","error");
				}else{
					$.post("accounts/withdrawBalance.php", {accountID:accountIDVal, points:pointsVal1}, function(res){
						$("#modal_accountWithdrawBalance").modal("hide");
						
						if(res == 0){
							swal("Error! Refresh the page and try again or system developer assistance is required!", "", "error");	
						}else if(res == 2){
							swal("Error! Please logout and relogin your account!", "", "error");	
						}else if(res == 3){
							$("#txtWithdrawBalance").val("");
							swal("Error! Points to withdraw is greater than your current balance.", "", "error");	
						}else if(res == 4){
							swal("Error! You have an existing withdrawal request please proceed to cashier for assistance.", "", "error");	
						}else if(res == 5){
							swal("Error! System generation code error. Please refresh the page and try to withdraw again!", "", "error");	
						}else if(res == 7){
							swal("Error! No Event for today!", "", "error");	
						}else{								
							$("#modal_barcodeWithdraw").modal("show");
							$("#barcodeValWithdraw").html(res);
						}
					});
					
				}
				
			});	
			$(".btnCancelWithdrawBalance").click(function(){
				$("#modal_accountWithdrawBalance").modal("hide");
			});
			
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h3 class="modal-title" style="font-weight:bold;">WITHDRAW POINTS</h3><button type="button" class="btn btn-md btn-danger btnCancelWithdrawBalance">X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<h6>CURRENT POINTS: <span id = "spanWithdrawBalance" style="font-weight:bold;"></span> </h6>
										<h6>NAME: <span id = "spanWithdrawLname" style="font-weight:bold;"></span>, <span id = "spanWithdrawFname"  style="font-weight:bold;"></span> </h6>
										<h6>MOBILE NUMBER: <span id = "spanWithdrawMobileNumber"  style="font-weight:bold;"></span></h6>
										<input id="txtWithdrawBalance" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:20px; letter-spacing:2px;" value = "" placeholder = "AMOUNT TO WITHDRAW" AUTOCOMPLETE = "OFF">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type = "button" class="btn btn-md btn-raised btn-primary" id = "btnConfirmWithdrawBalance" style="width:100%;">SUBMIT WITHDRAWAL REQUEST</button>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_qrCode" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#sbmtPrintQr").click(function(){
				$("#frmPrintQrCode").submit();
				window.location.reload(true);
			});
			
			$("#closeModalQrCode").click(function(){
				window.location.reload(true);
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h2 class="modal-title">PRINT QR CODE</h2>
					<button type="button" class="close" id="closeModalQrCode" data-dismiss="modal">&times;</button>
					
				</div>
				<div class="modal-body" style="text-align:center;">
					<h3 id="h1QrCodeVal"></h3><br/>	
					<div id="qrcode" style="display: flex; justify-content: center; text-align: center;" ></div><br/><br/>
					<div style="display: flex; justify-content: center; text-align: center;" >
						<form method="POST" class="form-inline" target="_blank" action="bets/betsPrintQrCode.php" id="frmPrintQrCode">
							<input type = "hidden" name = "hiddenQrCode" id = "hiddenQrCode" /><br/>
							<input type="button" id ="sbmtPrintQr" class="btn btn-primary btn-lg" value = "PRINT QR CODE" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="container" >
	<div class="modal fade" id="modal_barcodeDeposit" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$(".btnBarcodeClose").click(function(){
				location.reload();
			});
		});
	</script>
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title" style="font-weight:bold;">DEPOSIT DETAILS</h4><button type="button" class="btn btn-md btn-danger btnBarcodeClose">X</button>
				</div>
				<div class="modal-body" style="text-align:center;">
					<div style="text-align:left;"><h6>1. Please proceed to the cashier. <br/>2. Present/show the TRANSACTION CODE.<br/> 3. Pay the amount of points to be deposited on your account!</h6><br/></div>
					<h4 id = "barcodeValDeposit"></h4>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<div class="modal fade" id="modal_barcodeWithdraw" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$(".btnBarcodeClose").click(function(){
				location.reload();
			});
		});
	</script>
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title" style="font-weight:bold;">WITHDRAWAL DETAILS</h4><button type="button" class="btn btn-md btn-danger btnBarcodeClose">X</button>
				</div>
				<div class="modal-body" style="text-align:center;">
					<div style="text-align:left;"><h6>1. Please proceed to the cashier. <br/>2. Present/show the TRANSACTION CODE.<br/> 3. Claim the cash amount equivalent to points to be withdawn!</h6><br/></div>
					<h4 id = "barcodeValWithdraw"></h4>
				</div>
			</div>
		</div>
	</div>
</div>