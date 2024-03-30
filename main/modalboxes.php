<!-- CSS FOR MODAL BOXES -->
<style type="text/css">
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
table#modalbox_container {
	position:fixed;
	top:0;
	left:0;
	z-index:2000;

	width:100%;
	height:100%;

	display:none;
}

div.modal_display #modal_table{
	border-radius:10px;
	background:#EEE;
	background-position:center;
	background-repeat:no-repeat;
	background-size:cover;*/

}
div.modal_display table tr td#modal_title {
	color:red;
	padding:5px;
	font-weight:bold;
	border-radius:7px 7px 0 0;
	height:20px;
	background: #848484;
}
div.modal_display table tr td#modal_content {}
div.modal_display table tr td#modal_footer {
	border-radius:7px 7px 0 0;
	padding:5px;
	height:20px;
}

.modalfield {
	border-bottom:#666 thin solid;
	font-weight:bold;
}
</style>

<script>
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
		<div id="loader" style="display:none;"><img src="design/images/loading.gif"></div>
    </td>
</tr>
</table>
