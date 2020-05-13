<?php 

namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth
{
	public $key;
	public function __construct()
	{
		$this->key = 'esto_es_una_clave_super_secreta-99887766';
	}

	public function signup($email, $password, $getToken=null)
	{
	//buscar si existe el ususario
		$user= User::where([
			'email' => $email,
			'password' => $password
		])->first();
	//cpṕrbar si son correctas
		$signup = false;
		if (is_object($user)) {
			# code...
			$signup = true;
		}
	//generar token con los datos del usuario ifentifivdo
		if ($signup) 
		{
			$token =array(
				'sub' => $user->id,
				'email' => $user->email,
				'name' => $user->name,
				'surname' => $user->surname,
				'iat' => time(),
				'exp' => time() + (7 * 24 * 60 * 60)
			);
			$jwt = JWT::encode($token, $this->key, 'HS256');
			$decoded = JWT::decode($jwt, $this->key, ['HS256']);
			//devolver datos decodificados o el token en funcion de un parametrio
			if (is_null($getToken)) {
				$data =  $jwt;
			}else{
				$data = $decoded;
			}
		}else{
			$data = array(
				'status' => 'error',
				'message' => 'Login incorrrecto',
			);
		}

		return $data;
	
	}
	


}