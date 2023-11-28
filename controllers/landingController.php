<?php 

	$tpl = new Helper('views/landingView.html');

	session_unset();
	session_destroy();

	$tpl->printToScreen();

 ?>