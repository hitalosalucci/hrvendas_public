<?php

namespace App\Transactions\PermissaoUsuario;

use App\Model\Permissao;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddPermissaoTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, string $codigo)
    {
        $this->nome = $nome;
        $this->codigo = $codigo;
    }

    public function execute()
    {
        $this->validarDados();

        $usuario = new Permissao;
        $usuario->nome = $this->nome;
        $usuario->codigo = $this->codigo;
        
        $usuario->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->textoVazio($this->codigo, 'codigo', $erros);
    }
}

?>