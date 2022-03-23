<?php

namespace App\Transactions\Usuario;

use App\Model\Usuario\Usuario;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;
use Illuminate\Support\Facades\Hash;

class AlterarSenhaUsuarioTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(int $id, $novaSenha)
    {
        $this->id = $id;
        $this->senha = $novaSenha;
    }

    public function execute()
    {
        $this->validarDados();

        $usuario = Usuario::find($this->id);

        $usuario->senha = Hash::make($this->senha);

        $usuario->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Usuario(), $this->id, 'usuario', $erros);
        $validador->textoVazio($this->senha, 'nova-senha', $erros);
    }
}

?>