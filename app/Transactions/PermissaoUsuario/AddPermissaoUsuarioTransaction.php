<?php

namespace App\Transactions\PermissaoUsuario;

use App\Model\Permissao;
use App\Model\Usuario\Usuario;
use App\Transactions\ValidadorTransacao;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Transaction;

class AddPermissaoUsuarioTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(int $idPermissao, int $idUsuario)
    {
        $this->idPermissao = $idPermissao;
        $this->idUsuario = $idUsuario;        
    }

    public function execute()
    {
        $this->validarDados();

        $usuario = Usuario::find($this->idUsuario);

        $usuario->permissoes()->attach($this->idPermissao);
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Permissao(), $this->idPermissao, 'permissao', $erros);
        $validador->objetoInexistente(new Usuario(), $this->idUsuario, 'usuario', $erros);
    }

}



?>