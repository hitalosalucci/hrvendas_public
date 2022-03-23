<?php

namespace App\Transactions\Estado;

use App\Model\Endereco\Estado;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddEstadoTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, string $sigla)
    {
        $this->nome = $nome;
        $this->sigla = $sigla;
    }

    public function execute()
    {
        $this->validarDados();

        $estado = new Estado();
        $estado->nome = $this->nome;
        $estado->sigla = $this->sigla;

        $estado->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->textoVazio($this->sigla, 'sigla', $erros);
    }
}

?>