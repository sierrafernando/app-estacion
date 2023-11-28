<?php 

	$tpl = new Helper('views/registerView.html');

	$chipid = explode("/",$_SERVER["REQUEST_URI"]);

	$tpl->assign("CHIP_ID", end($chipid));

	if(isset($_POST["btn_register"])){

		$usuario = new User($_POST['txt_email']);

		$response = $usuario->register($_POST['txt_pass'],$_POST['txt_pass2']);

		// Si el registro fue exitoso
		if ($response["errno"]==200) {
			header("Location: ../login/".end($chipid));
		}
		// Si el usuario ya existe, los campos estan vacios o las contraseñas no son iguales
		else {
			$tpl->assign("MENSAJE", $response["error"]);
		}
	}
	

	$tpl->printToScreen();

 ?>
