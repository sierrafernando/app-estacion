<?php


	/**
	 * 
	 */
	class Update extends DBAbstract
	{

		public $user = false;
		public $email;
		public $token_action;
		public $token;
		public $activo = false;
		public $bloqueado = false;
		public $recupero = false;
		public $active_date;
		public $blocked_date;
		public $update_date;

		function __construct($type, $data){

			// Busco si el usuario esta registrado
			$sql = "SELECT * FROM `appEstacion__usuarios` WHERE `$type`='$data';";

			$user_list = $this->query($sql);

			//var_dump($user_list);

			if(count($user_list)>0){

				// el usuario existe
				$this->user = true;
				// email del usuario
				$this->email = $user_list[0]['email'];
				// token del usuario
				$this->token = $user_list[0]['token'];
				// token_action del usuario
				$this->token_action = $user_list[0]['token_action'];

				// si el bloqueo está activo
				if ($user_list[0]['bloqueado'] == 1){
					// habilito el bloqueo del usuario
					$this->bloqueado = true;
				}
				// si el bloqueo está activo
				if ($user_list[0]['recupero'] == 1){
					// habilito el bloqueo del usuario
					$this->recupero = true;
				} 
				// si el usuario está activo
				if ($user_list[0]['activo'] == 1){
					// habilito el usuario activo
					$this->activo = true;
				}
			}
		}

		/**
		 * 
		 * Activar el usuario
		 * 
		 * */
		function validate(){

			if($this->user){
				// se encontró al usuario desactivado
				if(!$this->activo){

					$this->active_date = shell_exec("date +'%Y-%m-%d %T'");

					// activo el usuario
					$sql = "UPDATE `appEstacion__usuarios` SET `token_action`='', `activo`=1, `active_date`='$this->active_date' WHERE `token_action` = '$this->token_action'";

					$this->query($sql);

					// envio un mail avisando que el usuario fue activado
					$email = new EmailEngine();
					$email->send($this->email,'¡Usuario activado!',"Su usuario ha sido activado correctamente.");	

					return array("errno" => 200, "error" => "Se activó correctamente");	
				} else {
					return array("errno" => 405, "error" => "El usuario ya se encuentra activo");	
				}
			} else {
				return array("errno" => 404, "error" => "Usuario no registrado");
			}
		}

		/**
		 * 
		 * Bloquear el usuario
		 * 
		 * */
		function blocked(){

			if($this->user){
				// se encontró al usuario desbloqueado
				if(!$this->bloqueado){

					$this->blocked_date = shell_exec("date +'%Y-%m-%d %T'");

					$this->token_action = bin2hex(openssl_random_pseudo_bytes(16));

					// bloqueo el usuario
					$sql = "UPDATE `appEstacion__usuarios` SET `token_action`='$this->token_action', `bloqueado`=1, `blocked_date`='$this->blocked_date' WHERE `token` = '$this->token'";

					$this->query($sql);

					// envio un mail avisando que el usuario fue bloqueado
					$email = new EmailEngine();
					$email->send($this->email,'¡Usuario bloqueado!',"Su usuario ha sido bloqueado. <br/> <a href='https://mattprofe.com.ar/alumno/3893/app-estacion/reset/$this->token_action'>Click aquí para cambiar contraseña</a>");	

					return array("errno" => 200, "error" => "Usuario bloqueado, revise su correo electrónico");	
				} else {
					return array("errno" => 405, "error" => "El usuario ya se encuentra bloqueado");	
				}
			} else {
				return array("errno" => 404, "error" => "Usuario no registrado");
			}
		}

		/**
		 * 
		 * Modificar la contraseña del usuario
		 * 
		 * */
		function reset($pass,$pass2){
			
			if($this->user){
				// se encontró al usuario bloqueado o recupero
				if($this->bloqueado || $this->recupero){

					// hay campos vacíos...
					if (empty($pass) || $pass == PHP_EOL || empty($pass2) || $pass2 == PHP_EOL){
						
						return array("errno" => 301, "error" => "Completar todos los campos");
					}
					// las contraseñas no coinciden...
					else if ($pass != $pass2){
						
						return array("errno" => 302, "error" => "Las contraseñas no coinciden");
					}

					$this->update_date = shell_exec("date +'%Y-%m-%d %T'");

					// reinicio la contraseña
					$sql = "UPDATE `appEstacion__usuarios` SET `token_action`='', `bloqueado`=0, `recupero`=0,`contraseña`='".password_hash($pass,PASSWORD_DEFAULT)."', `update_date`='$this->update_date' WHERE `token_action` = '$this->token_action'";

					$this->query($sql);

					$info = explode('(', $_SERVER['HTTP_USER_AGENT']);

					$ip=$_SERVER['REMOTE_ADDR'];
					$nave = $info[0];

					$info = explode(')',$info[1]);
					$info = explode(';',$info[0]);

					// envio un mail avisando que la contraseña fue restablecida
					$email = new EmailEngine();
					$email->send($this->email,'¡Contraseña restablecida!',"Su contraseña fue restablecida correctamente. <br/> 
						Ip: $ip <br/>
						Navegador Web: $nave <br/>
						Sistema Operativo: $info[0], $info[1], $info[2] <br/>
						Fecha y Hora: $this->update_date <br/>
						<a href='https://mattprofe.com.ar/alumno/3893/app-estacion/blocked/$this->token'>No fui yo, bloquear cuenta</a>");	

					return array("errno" => 200, "error" => "Contraseña restablecida");	
				} else {
					return array("errno" => 405, "error" => "El usuario no se encuentra bloqueado o en recupero");	
				}
			} else {
				return array("errno" => 404, "error" => "Usuario no registrado");
			}
		}
	}
?>