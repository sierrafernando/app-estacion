<?php

	session_start();

	include 'env.php';

	include 'lib/helper.php';

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
	/*
	if(isset($_SESSION[APP_NAME])){

		if($section=='landing' || $section=='login' || $section=='register'){
			$section='panel';
		}

	}else{ // Sesion no iniciada
		if($section=='panel' || $section=='logout'){
			$section='landing';
		}
	}
	*/
	
	include "controllers/{$section}Controller.php";


 ?>
