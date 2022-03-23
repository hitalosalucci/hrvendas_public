<?php

namespace App\Model\Usuario;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

abstract class BaseUsuario extends User
{
    //string nome
    //string login

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function validarSenha(string $senha) : bool
    {
        return Hash::check($senha, $this->senha);
    }

    //abstract public function verificarAdministrador() : bool;
}
