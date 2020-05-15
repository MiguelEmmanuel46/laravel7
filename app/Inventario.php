<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    //
    protected $table ='inventario';
    protected $primaryKey = 'id_producto';
    const UPDATED_AT = null;
    const CREATED_AT = null;


    //muchos a uno
    
}
