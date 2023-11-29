<?php

	$tpl = new Helper('views/panelView.html');

	$usuario = new Tracker();

	$usuario->update_tracker();

	$tpl->printToScreen();

?>