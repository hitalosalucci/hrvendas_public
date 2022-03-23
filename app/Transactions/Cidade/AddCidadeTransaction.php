<?php 

namespace App\Transactions\Cidade;

use App\Model\Endereco\Cidade;
use App\Model\Endereco\Estado;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddCidadeTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, int $idEstado)
    {
            $this->nome = $nome;
            $this->idEstado = $idEstado;
    }

    public function execute()
    {
        $this->validarDados();

        $cidade = new Cidade();
        $cidade->nome = $this->nome;
        $cidade->estado_id = $this->idEstado;

        $cidade->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->objetoInexistente(new Estado(), $this->idEstado, 'estado', $erros);
    }

}

?>