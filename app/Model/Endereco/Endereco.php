<?php

namespace App\Model\Endereco;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    public function cidade()
    {
        return $this->belongsTo('App\Model\Endereco\Cidade')->withDefault([
            'id' => 0,
            'nome' => 'Sem cidade',
        ]);;
    }

    public function estado()
    {
        return $this->cidade->estado()->withDefault([
            'id' => 0,
            'nome' => 'Sem estado',
            'sigla' => 'N/A'
        ]);;
    }

    public function fornecedor()
    {
        return $this->hasOne('App\Model\Fornecedor');
    }

    public function empresa()
    {
        return $this->hasOne('App\Model\Empresa');
    }

    public function cliente()
    {
        return $this->hasOne('App\Model\Cliente');
    }

    public static function verificarSeEnderecoExiste($endereco)
    {
        $enderecoExiste = Endereco::where('bairro', $endereco['bairro'])
                                    ->where('rua', $endereco['rua'])
                                    ->where('referencia', $endereco['referencia'])
                                    ->where('numero', $endereco['numero'])
                                    ->where('cidade_id', $endereco['cidade']['idCidade']);
        
        if ($enderecoExiste->count() >= 1)
            return ['idEndereco' => $enderecoExiste->first()->id];
        else
            return false;
    }
}
