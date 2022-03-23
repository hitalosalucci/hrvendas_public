<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

    public function categoria_produto()
    {
        return $this->belongsTo('App\Model\CategoriaProduto');
    }

    public function marca()
    {
        return $this->belongsTo('App\Model\Marca')->withDefault([
            'id' => 0,
            'nome' => 'Sem marca',
        ]);
    }

    public function empresa()
    {
        return $this->categoria_produto->empresa();
    }
}
