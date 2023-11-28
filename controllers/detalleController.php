<?php
	$chipid = explode("/",$_SERVER["REQUEST_URI"]);

	if(!isset($_SESSION[APP_NAME]["user_name"])){
		header("Location: ../login/".end($chipid));
	} else {

		$tpl = new Helper('views/detalleView.html');

		$tpl->assign("CHIP_ID", end($chipid));

		$tpl->printToScreen();
	}
?>
