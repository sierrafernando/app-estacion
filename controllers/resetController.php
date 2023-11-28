<?php
	
	$tpl = new Helper('views/resetView.html');

	$url = explode("/",$_SERVER["REQUEST_URI"]);
	
	$token_action = end($url);	

	if(isset($_POST['btn_recover'])){
	
		$usuario = new Update('token_action', $token_action);

		$response = $usuario->reset($_POST['txt_pass'],$_POST['txt_pass2']);

		// Si el proceso de reseteo fue exitoso
		if ($response["errno"]==200) {
			header("Location: ../login/713630");
		}
		// Si el usuario no existe, el usuario no se encuentra bloqueado o en recupero, o el usuario no se encuentra registrado
		else {
			$tpl->assign("MENSAJE", $response["error"]);
		}
	}

	$tpl->printToScreen();
?>
