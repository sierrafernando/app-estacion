<?php 

	$tpl = new Helper('views/recoveryView.html');

	// si se presiono el boton
	if(isset($_POST['btn_recover'])){
	
		$usuario = new User($_POST["txt_email"]);

		$response = $usuario->recovery();

		// Si el proceso de restablecimiento fue exitoso
		if ($response["errno"]==200) {
			header("Location: ../login/713630");
		}
		// Si el usuario no existe
		else {
			$tpl->assign("MENSAJE", $response["error"]);
		}
	}

	$tpl->printToScreen();

 ?>
