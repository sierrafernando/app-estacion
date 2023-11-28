<?php
	
	$tpl = new Helper('views/blockedView.html');

	$url = explode("/",$_SERVER["REQUEST_URI"]);
	$token = end($url);	

	$usuario = new Update('token', $token);

	$response = $usuario->blocked();

	// Si se bloqueo correctamente
	if ($response["errno"]==200) {
		$tpl->assign("MENSAJE", $response["error"]);
	}
	// Si el usuario ya se encuentra bloqueado o no existe
	else {
		$tpl->assign("MENSAJE", $response["error"]);
	}

	session_unset();
	session_destroy();

	$tpl->printToScreen();
?>
