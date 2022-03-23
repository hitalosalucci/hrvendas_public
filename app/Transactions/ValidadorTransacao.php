<?php

namespace App\Transactions;

use Illuminate\Database\Eloquent\Model;

class ValidadorTransacao
{
    public function textoVazio($texto, string $campo, array &$erros)
    {
        if (is_null($texto) || $texto == '')
            $erros[$campo] = 'vazio';
    }

    public function textoVazioOuNull($texto = null, string $campo, array &$erros)
    {
        if (!is_null($texto) && $texto == '')
            $erros[$campo] = 'vazio';
    }

    public function arrayVazia(array $array, $campo, array &$erros)
    {
        if (count($array) == 0)
            $erros[$campo] = 'vazio';
    }

    public function numeroNegativo(int $numero, $campo, array &$erros)
    {
        if ($numero < 0)
            $erros[$campo] = 'negativo';
    }

    public function numeroNegativoOuNull(int $numero = null, $campo, array &$erros)
    {
        if ($numero < 0 && $numero != null)
            $erros[$campo] = 'negativo';
    }

    public function objetoInexistente(Model $modelo, int $id, string $campo, array &$erros)
    {
        if ($modelo->where('id', $id)->count() == 0)
            $erros[$campo] = 'inexistente';
    }
}

?>