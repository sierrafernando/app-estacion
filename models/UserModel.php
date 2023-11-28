<?php


	/**
	 * 
	 */
	class User extends DBAbstract
	{

		public $token;
		public $email;
		public $pass;
		public $activo = false;
		public $bloqueado = false;
		public $recupero = false;
		public $token_action;
		public $add_date;
		public $recover_date;

		private $register = true;

		function __construct($email){
			$this->email = $email;

			// busco si el usuario está registrado
			$sql = "SELECT * FROM `appEstacion__usuarios` WHERE `email`='$this->email';";

			$user_list = $this->query($sql);

			// var_dump($user_list);

			// si el usuario está registrado
			if(count($user_list)>0){
				// deshabilito el registro del usuario
				$this->register = false;
				
				// almaceno la contraseña 
				$this->pass = $user_list[0]['contraseña'];

				// si el bloqueo está activo
				if ($user_list[0]['bloqueado'] == 1){
					// habilito el bloqueo del usuario
					$this->bloqueado = true;
				} 
				// si el recupero está activo
				if ($user_list[0]['recupero'] == 1){
					// habilito el recupero del usuario
					$this->recupero = true;
				} 
				// si el usuario está activo
				if ($user_list[0]['activo'] == 1){
					// habilito el usuario activo
					$this->activo = true;
				}

				// token del usuario
				$this->token = $user_list[0]['token'];
			}
		}

		/**
		 * 
		 * Solo si el usuario es nuevo lo registra pidiendo dos contraseñas
		 * 
		 * */
		function register($pass,$pass2){

			// el usuario ya existe...
			if(!$this->register){
				
				return array("errno" => 300, "error" => "El usuario ya se encuentra registrado");
			}
			// hay campos vacíos...
			else if (empty($this->email) || $this->email == PHP_EOL || empty($pass) || $pass == PHP_EOL || empty($pass2) || $pass2 == PHP_EOL){
				
				return array("errno" => 301, "error" => "Completar todos los campos");
			}
			// las contraseñas no coinciden...
			else if ($pass != $pass2){
				
				return array("errno" => 302, "error" => "Las contraseñas no coinciden");
			}

			// creo el token y token_action
			$this->token = bin2hex(openssl_random_pseudo_bytes(16));
			$this->token_action = bin2hex(openssl_random_pseudo_bytes(16));
			$this->add_date = shell_exec("date +'%Y-%m-%d %T'");
			
			$sql = "INSERT INTO `appEstacion__usuarios` (`token`, `email`, `contraseña`, `activo`, `token_action`,`add_date`) VALUES ('$this->token', '$this->email', '".password_hash($pass,PASSWORD_DEFAULT)."','0', '$this->token_action', '$this->add_date');";

			$this->query($sql);
					
			// envio un mail dando la bienvenida al nuevo usuario
			$email = new EmailEngine();
			$email->send($_POST['txt_email'],'¡Bienvenido a App-Estación!',"App-Estación es una app-web con la cual podremos ver las diferentes temperaturas que se encuentran en las estaciones meteorológicas instaladas a lo largo y ancho del país. Por favor siga el siguiente enlace para activar su cuenta.<br/>
				<a href='https://mattprofe.com.ar/alumno/3893/app-estacion/validate/$this->token_action'>Activar cuenta</a>");	

			return array("errno" => 200, "error" => "Se agrego correctamente el usuario");
		}


		/**
		 * 
		 * valida la contraseña de un usuario valido
		 * 
		 * */
		function login($pass){

			// el usuario ya existe...
			if(!$this->register){
				// si el usuario no esta activado...
				if (!$this->activo){
					
					return array("errno" => 402, "error" => "Su usuario aún no se ha validado, revise su casilla de correo");
				}
				// si el usuario esta bloqueado...
				else if ($this->bloqueado || $this->recupero){
					
					return array("errno" => 403, "error" => "Su usuario está bloqueado, revise su casilla de correo");
				}
				// si el usuario es correcto...
				else if(password_verify($pass, $this->pass)){

					$info = explode('(', $_SERVER['HTTP_USER_AGENT']);

					$ip=$_SERVER['REMOTE_ADDR'];
					$nave = $info[0];
					$tiempo = shell_exec("date +'%Y-%m-%d %T'");

					$info = explode(')',$info[1]);
					$info = explode(';',$info[0]);

					// envio un mail avisando que se inicio sesion
					$email = new EmailEngine();
					$email->send($_POST['txt_email'],'Se inició sesión',"Se ha iniciado sesión correctamente.<br/>
						Ip: $ip <br/>
						Navegador Web: $nave <br/>
						Sistema Operativo: $info[0], $info[1], $info[2] <br/>
						Fecha y Hora: $tiempo <br/>
						<a href='https://mattprofe.com.ar/alumno/3893/app-estacion/blocked/$this->token'>No fui yo, bloquear cuenta</a>");

					return array("errno" => 200, "error" => "Se logueo correctamente");
				}
				// si las credenciales son incorrectas...
				else{
					
					return array("errno" => 401, "error" => "Credenciales incorrectas");
				}			
			}

			// si el usuario no esta registrado...
			return array("errno" => 404, "error" => "Usuario no registrado");
		}

		/**
		 * 
		 * Si el usuario se olvidó la contraseña la puede recuperar
		 * 
		 * */
		function recovery(){

			// hay campos vacíos...
			if (empty($this->email) || $this->email == PHP_EOL){
				
				return array("errno" => 301, "error" => "Completar todos los campos");
			}

			// el usuario ya existe...
			else if(!$this->register){
				
				$this->recover_date = shell_exec("date +'%Y-%m-%d %T'");
				$this->token_action = bin2hex(openssl_random_pseudo_bytes(16));

				$sql = "UPDATE `appEstacion__usuarios` SET `recupero`=1, `recover_date`='$this->recover_date', `token_action`='$this->token_action' WHERE `email` = '$this->email'";

				$this->query($sql);

				// envio un mail avisando que el usuario fue bloqueado
				$email = new EmailEngine();
				$email->send($this->email,'¡Cuenta en restablecimiento!',"Se inició el proceso de restablecimiento de contraseña. <br/> <a href='https://mattprofe.com.ar/alumno/3893/app-estacion/reset/$this->token_action'>Click aquí para restablecer la contraseña</a>");	

				return array("errno" => 200, "error" => "Cuenta en restablecimiento");	
				
			} else {
				// si el usuario no esta registrado...
				return array("errno" => 404, "error" => "Usuario no registrado");
			}			
		}
	}
 ?>