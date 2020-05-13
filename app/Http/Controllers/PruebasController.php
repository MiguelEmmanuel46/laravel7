<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ventas;
use App\Inventario;

class PruebasController extends Controller
{
    //
    public function index(){
        $titulo = 'Animales';
        $animales=['perro','gato','tigre'];
        return view('pruebas.index', array(
            'titulo' => $titulo,
            'animales' => $animales
        ));
    }

    public function testOrm()
    {
        $ventas = Ventas::all();
         echo "
            <table class='table table-bordered'>
              <thead>
                <tr>
                  <th scope='col'>Nombre Producto</th>
                  <th scope='col'>Cantidad</th>
                  <th scope='col'>Total $</th>
                  <th scope='col'>Fecha</th>
                  <th scope='col'>Quien la vendio</th>
                </tr>
              </thead>
              <tbody>";
        foreach ($ventas as $key => $venta) {
            # code...
            echo " <tr>
                  <th scope='row'>{$venta->inventario->nombre_producto}</th>
                  <td>{$venta->cantidad}</td>
                  <td>{$venta->total}</td>
                  <td>{$venta->fecha}</td>
                  <td>{$venta->user->name}</td>
                </tr>";

           
               
               
        }
         echo "
              </tbody>
            </table>
            ";
        die();
    }
}
