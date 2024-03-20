<!-- CSS FOR MODAL BOXES -->
<style type="text/css">
/* PAGE BLOCKER FOR MODAL BOXES */
div#pageblocker {
	position:fixed;
	top:0;
	left:0;
	z-index:1000;
	
	width:100%;
	height:100%;
	
	background:#666;
	opacity:.3;
	
	display:none;
}
/* MODAL BOX CONTAINER FOR POSITIONING */
table#modalbox_container {
	position:fixed;
	top:0;
	left:0;
	z-index:2000;
	
	width:100%;
	height:100%;
	
	display:none;
}
</style>

<script language="javascript">
$(document).ready(function(e) {
	//executes by closing modalboxes
	//close button class must be "modal_close"
	$("button.modal_close").click(function(e) {
		hideModal();
	});
});

//call this function to hide modal boxes
function hideModal() {
	$("div.modal_display").fadeOut();
	$("table#modalbox_container").fadeOut();
	$("div#pageblocker").fadeOut();
}
//call this function before showing modal boxes
function showModal() {
	$("#pageblocker").show();
	$("#modalbox_container").show();
	//$("#modalbox_container #loader").fadeIn();
}
</script>

<!-- PAGE BLOCKER FOR MODAL BOXES -->
<div id="pageblocker"></div>

<!-- CONTAINER FOR MODAL BOXES -->
<table id="modalbox_container" align="center">
<tr>
	<td valign="middle" align="center">
		<div id="loader" style="display:none;"><img src="main/assets/images/loading.gif"></div>
    </td>
</tr>
</table>
