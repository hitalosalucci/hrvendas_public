<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\MetodoPagamento;
use App\Transactions\MetodoPagamento\AddMetodoPagamentoTransaction;
use Tests\TestCase;

class MetodoPagamentoUnitTest extends TestCase
{
    private $nome = 'Dinheiro';

    public function testCadastrarMetodoPagamento()
    {
        $transacao = new AddMetodoPagamentoTransaction($this->nome);
        $transacao->execute();

        $metodoPagamento = MetodoPagamento::first();

        $this->assertNotNull($metodoPagamento, 'Método de pagamento não cadastrado');
        $this->assertEquals($this->nome, $metodoPagamento->nome, 'Nome do método de pagamento incorreto');
    }

    public function testCadastrarMetodoPagamentoInvalido()
    {
        $excecao = null;

        try
        {
            $t = new AddMetodoPagamentoTransaction('');
            $t->execute();
        }
        catch (TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
    }
}
