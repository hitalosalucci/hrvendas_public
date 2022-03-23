<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    public function usuario()
    {
        return $this->belongsTo('App\Model\Usuario\Usuario');
    }

    public function itensLote()
    {
        return $this->hasMany('App\Model\LoteProduto');
    }

    public function fornecedor()
    {
        return $this->belongsTo('App\Model\Fornecedor');    
    }
}
