<?php

	if($_SESSION[APP_NAME]["user_name"] == 'admin-estacion@gmail.com'){
		$tpl = new Helper('views/mapView.html');

		$tpl->printToScreen();
	} else {
		header("Location: ./panel");
	}

?>
