<?php

	
	/**
	 * 
	 */
	class Tracker extends DBAbstract
	{

		public function construct() {
            parent::__construct();
            $response = $this->query("SELECT * FROM appEstacion__tracker");
            $campos = $response->fetch_fields();


            foreach ($campos as $key => $campo) {
                $buffer = $campo->name;
                $this->$buffer = "";
            }
        }

		/**
		 * 
		 * Obtener todos los trackers
		 * 
		 * */
		public function get_trackers(){

			// obtengo al información de los trackers
			$sql = "SELECT `ip`,`latitud`,`longitud`,count(ip) as 'visitas' FROM `appEstacion__tracker` GROUP BY `ip`; ";

			$trackers_list = $this->query($sql);

			return $trackers_list;
		}


		/**
		 * 
		 * Insertar un nuevo tracker
		 * 
		 * */
		public function update_tracker(){

			$info = explode('(', $_SERVER['HTTP_USER_AGENT']);

			$ip=$_SERVER['REMOTE_ADDR'];
			$nave = $info[0];

			$info = explode(')',$info[1]);
			$info = explode(';',$info[0]);

			// Protección para evitar leer una ip local 
			if($ip == "127.0.0.1") {
				$ip = "181.47.205.193"; // Usamos un ip pública
			}
			
			// Consulta a la api para obtener más información de la ip
			$web = file_get_contents("http://ipwho.is/".$ip);

			// Convierte el json recuperado en un objeto
			$response = json_decode($web);

			// inserto la información del nuevo tracker
			$sql = "INSERT INTO `appEstacion__tracker`(`ip`, `latitud`, `longitud`, `pais`, `navegador`, `sistema`) VALUES ('$ip','$response->latitude','$response->longitude','$response->country','$nave','$info[0], $info[1], $info[2]'); ";

			$tracker_update = $this->query($sql);

			// se insertó el nuevo tracker correctamente
			return array("errno" => 200, "error" => "Se creo el nuevo tracker");
		}

		/**
		 * 
		 * Obtener cantidad numerica de trackers
		 * 
		 * */
		public function get_cant_trackers(){
			// obtengo al información de los trackers
			$sql = "SELECT count(DISTINCT ip) as 'visitas' FROM `appEstacion__tracker`;";

			$trackers_cant = $this->query($sql);

			$response = reset($trackers_cant);

			return $response;
		}

		/**
		 * 
		 * Obtener cantidad numerica de usuarios
		 * 
		 * */
		public function get_cant_users(){

			// obtengo al información de los trackers
			$sql = "SELECT count(id) as 'usuarios' FROM `appEstacion__usuarios`; ";

			$users_cant = $this->query($sql);

			$response = reset($users_cant);

			return $response;
		}
	}
 ?>
