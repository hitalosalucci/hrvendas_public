<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use SoftDeletes;
    
    public function empresa()
    {
        return $this->belongsTo('App\Model\Empresa');
    }

    public function produtos()
    {
        return $this->hasMany('App\Model\Produto');
    }
}
