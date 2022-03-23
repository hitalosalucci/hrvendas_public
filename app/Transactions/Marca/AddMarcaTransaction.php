<?php

namespace App\Transactions\Marca;

use App\Model\Empresa;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Transaction;
use App\Model\Marca;
use App\Transactions\ValidadorTransacao;

class AddMarcaTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, int $idEmpresa, string $codigo = null)
    {
        $this->nome = $nome;
        $this->idEmpresa = $idEmpresa;
        $this->codigo = $codigo;
    }

    public function execute()
    {
        $this->validarDados();

        $marca = new Marca();
        $marca->nome = $this->nome;
        $marca->empresa_id = $this->idEmpresa;
        $marca->codigo = $this->codigo;

        $marca->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->objetoInexistente(new Empresa(), $this->idEmpresa, 'empresa', $erros);
    }

}

?>