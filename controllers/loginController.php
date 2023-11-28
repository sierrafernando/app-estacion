<?php 

	$tpl = new Helper('views/loginView.html');

	$chipid = explode("/",$_SERVER["REQUEST_URI"]);

	$tpl->assign("CHIP_ID", end($chipid));

	// si se presiono el boton
	if(isset($_POST['btn_login'])){
	
		$usuario = new User($_POST["txt_email"]);

		$response = $usuario->login($_POST["txt_pass"]);

		// Si el usuario esta registrado ya esta logueado
		if ($response["errno"]==200) {

			$_SESSION[APP_NAME]=array("user_name" => $usuario->email);
			header("Location: ../detalle/".end($chipid));
		}
		// Si las credenciales son incorrectas, el usuario no está activo, no existe o esta bloqueado
		else {
			$tpl->assign("MENSAJE", $response["error"]);
		}
	}

	$tpl->printToScreen();

 ?>