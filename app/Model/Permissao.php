<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    public $table = 'permissoes';

    public function usuarios()
    {
        return $this->belongsToMany('App\Model\Usuario\Usuario');
    }
}
