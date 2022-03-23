<?php

namespace App\Transactions\MetodoPagamento;

use App\Model\MetodoPagamento;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddMetodoPagamentoTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function execute()
    {
        $this->validarDados();

        $metodoPagamento = new MetodoPagamento();
        $metodoPagamento->nome = $this->nome;

        $metodoPagamento->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
    }
}

?>