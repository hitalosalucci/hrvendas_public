<?php

namespace App\Model\Usuario;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Usuario extends BaseUsuario
{
    use SoftDeletes;

    public function empresa()
    {
        return $this->belongsTo('App\Model\Empresa');
    }

    public function validarSenha(string $senha) : bool
    {
        return Hash::check($senha, $this->senha);
    }

    public function lotes()
    {
        return $this->hasMany('App\Model\Lote');
    }

    public function permissoes()
    {
        return $this->belongsToMany('App\Model\Permissao');
    }

    public function verificarPermissao($codigo)
    {
        $countPermissoes = $this->permissoes()->where('codigo', $codigo)->count();

        return $countPermissoes > 0;
    }
}
