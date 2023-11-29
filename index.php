<?php

	session_start();

	include 'env.php';

	include 'models/dbAbstractModel.php';
	include 'models/UserModel.php';
	include 'models/UpdateModel.php';
	include 'models/TrackerModel.php';

	include 'lib/helper.php';
	include 'lib/sendmail.php';

	include 'lib/Mailer/src/PHPMailer.php';
	include 'lib/Mailer/src/SMTP.php';
	include 'lib/Mailer/src/Exception.php';

	$_SECTION = explode("/", $_SERVER["REQUEST_URI"]);

	unset($_SECTION[0],$_SECTION[1],$_SECTION[2],$_SECTION[3]);

	$_SECTION = array_values($_SECTION);

	// var_dump($_SECTION);

	//router
	if($_SECTION[0]==""){
		$section = 'landing';	
	}else{
		$section = $_SECTION[0];
		if(!file_exists("controllers/{$section}Controller.php")){
			$section = 'error404';
		}
	}

	// Sesion iniciada
	if(isset($_SESSION[APP_NAME]["user_name"])){
		// En caso de esta logueado
		if($section=="login" || $section=="register" || $section=="validate" || $section=="recovery" || $section=="reset"){
				$section="panel";
		}
	}
	
	include "controllers/{$section}Controller.php";
 ?>
