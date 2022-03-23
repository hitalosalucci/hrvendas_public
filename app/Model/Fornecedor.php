<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    public $table = 'fornecedores';

    public function empresa()
    {
        return $this->belongsTo('App\Model\Empresa');
    }

    public function endereco()
    {
        return $this->belongsTo('App\Model\Endereco\Endereco');
    }

    public function lote()
    {
        return $this->hasMany('App\Model\Lote');
    }
}
