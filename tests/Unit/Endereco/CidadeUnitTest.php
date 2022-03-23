<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Endereco\Cidade;
use App\Transactions\Cidade\AddCidadeTransaction;

use Tests\TestCase;

class CidadeUnitTest extends TestCase
{

    private $nomeCidade = 'Cidade de teste';

    public function testAdicionarCidade()
    {
        $estado = $this->criarEstado();

        $transacao = new AddCidadeTransaction($this->nomeCidade, $estado->id);
        $transacao->execute();

        $cidade = Cidade::first();

        $this->assertNotNull($cidade);
        $this->assertEquals($this->nomeCidade, $cidade->nome);
        $this->assertEquals($estado, $cidade->estado);
    }

    public function testAdicionarCidadeInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AddCidadeTransaction('', 0);
            $transacao->execute();
        }
        catch (TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'estado', 'inexistente');
    }
   
}
