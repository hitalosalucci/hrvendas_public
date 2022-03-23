<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaProduto extends Model
{
    use SoftDeletes;

    public $table = 'categorias_produtos';

    public function empresa()
    {
        return $this->belongsTo('App\Model\Empresa');
    }

    public function produtos()
    {
        return $this->hasMany('App\Model\Produto');
    }
}
