<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
    
    public function empresa()
    {
        return $this->belongsTo('App\Model\Empresa');
    }
    public function endereco()
    {
        return $this->belongsTo('App\Model\Endereco\Endereco')->withDefault([
            'id' => 0,
            'bairro' => 'Sem bairro',
            'rua' => 'Sem rua',
            'referencia' => 'Sem referência',
            'numero' => 'S/N°',
            'cidade_id' => 0,
        ]);
    }
}
