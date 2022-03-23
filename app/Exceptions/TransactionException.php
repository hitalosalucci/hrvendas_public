<?php

namespace App\Exceptions;

use Exception;

class TransactionException extends Exception
{
    private $erros;

    public function __construct($erros, $codigo = 0, Exception $anterior = null)
    {
        $this->erros = $erros;

        parent::__construct($this->gerarMensagem($erros), $codigo, $anterior);
    }

    public function getErros() : array
    {
        return $this->erros;
    }

    private function gerarMensagem(array $erros)
    {
        $mensagem = '';

        foreach ($erros as $campo => $erro)
        {
            $mensagem .= $campo.': '.$erro.'; ' ;
        }

        return $mensagem;
    }
}
