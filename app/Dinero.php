<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dinero extends Model
{
    //
    protected $table ='dinero_diario';

    public function user()
    {
    	return $this.belongsTo('App\User', 'id_usuario','id');
    }
}
