<?php

	if($_SESSION[APP_NAME]["user_name"] == 'admin-estacion@gmail.com'){
		$tpl = new Helper('views/administratorView.html');

		$usuario = new Tracker();

		$cantidad_trackers = $usuario->get_cant_trackers();

		$cantidad_users = $usuario->get_cant_users();

		$tpl->assign("USERS", "Cantidad de usuarios: ".$cantidad_users['usuarios']);

		$tpl->assign("CLIENTS", "Cantidad de clientes: ".$cantidad_trackers['visitas']);

		$tpl->printToScreen();
	} else {
		header("Location: ./login/713630");
	}

?>