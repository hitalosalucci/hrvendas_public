<?php

namespace App\Http\Controllers;

trait FormataErrosTransacoes
{
    protected function formatarErrosTransacao(array $erros) : array
    {
        $novaArrayErros = [];
        $nomeCampo = $this->getNomeCampo();

        foreach ($erros as $campo => $erro)
        {
            if ($campo == $nomeCampo)
                $novaArrayErros[$nomeCampo] = $erro;
            else if ($campo == 'objeto')
                $novaArrayErros['objeto'] = $erro;
            else
                $novaArrayErros[$campo.'-'.$nomeCampo] = $erro;
        }
            

        return $novaArrayErros;
    }

    protected abstract function getNomeCampo() : string;
}

?>