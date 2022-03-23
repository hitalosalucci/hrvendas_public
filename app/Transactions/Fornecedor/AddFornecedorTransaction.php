<?php

namespace App\Transactions\Fornecedor;

use App\Model\Empresa;
use App\Model\Endereco\Endereco;
use App\Model\Fornecedor;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddFornecedorTransaction implements Transaction
{
    use ValidaDados;
    
    public function __construct(string $nome, int $idEmpresa, string $cnpj = null, string $telefone = null, string $telefone2 = null, string $descricao = null, int $idEndereco = null)
    {
        $this->nome = $nome;
        $this->idEmpresa = $idEmpresa;
        $this->cnpj = $cnpj;
        $this->telefone = $telefone;
        $this->telefone2 = $telefone2;
        $this->descricao = $descricao;
        $this->idEndereco = $idEndereco;        
    }

    public function execute()
    {
        $this->validarDados();

        $fornecedor = new Fornecedor();
        $fornecedor->nome = $this->nome;
        $fornecedor->empresa_id = $this->idEmpresa;
        $fornecedor->cnpj = $this->cnpj;
        $fornecedor->telefone = $this->telefone;
        $fornecedor->telefone2 = $this->telefone2;
        $fornecedor->descricao = $this->descricao;
        $fornecedor->endereco_id = $this->idEndereco;

        $fornecedor->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->objetoInexistente(new Empresa(), $this->idEmpresa, 'empresa', $erros);
    }
}

?>