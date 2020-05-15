<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Inventario;

class InventarioController extends Controller
{
	public function __construct()
	{
		$this->middleware('api.auth',['except' => ['index','show']]);
	}
    //
    public function index()
    {
    	$inventario = Inventario::all();

    	return response()->json([
    		'code' => 200,
    		'status' => 'success',
    		'inventario' => $inventario
    	]);
    }

    public function show($id_producto)
    {
    	$inventario = Inventario::find($id_producto);

    	if (is_object($inventario)) 
    	{
    		$data= ['code' => 200,
    		'status' => 'success',
    		'inventario' => $inventario
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
    	$params_array = json_decode($json, true);

    	if (!empty($params_array)) {
    		    	//validar datos
    		    	$validate =  \Validator::make($params_array, [
    		    		'nombre_producto' => 'required|regex:/^[\pL\s\-]+$/u',
    		    		'precio_venta' => 'required|numeric|min:0|max:999999.99|regex:/^\d+(\.\d{1,2})?$/',
    		    		'existencia' => 'required|numeric'
    		    	]);

    		    	//guardar categoria

    		    	if ($validate->fails()) {
    		    		$data= ['code' => 404,
    		    		'status' => 'error',
    		    		'mensaje' => 'No se ha guardado el producto'.$validate->errors()
    		    		];

    		    	}else{
    		    		$inventario = new Inventario();
    		    		$inventario->nombre_producto = $params_array['nombre_producto'];
    					$inventario->precio_venta = $params_array['precio_venta'];
    					$inventario->existencia = $params_array['existencia'];
    					$inventario->save();
    					$data= ['code' => 200,
    		    		'status' => 'success',
    		    		'inventario' => $inventario
    		    		];

    		    	}
    		    	
    		    	
    	}else{
    		$data= [
    			'code' => 404,
    			'status' => 'error',
    			'mensaje' => 'No has enviado ningun producto'
    		];
    	}


    	//devolver el resultado
		return response()->json($data, $data['code']);

    }

    public function update($id, Request $request)
    {

    	$json = $request->input('json', null);
    	$params_array = json_decode($json, true);

    	if (!empty($params_array)) 
    	{
    		$validate =  \Validator::make($params_array, [
    			'nombre_producto' => 'required|regex:/^[\pL\s\-]+$/u',
    			'precio_venta' => 'required|numeric|min:0|max:999999.99|regex:/^\d+(\.\d{1,2})?$/',
    			'existencia' => 'required|numeric'
    		]);


    		unset($params_array['id_producto']);

    		
    		
    		$inventario_update=Inventario::where('id_producto', $id)->update($params_array);

    		
    		//devolver  array con resultado)
    		$data = array(
    		    'code' => 200,
    		    'status' => 'success',
    		    'producto actualizado' => $params_array
    		 );

    	}
    	else{
    		$data= [
    			'code' => 404,
    			'status' => 'error',
    			'mensaje' => 'No has enviado ningun producto'
    		];
    	}
    	return response()->json($data, $data['code']);
    }
}
