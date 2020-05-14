<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
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
                //$pwd = password_hash($params->password, PASSWORD_BCRYPT,['cost'  => 4]);
                $pwd = hash('sha256', $params->password);
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
    	$jwtAuth = new \JwtAuth();

        //recibir datos por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        //validar esos datos
         $validate = \Validator::make($params_array, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
    
            if ($validate->fails()) 
            {
                $signup = array(
                    'status' => 'error',
                    'code' => '404',
                    'message' => 'El ususario no se ha podido loguear',
                    'errors' => $validate->errors()
                );
            }else{
                    //cifrar pass
                $pwd = hash('sha256', $params->password);
                    ///devolver token o datos 
                $signup = $jwtAuth->signup($params->email, $pwd);
                if (!empty($params->gettoken)) {
                    $signup = $jwtAuth->signup($params->email, $pwd, true);
                }
            }
        return response()->json($signup, 200);
    }

    public function update(Request $request)
    {

        //comprobar si el usuario esta ifntifivafo 

        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //recoger dtos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if ($checkToken && !empty($params_array)) {
            //actualizar el usuario

          

            //sacar usuario identificaso
            $user = $jwtAuth->checkToken($token, true);

            //validar datos 
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,'.$user->sub //comprobar si usuario esta duplicado
                              
            ]);


            //quitar campos que no quiero acyualizar
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);
            //actualizar usuario en bd
            $user_update=User::where('id', $user->sub)->update($params_array);

            
            //devolver  array con resultado)
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array 
             );

        }else{
             $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado',
             );
        }
        return response()->json($data, $data['code']);
    }

    public function upload(Request $request)
    {

        //recoger datos de la peticio
        $image = $request->file('file0');

        ///validacin de imaghen

        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'

        ]);

        //subir imagen
        if (!$image || $validate->fails()) {
            $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Error al subir imagen'
            );
        }else{
            $image_name = time().$image->getClientOriginalName();
             \Storage::disk('users')->put($image_name, \File::get($image));
             $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
             );
        }

        //devolver el resultado

        
        return response()->json($data, $data['code']);
    }

    public function getImage($filename)
    {
        $isset = \Storage::disk('users')->exists($filename);
        if ($isset) {
            $file =  \Storage::disk('users')->get($filename);
        return new Response($file,200);
        }else{
             $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'la imagen no existe'
             );

             return response()->json($data, $data['code']);
        }
        
    }


    public function detail($id)
    {
        $user = User::find($id);
        if (is_object($user)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
             );
        }else{
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'el usuario no existe'
             );
        }

        return response()->json($data, $data['code']);
    }
}
