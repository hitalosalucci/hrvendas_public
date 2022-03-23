<?php

namespace App\Transactions\Cliente;

use App\Model\Cliente;
use App\Model\Empresa;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\ValidadorTransacao;

class AlterarClienteTransaction implements Transaction
{
    use ValidaDados;
    use VerificaEmpresa;

    public function __construct(int $idCliente, string $nome, string $telefone = null, string $telefone2 = null, string $dataNascimento = null, string $cpf = null, string $identidade = null, int $idEndereco = null)
    {
        $this->idCliente = $idCliente;

        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->telefone2 = $telefone2;
        $this->dataNascimento = $dataNascimento;
        $this->cpf = $cpf;
        $this->identidade = $identidade;
        $this->idEndereco = $idEndereco;
        
    }

    public function execute()
    {
        $this->validarDados();

        $cliente = Cliente::find($this->idCliente);

        $this->autorizarMesmaEmpresa($cliente);

        $cliente->nome = $this->nome;
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
        $validador->objetoInexistente(new Cliente(), $this->idCliente, 'cliente', $erros);
        $validador->textoVazio($this->nome, 'nome', $erros);
    }
}
