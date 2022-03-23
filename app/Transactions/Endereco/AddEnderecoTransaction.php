<?php 

namespace App\Transactions\Endereco;

use App\Model\Endereco\Endereco;
use App\Model\Endereco\Cidade;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddEnderecoTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $bairro, string $rua, int $numero, int $idCidade, string $referencia = null)
    {
        $this->bairro = $bairro;
        $this->rua = $rua;
        $this->numero = $numero;
        $this->idCidade = $idCidade;
        $this->referencia = $referencia;
    }

    public function execute()
    {
        $this->validarDados();

        $endereco = new Endereco();
        $endereco->bairro = $this->bairro;
        $endereco->rua = $this->rua;
        $endereco->numero = $this->numero;
        $endereco->cidade_id = $this->idCidade;
        $endereco->referencia = $this->referencia;
        
        $endereco->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->bairro, 'bairro', $erros);
        $validador->textoVazio($this->rua, 'rua', $erros);
        $validador->numeroNegativo($this->numero, 'numero', $erros);
        $validador->objetoInexistente(new Cidade(), $this->idCidade, 'cidade', $erros);
    }
}
