<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Ventas;
use App\Helpers\JwtAuth;

class VentasController extends Controller
{
    //

    public function __construct()
    {
    	$this->middleware('api.auth',['except' => ['index','show']]);
    }
    

    public function index()
    {
    	$ventas = Ventas::all()->load('user')->load('inventario');

    	return response()->json([
    		'code' => 200,
    		'status' => 'success',
    		'ventas' => $ventas
    	], 200);
    }

    public function show($id)
    {
    	$ventas = Ventas::find($id)->load('inventario')->load('user');

    	if (is_object($ventas)) 
    	{
    		$data= ['code' => 200,
    		'status' => 'success',
    		'ventas' => $ventas
    		];
    	}else{
    		$data= ['code' => 404,
    		'status' => 'error',
    		'mensaje' => 'producto no existe'
    		];
    	}
    	return response()->json($data, $data['code']);
    }

        public function store(Request $request)
    {
    	//recoger datos por post
    	$json = $request->input('json', null);
    	$params = json_decode($json);
    	$params_array = json_decode($json, true);




    	if (!empty($params_array)) {
    				//datos del usuario
    				$user = $this->getIdentity($request);

    		    	//validar datos
    		    	$validate =  \Validator::make($params_array, [
    		    		'id_producto' => 'required|numeric',
    		    		'cantidad' => 'required|numeric',
    		    		'total' => 'required|numeric|min:0|max:999999.99|regex:/^\d+(\.\d{1,2})?$/',
    		    		'fecha' => 'required'
    		    	]);

    		    	//guardar 

    		    	if ($validate->fails()) {
    		    		$data= ['code' => 404,
    		    		'status' => 'error',
    		    		'mensaje' => 'No se ha registrado la venta'.$validate->errors()
    		    		];

    		    	}else{
    		    		$ventas = new Ventas();
    		    		$ventas->id_producto = $params_array['id_producto'];
    					$ventas->cantidad = $params_array['cantidad'];
    					$ventas->total = $params_array['total'];
    					$ventas->fecha = $params_array['fecha'];
    					$ventas->id_usuario = $user->sub;
    					

    					$ventas->save();
    					$data= ['code' => 200,
    		    		'status' => 'success',
    		    		'ventas' => $ventas
    		    		];

    		    	}
    		    	
    		    	
    	}else{
    		$data= [
    			'code' => 404,
    			'status' => 'error',
    			'mensaje' => 'No has enviado ninguna venta'
    		];
    	}


    	//devolver el resultado
		return response()->json($data, $data['code']);

    }

    public function update($id, Request $request)
    {
    	$user = $this->getIdentity($request);

    	$json = $request->input('json', null);
    	$params_array = json_decode($json, true);

    	if (!empty($params_array)) 
    	{
    		$validate =  \Validator::make($params_array, [
    			'id_producto' => 'required|numeric',
    		    'cantidad' => 'required|numeric',
    		    'total' => 'required|numeric|min:0|max:999999.99|regex:/^\d+(\.\d{1,2})?$/',
    		    'fecha' => 'required'
    		]);

    		if ($validate->fails()) {
    			return response()->json($validate->errors(), 400);
    		}
    		unset($params_array['idventas']);
    		unset($params_array['created_at']);
    		unset($params_array['id_usuario']);

    		
    		
    		$ventas_update=Ventas::where('idventas', $id)->where('id_usuario', $user->sub)->update($params_array);

    		
    		//devolver  array con resultado)
    		$data = array(
    		    'code' => 200,
    		    'status' => 'success',
    		    'venta actualizado' => $params_array
    		 );

    	}
    	else{
    		$data= [
    			'code' => 404,
    			'status' => 'error',
    			'mensaje' => 'No has enviado ninguna venta'
    		];
    	}
    	return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request)
    {
    	//datos del usuario
    	$user = $this->getIdentity($request);

    	//conseguir post
    	$ventas = Ventas::where('idventas',$id)->where('id_usuario', $user->sub)->first();

    	if (!empty($ventas)) {
    		//borrarlo
    		//$ventas_delete=Ventas::where('idventas', $id)->delete();
    		$ventas->delete();

    		$data= [
    				'code' => 200,
    				'status' => 'success',
    				'venta' => $ventas
    			];
    	}else{
    		$data= [
    				'code' => 404,
    				'status' => 'error',
    				'message' => 'No existe ese registro'
    			];
    	}
    	

    	return response()->json($data, $data['code']);
    }

    private function getIdentity($request)
    {
    	//datos del usuario
    	$jwtAuth = new JwtAuth();
    	$token = $request->header('Authorization', null);
    	$user=$jwtAuth->checkToken($token, true);
    	
    	return $user;
    }
}
