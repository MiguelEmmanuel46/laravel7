<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    //
    protected $table ='caja';
    
    public function user()
    {
    	return $this.belongsTo('App\User', 'id_usuario','id');
    }
}
