<?php

namespace App\Model\Endereco;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    public function estado()
    {
        return $this->belongsTo('App\Model\Endereco\Estado')->withDefault(
            [
                'id' => 0,
                'nome' => 'Sem estado',
                'sigla' => 'Sem estado',
            ]
        );
    }

    public static function verificarSeCidadeExiste($cidade, $estadoInformado)
    {

        $cidadeExiste = Cidade::where('nome', $cidade)->where('estado_id', $estadoInformado);
        
        if ($cidadeExiste->count() >= 1)
            return ['idCidade' => $cidadeExiste->first()->id];
        else
            return false;

    }

}
