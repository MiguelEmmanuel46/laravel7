<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    //
    protected $table='ventas';
    protected $primaryKey = 'idventas';
        const UPDATED_AT = null;

    protected $fillable = [
        'id_producto','cantidad','total','fecha'
    ];


    public function user()
    {
    	return $this->belongsTo('App\User', 'id_usuario','id');
    }

    public function inventario()
    {
    	return $this->belongsTo('App\Inventario', 'id_producto', 'id_producto');
    }
}
