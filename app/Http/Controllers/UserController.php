<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function pruebas(Request $request)
    {
        return "Pruebas de user controller";
    }
    
    public function register(Request $request)
    {
        //recoger los datos del uaurrio por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array =  json_decode($json, true);
        if (!empty($params) && !empty($params_array)) 
        {
            //limpiar datos
            $params_array = array_map('trim', $params_array);
            //validar datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users', //comprobar si usuario esta duplicado
                'password' => 'required'
            ]);
    
            if ($validate->fails()) 
            {
                $data = array(
                    'status' => 'error',
                    'code' => '404',
                    'message' => 'El ususario no se creo',
                    'errors' => $validate->errors()
                );
            }else{
                //cifrar pass
                $pwd = password_hash($params->password, PASSWORD_BCRYPT,['cost'  => 4]);
                //crear el usuario
                $user = new User();
                $user->name =$params_array['name'];
                $user->surname =$params_array['surname'];
                $user->email =$params_array['email'];
                $user->password =$pwd;
                $user->role="user";
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'El ususario  se creo'
                );
            }
        }else{          
                $data = array(
                    'status' => 'success',
                    'code' => '404',
                    'message' => 'Los datos enviados no son correctos'
                );
            }
        return response()->json($data, $data['code']);
    }

    public function login(Request $request)
    {
    	
    	return "login";
    }
}
