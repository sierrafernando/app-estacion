<?php
	
	$tpl = new Helper('views/validateView.html');

	$url = explode("/",$_SERVER["REQUEST_URI"]);
	$token_action = end($url);	

	$usuario = new Update('token_action', $token_action);

	$response = $usuario->validate();

	// Si se activo correctamente
	if ($response["errno"]==200) {
		header("Location: ../login/713630");
	}
	// Si el usuario ya existe, los campos estan vacios o las contraseñas no son iguales
	else {
		$tpl->assign("MENSAJE", $response["error"]);
	}

	$tpl->printToScreen();
?>