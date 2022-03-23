<?php

namespace App\Transactions\Empresa;

use App\Model\Empresa;
use App\Model\Endereco\Endereco;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddEmpresaTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, string $nomeEmpresarial, string $cnpj, string $telefone, int $idEndereco, string $telefone2 = null)
    {
        $this->nome = $nome;
        $this->nomeEmpresarial = $nomeEmpresarial;
        $this->cnpj = $cnpj;
        $this->telefone = $telefone;
        $this->idEndereco = $idEndereco;
        $this->telefone2 = $telefone2;
    }

    public function execute()
    {   
        $this->validarDados();

        $empresa = new Empresa();
        $empresa->nome = $this->nome;
        $empresa->nome_empresarial = $this->nomeEmpresarial;
        $empresa->cnpj =  $this->cnpj;
        $empresa->endereco_id =  $this->idEndereco;
        $empresa->telefone = $this->telefone;
        $empresa->telefone2 = $this->telefone2;

        $empresa->save();
    }

    public function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->textoVazio($this->nomeEmpresarial, 'nome_empresarial', $erros);
        $validador->textoVazio($this->cnpj, 'cnpj', $erros);
        $validador->textoVazio($this->telefone, 'telefone', $erros);
        $validador->objetoInexistente(new Endereco(), $this->idEndereco, 'endereco', $erros);
    }
} 

?>