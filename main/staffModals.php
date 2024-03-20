<!--<form method = "POST" class = "form-inline" target = "_blank" action = "print/printCashin.php" id = "frmCashin">
	<input type="hidden" name="cashin_text">
	<input type="hidden" name="txtcashinamount" id = "txtcashinamount">
	<input type="submit" name="generate_cashin" id = "sbmtCashin" style = "display:none;" value = "CASHIN">
</form>-->

<form method = "POST" class = "form-inline" target = "_blank" action = "print/printCashinteller.php" id = "frmCashinteller">
	<input type="hidden" name="cashinteller_text">
	<input type="hidden" name="txtfrmCashintelleramount" id = "txtfrmCashintelleramount">
	<input type="hidden" name="txtfrmCashinhandlerID" id = "txtfrmCashinhandlerID">
	<input type="hidden" name="txtfrmCashintellerpassword" id = "txtfrmCashintellerpassword">
	<input type="submit" name="generate_cashinteller" id = "sbmtCashinteller" style = "display:none;" value = "CASH IN TELLER">
</form>

<form method = "POST" class = "form-inline" target = "_blank" action = "print/printCashoutteller.php" id = "frmCashoutteller">
	<input type="hidden" name="cashoutteller_text">
	<input type="hidden" name="txtfrmCashouttelleramount" id = "txtfrmCashouttelleramount">
	<input type="hidden" name="txtfrmCashouthandlerID" id = "txtfrmCashouthandlerID">
	<input type="hidden" name="txtfrmCashouttellerpassword" id = "txtfrmCashouttellerpassword">
	<input type="submit" name="generate_cashoutteller" id = "sbmtCashoutteller" style = "display:none;" value = "CASH OUT TELLER">
</form>


<form method = "POST" class = "form-inline" target = "_blank" action = "print/printCashout.php" id = "frmCashout">
	
	<input type="hidden" name="cashout_text">
	<input type="hidden" name="hiddentellercashoutID" id = "hiddentellercashoutID">
	<input type="hidden" name="txtcashoutamount" id = "txtcashoutamount">
	<input type="submit" name="generate_cashout" id = "sbmtCashout" style = "display:none;" value = "CASHOUT">
</form>
<script type="text/javascript" src="assets/js/autoNumeric.js"></script>
<script type="text/javascript">
		jQuery(function($) {
			$('.auto').autoNumeric('init');
		});
	$(document).ready(function(){
		$("#changePassword").click(function(){
			$("#passwordOld").val("");
			$("#passwordNew").val("");
			$("#passwordConfirm").val("");
			$("#modal_changePassword").modal("show");
		});
		
		$("#cashin").click(function(){
			$("#modal_cashin").modal("show");
		});
		
		$('#modal_cashin').on('shown.bs.modal', function () {
			setTimeout(function (){
				$('#txtCashin').val("").focus();
			}, 100);
		});	
		
		$("#closeModalCashin").click(function(){
			$("aside ul #cashin").children("a").removeClass("active");
		});
		
		$("#cashoutteller").click(function(){
			$("#modal_cashoutteller").modal("show");
		});
		
		$('#modal_cashoutteller').on('shown.bs.modal', function () {
			setTimeout(function (){
				$('#txtCashoutteller').val("").focus();
				$('#selCashoutHandler').val("");
				$('#txtCashouttellerpassword').val("");
			}, 100);
		});
		
		$("#cashinteller").click(function(){
			$("#modal_cashinteller").modal("show");
		});
		
		$('#modal_cashinteller').on('shown.bs.modal', function () {
			setTimeout(function (){
				$('#txtCashinteller').val("").focus();
				$('#selCashinHandler').val("");
				$('#txtCashintellerpassword').val("");
			}, 100);
		});
		
		
		$("#cashout").click(function(){
			$("#modal_cashout").modal("show");
		});
		
		$('#modal_cashout').on('shown.bs.modal', function () {
			setTimeout(function (){
				$('#txtCashout').val("").focus();
			}, 100);
		});	
		
		$("#closeModalCashout").click(function(){
			$("aside ul #cashout").children("a").removeClass("active");
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
					<h4 class="modal-title" style="font-weight:bold;">Password</h4><button type="button" class="btn btn-md btn-danger" id="closeModalPassword" data-dismiss="modal" >X</button>
				</div>
				<div class="modal-body">

					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
										<span style='font-weight:bolder; font-size:15px;'>Old Password:</span>
										<input type="password" class="form-control" id="passwordOld" maxlength = "32" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" placeholder="Your Old Password Here..."/>
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
	<div class="modal fade" id="modal_cashin" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnCashin").click(function(){
				betAmount = $("#txtCashin").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));

				if(betAmount == 0){
					$("#txtCashin").focus();
					swal("ERROR! Please Input Cash IN Amount!","","error");
				}else if(betAmount < 100){
					$("#txtCashin").focus();
					swal("ERROR! Please Input Cash IN Amount! Minimum Cash IN is 100 Points!","","error");
				}else if(betAmount == ""){
					$("#txtCashin").focus();
					swal("ERROR! Please Input Cash IN Amount!","","error");
				}else{
					$("#txtcashinamount").val(betAmount1);
					$("#sbmtCashin").click();	
		
				}
			});
			$("#sbmtCashin").click(function(){
				$("#modal_cashin").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title" style="font-weight:bold;">Cash IN</h4><button type="button" class="btn btn-md btn-danger" id="closeModalCashin" data-dismiss="modal" >X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12">

							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash IN Amount:</span>
									<input id="txtCashin" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;" value = ""placeholder="ENTER AMOUNT HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS>
									</div>
								</div>
							</div>
							<div class="row" style="margin:1px; text-align:center;">
								<div class="col-md-12">
									<input type="button" id = "btnCashin" class="btn btn-raised btn-success" value = "Submit Cash IN">
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
	<div class="modal fade" id="modal_cashinteller" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnCashinteller").click(function(){
				betAmount = $("#txtCashinteller").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				pass = $("#txtCashintellerpassword").val();
				chID = $("#selCashinHandler option:selected").val();
				if(betAmount == 0){
					$("#txtCashinteller").focus();
					swal("ERROR! Please Input Cash IN Amount!","","error");
				}else if(betAmount < 100){
					$("#txtCashinteller").focus();
					swal("ERROR! Please Input Cash IN Amount! Minimum Cash IN is 100 Points!","","error");
				}else if(betAmount == ""){
					$("#txtCashinteller").focus();
					swal("ERROR! Please Input Cash IN Amount!","","error");
				}else if(chID == ""){
					$("#selCashinHandler").focus();
					swal("ERROR! Please Select Cash IN Handler!","","error");
				}else if(pass == ""){
					$("#txtCashintellerpassword").focus();
					swal("ERROR! Please Input Cash IN Handler Password!","","error");
				}else{
					$("#txtfrmCashintelleramount").val(betAmount1);
					$("#txtfrmCashinhandlerID").val(chID);
					$("#txtfrmCashintellerpassword").val(pass);
					$("#sbmtCashinteller").click();	
		
				}
			});
			$("#sbmtCashinteller").click(function(){
				$("#modal_cashinteller").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title" style="font-weight:bold;">Cash IN</h4><button type="button" class="btn btn-md btn-danger" id="closeModalCashinteller" data-dismiss="modal" >X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12">

							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash IN Amount:</span>
									<input id="txtCashinteller" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;" value = ""placeholder="ENTER AMOUNT HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS>
									</div>
								</div>
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash IN Handler:</span>
									<select id="selCashinHandler" class="form-control "  style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;">
									<?php
										$queryCI = $mysqli->query("SELECT `id`, `username`, `cname` FROM `tblusers` WHERE `roleID` = '13' AND isActive = '1'  ");
										if($queryCI->num_rows > 0){
											echo '<option value = "">SELECT CASHIN HANDLER</option>';
											while($rowCI = $queryCI->fetch_assoc()){
												echo '<option value = "'.$rowCI['id'].'">'.$rowCI['username'].' - '.$rowCI['cname'].'</option>';
											}
										}else{
											echo '<option value = "">NO AVAILABLE CASHIN HANDLER</option>';
										}
									?>
									</select>
									</div>
								</div>
								
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash IN Handler Password:</span>
									<input id="txtCashintellerpassword" type="password" class="form-control" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;" placeholder="ENTER CASHIN HANDLER PASSWORD HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS>
									</div>
								</div>
							</div>
							<div class="row" style="margin:1px; text-align:center;">
								<div class="col-md-12">
									<input type="button" id = "btnCashinteller" class="btn btn-raised btn-primary" value = "Submit Teller Cash IN">
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
	<div class="modal fade" id="modal_cashoutteller" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnCashoutteller").click(function(){
				betAmount = $("#txtCashoutteller").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				pass = $("#txtCashouttellerpassword").val();
				chID = $("#selCashoutHandler option:selected").val();
				if(betAmount == 0){
					$("#txtCashoutteller").focus();
					swal("ERROR! Please Input Cash OUT Amount!","","error");
				}else if(betAmount < 100){
					$("#txtCashoutteller").focus();
					swal("ERROR! Please Input Cash OUT Amount! Minimum Cash OUT is 100 Points!","","error");
				}else if(betAmount == ""){
					$("#txtCashoutteller").focus();
					swal("ERROR! Please Input Cash OUT Amount!","","error");
				}else if(chID == ""){
					$("#selCashoutHandler").focus();
					swal("ERROR! Please Select Cash Out Handler!","","error");
				}else if(pass == ""){
					$("#txtCashouttellerpassword").focus();
					swal("ERROR! Please Input Cash Out Handler Password!","","error");
				}else{
					$("#txtfrmCashouttelleramount").val(betAmount1);
					$("#txtfrmCashouthandlerID").val(chID);
					$("#txtfrmCashouttellerpassword").val(pass);
					$("#sbmtCashoutteller").click();	
		
				}
			});
			$("#sbmtCashoutteller").click(function(){
				$("#modal_cashoutteller").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title" style="font-weight:bold;">Cash OUT</h4><button type="button" class="btn btn-md btn-danger" id="closeModalCashoutteller" data-dismiss="modal" >X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12">

							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash OUT Amount:</span>
									<input id="txtCashoutteller" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;" value = ""placeholder="ENTER AMOUNT HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS>
									</div>
								</div>
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash OUT Handler:</span>
									<select id="selCashoutHandler" class="form-control "  style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;">
									<?php
										$queryCH = $mysqli->query("SELECT `id`, `username`, `cname` FROM `tblusers` WHERE `roleID` = '13' AND isActive = '1'  ");
										if($queryCH->num_rows > 0){
											echo '<option value = "">SELECT CASHOUT HANDLER</option>';
											while($rowCH = $queryCH->fetch_assoc()){
												echo '<option value = "'.$rowCH['id'].'">'.$rowCH['username'].' - '.$rowCH['cname'].'</option>';
											}
										}else{
											echo '<option value = "">NO AVAILABLE CASHOUT HANDLER</option>';
										}
									?>
									</select>
									</div>
								</div>
								
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash OUT Handler Password:</span>
									<input id="txtCashouttellerpassword" type="password" class="form-control" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;" value = ""placeholder="ENTER CASHOUT HANDLER PASSWORD HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS>
									</div>
								</div>
							</div>
							<div class="row" style="margin:1px; text-align:center;">
								<div class="col-md-12">
									<input type="button" id = "btnCashoutteller" class="btn btn-raised btn-primary" value = "Submit Teller Cash OUT">
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
	<div class="modal fade" id="modal_cashout" role="dialog" data-backdrop="static" data-keyboard="false">
	<script>
		$(document).ready(function(){
			$("#btnCashout").click(function(){
				betAmount = $("#txtCashout").val();
				betAmount1 = parseFloat(betAmount.replace(/,/g,""));
				tellercashout = $("#tellercashout option:selected").val();

				if(tellercashout == ""){
					$("#tellercashout").focus();
					swal("ERROR! Please select the teller to receive your cash out transaction!","","error");
				}else if(betAmount <= 0){
					$("#txtCashout").focus();
					swal("ERROR! Please Input Cash OUT Amount!","","error");
				}else if(betAmount == ""){
					$("#txtCashout").focus();
					swal("ERROR! Please Input Cash OUT Amount!","","error");
				}else{
					$("#txtcashoutamount").val(betAmount1);
					$("#hiddentellercashoutID").val(tellercashout);
					$("#sbmtCashout").click();	
					$("#modal_cashout").modal("hide");
		
				}
			});
			$("#sbmtCashout").click(function(){
				$("#modal_cashout").modal("hide");
			});
		});
	</script>
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<h4 class="modal-title" style="font-weight:bold;">Cash OUT</h4><button type="button" class="btn btn-md btn-danger" id="closeModalCashout" data-dismiss="modal" >X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12">

							<div class="well">
								<div class="row" style="margin:1px;">
									<div class="col-md-12">
										<select class="form-control" name="tellercashout" id = "tellercashout" REQUIRED>
											<?php
												$qteller = $mysqli->query("SELECT * FROM tblusers WHERE roleID = '2' AND isActive = '1'");
												if($qteller->num_rows > 0){
													echo '<option value = "">SELECT TELLER</option>';
													while($rteller = $qteller->fetch_assoc()){
														echo '<option value = "'.$rteller['id'].'">'.$rteller['username'].'</option>';
													}
												}else{
													
												}
											?>
										</select>
									</div>
									<div class="col-md-12">
									<span style='font-weight:bolder; font-size:15px;'>Cash OUT Amount:</span>
									<input id="txtCashout" type="text" class="form-control auto" style="background-color:#1f364f; text-align:center; color:yellow; font-weight:bolder; font-size:15px;height:60px;" value = ""placeholder="ENTER AMOUNT HERE" AUTOCOMPLETE = "OFF" AUTOFOCUS>
									</div>
								</div>
							</div>
							<div class="row" style="margin:1px; text-align:center;">
								<div class="col-md-12">
									<input type="button" id = "btnCashout" class="btn btn-raised btn-primary" value = "Save Cash OUT">
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
	<div class="modal fade" id="modal_isBet" role="dialog" data-backdrop="static" data-keyboard="false">
		<script>
			$(document).ready(function(){
				$("#btnIsBetYes").click(function(){
					$("#sbmtGenerateBarcode").click();
				});
			});
		</script>
	
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-primary">
					<button type="button" class="close" id="closeModal" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">BET AMOUNT CONFIRMATION</h2>
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



		