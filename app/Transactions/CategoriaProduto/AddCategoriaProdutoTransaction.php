<?php

namespace App\Transactions\CategoriaProduto;

use App\Model\Empresa;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Transaction;
use App\Model\CategoriaProduto;
use App\Transactions\ValidadorTransacao;

class AddCategoriaProdutoTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, int $idEmpresa)
    {
        $this->nome = $nome;
        $this->idEmpresa = $idEmpresa;
    }

    public function execute()
    {
        $this->validarDados();

        $categoria = new CategoriaProduto();
        $categoria->nome = $this->nome;
        $categoria->empresa_id = $this->idEmpresa;

        $categoria->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->objetoInexistente(new Empresa(), $this->idEmpresa, 'empresa', $erros);
    }

}

?>