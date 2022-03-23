<?php

namespace App\Transactions\Cliente;

use App\Model\Cliente;
use App\Model\Empresa;
use App\Model\Endereco\Endereco;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddClienteTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, int $idEmpresa, string $telefone = null, string $telefone2 = null, string $dataNascimento = null, string $cpf = null, string $identidade = null, int $idEndereco = null)
    {
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->telefone2 = $telefone2;
        $this->dataNascimento = $dataNascimento;
        $this->cpf = $cpf;
        $this->identidade = $identidade;
        $this->idEmpresa = $idEmpresa;
        $this->idEndereco = $idEndereco;
        
    }

    public function execute()
    {
        $this->validarDados();

        $cliente = new Cliente();
        $cliente->nome = $this->nome;
        $cliente->empresa_id = $this->idEmpresa;
        $cliente->telefone = $this->telefone;
        $cliente->telefone2 = $this->telefone2;
        $cliente->data_nascimento = $this->dataNascimento;
        $cliente->cpf = $this->cpf;
        $cliente->identidade = $this->identidade;
        $cliente->endereco_id = $this->idEndereco;
                
        $cliente->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->objetoInexistente(new Empresa(), $this->idEmpresa, 'empresa', $erros);    
    }
}
