<?php 

namespace App\Transactions\FuncionalidadeSistema;

use App\Model\FuncionalidadeSistema;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddFuncionalidadeSistemaTransaction implements Transaction
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

        $funcionalidade = new FuncionalidadeSistema();
        $funcionalidade->nome = $this->nome;
        $funcionalidade->codigo = $this->codigo;

        $funcionalidade->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->textoVazio($this->codigo, 'codigo', $erros);
    }
}

?>