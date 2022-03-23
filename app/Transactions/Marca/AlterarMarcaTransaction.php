<?php

namespace App\Transactions\Marca;

use App\Model\Empresa;
use App\Model\Marca;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class AlterarMarcaTransaction implements Transaction
{
    use ValidaDados;
    use VerificaEmpresa;

    public function __construct(int $id, string $nome, string $codigo = null)
    {   
        $this->id = $id;
        $this->nome = $nome;
        $this->codigo = $codigo;
    }

    public function execute()
    {
        $this->validarDados();

        $marca = Marca::find($this->id);

        $this->autorizarMesmaEmpresa($marca);

        $marca->nome = $this->nome;
        $marca->codigo = $this->codigo;

        $marca->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->objetoInexistente(new Marca(), $this->id, 'marca', $erros);
    }
}


?>