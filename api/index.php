<?php 

	// GET

	// POST

	include '../env.php';

	include '../models/dbAbstractModel.php';

	header("Content-Type: application/json");

	$_SECTION = explode("/", $_GET["section"]);

	$_SECTION = array_values($_SECTION);

	$buffer = array();

	$model = $_SECTION[0];

	$model = ucfirst($model);

	if(!file_exists("../models/".$model."Model.php")){
		$buffer = ["errno" => 404, "error" => "no existe el modelo ".$model."Model.php"];

		echo json_encode($buffer);
		exit();
	}

	include_once "../models/".$model."Model.php";

	$object = new $model();

	$method = $_SECTION[1];

	if($method == "list-clients-location"){
		$method = 'get_trackers';
	}

	if(!method_exists($object, $method)){
		$buffer = ["errno" => 404, "error" => "no existe el metodo '".$method."' en la clase ".$model."Model.php"];

		echo json_encode($buffer);
		exit();	
	}

	$request_method = $_SERVER['REQUEST_METHOD'];

	switch ($request_method) {
		case 'GET':
			unset($_SECTION[0]);
			unset($_SECTION[1]);

			$parameters = array_values($_SECTION);
			break;
		
		case 'POST':
			$parameters = $_POST;
			break;

		case 'DELETE':

			unset($_SECTION[0]);
			unset($_SECTION[1]);

			$parameters = array_values($_SECTION);
			break;

		case 'PUT':

			parse_str(file_get_contents('php://input'),$_PUT);

			$parameters = $_PUT;

			break;

		default:
			// code...
			break;
	}

	

	$response = $object->$method($parameters);

	
	echo json_encode($response);

 ?>