<?php

namespace App\Transactions\Traits;

use App\Exceptions\TransactionException;
use App\Transactions\ValidadorTransacao;

trait ValidaDados
{
    protected function validarDados()
    {
        $validador = new ValidadorTransacao();
        $erros = [];

        $this->validar($validador, $erros);

        if (count($erros) > 0)
            throw new TransactionException($erros);
    }

    abstract protected function validar(ValidadorTransacao $validador, array &$erros);
}

?>