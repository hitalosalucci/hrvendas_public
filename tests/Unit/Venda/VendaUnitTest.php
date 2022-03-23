<?php

namespace Tests\Unit\Venda;

use App\Exceptions\TransactionException;
use App\Model\Venda;
use App\Transactions\Transaction;
use App\Transactions\Venda\AddVendaTransaction;
use App\Transactions\Venda\AlterarVendaTransaction;
use App\Transactions\Venda\RemoverVendaTransaction;
use Illuminate\Auth\Access\AuthorizationException;

class VendaUnitTest extends BaseVendaUnitTest
{
    public $valorTotal = 18.00;
    public $valorDesconto = 2;
    //public $valorTroco = 250;

    protected function setUp(): void
    {
        parent::setUp();

        $this->produtos = $this->criarProdutos();
        $this->itens = $this->criarItensVenda($this->produtos);
        $this->metodosPagamento = $this->criarMetodosDePagamento();
        $this->metodosVenda = $this->criarMetodosPagamentoVenda($this->metodosPagamento);

    }

    public function testRegistrarVenda()
    {
        $transacao = new AddVendaTransaction($this->usuario->id, $this->valorTotal, $this->itens, $this->metodosVenda, $this->valorDesconto, $this->cliente->id);
        $transacao->execute();
        
        $venda = Venda::first();

        $this->assertNotNull($venda, 'Venda não registrada');
        $this->assertNotNull($venda->itens, 'Itens de vendas não registrados');
        $this->assertCount(count($this->itens), $venda->itens, 'Quantidade de itens da venda incorreta');
        $this->assertEquals($this->valorTotal, $venda->valor_total, 'Valor total da venda incorreto');
        $this->assertEquals($this->valorDesconto, $venda->valor_desconto, 'Valor desconto incorreto');
        //$this->assertEquals($this->valorTroco, $venda->valor_troco, 'Valor troco incorreto');
        $this->assertEquals($this->usuario, $venda->usuario, 'Usuário incorreto');
        $this->assertEquals($this->cliente, $venda->cliente, 'Cliente incorreto');
        $this->assertEquals($this->empresa->nome, $venda->usuario->empresa->nome, 'Empresa incorreta');
    }

    public function testRegistrarVendaSemOpcionais()
    {
        $transacao = new AddVendaTransaction($this->usuario->id, $this->valorTotal, $this->itens, $this->metodosVenda);
        $transacao->execute();
        
        $venda = Venda::first();

        $this->assertNotNull($venda, 'Venda não registrada');
        $this->assertNotNull($venda->itens, 'Itens de vendas não registrados');
        $this->assertCount(count($this->itens), $venda->itens, 'Quantidade de itens da venda incorreta');
        $this->assertEquals($this->valorTotal, $venda->valor_total, 'Valor total da venda incorreto');
        $this->assertEquals($venda->valor_desconto, null, 'Valor desconto incorreto');
        $this->assertEquals($venda->valor_troco, null, 'Valor troco incorreto');
        $this->assertEquals($venda->cliente->nome, 'Consumidor não identificado', 'Cliente incorreto');        
        $this->assertEquals($this->usuario, $venda->usuario, 'Usuário incorreto');
        $this->assertEquals($this->empresa->nome, $venda->usuario->empresa->nome, 'Empresa incorreta');
    }

    public function testRegistrarVendaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AddVendaTransaction(0, -43.23, [], [], -5, 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'usuario', 'inexistente');
        $this->assertErroTransacao($excecao, 'valor_total', 'negativo');
        $this->assertErroTransacao($excecao, 'valor_desconto', 'negativo');
        $this->assertErroTransacao($excecao, 'itens', 'vazio');
        $this->assertErroTransacao($excecao, 'metodos_pagamento', 'vazio');
    }

    public function testAlterarVenda()
    {
        $addVenda = new AddVendaTransaction($this->usuario->id, $this->valorTotal, $this->itens, $this->metodosVenda, $this->valorDesconto, $this->cliente->id);
        $addVenda->execute();

        $venda = Venda::where('usuario_id', $this->usuario->id)->latest()->first();

        $this->actingAs($this->usuario);

        $venda->refresh();

        //criar novos itens para alterar a venda
        $valorTotal = 19.90;
        $valorDesconto = 3;
        //$valorTroco = 200;

        //criando novos itens
        $produtos = $this->criarProdutos();
        $itens = $this->criarItensVenda($produtos);
        $metodosPagamento = $this->criarMetodosDePagamento();
        $metodosVenda = $this->criarMetodosPagamentoVenda($metodosPagamento);

        $transacao = new AlterarVendaTransaction($venda->id, $this->usuario->id, $valorTotal, $itens, $metodosVenda, $valorDesconto, $this->cliente->id);
        $transacao->execute();

        $vendaAlterada = Venda::find($venda->id);

        $this->assertNotNull($vendaAlterada->itens, 'Itens de vendas não registrados');
        $this->assertCount(count($itens), $vendaAlterada->itens, 'Quantidade de itens da venda incorreta');
        $this->assertEquals($valorTotal, $vendaAlterada->valor_total, 'Valor total da venda incorreto');
        $this->assertEquals($valorDesconto, $vendaAlterada->valor_desconto, 'Valor desconto incorreto');
        //$this->assertEquals($valorTroco, $vendaAlterada->valor_troco, 'Valor troco incorreto');
        $this->assertEquals($this->usuario->nome, $vendaAlterada->usuario->nome, 'Usuário incorreto');
        $this->assertEquals($this->cliente, $vendaAlterada->cliente, 'Cliente incorreto');
        $this->assertEquals($this->empresa->nome, $vendaAlterada->usuario->empresa->nome, 'Empresa incorreta');

        
    }

    public function testAlterarVendaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AlterarVendaTransaction(0, 0, -23, [], [], -32);
            $transacao->execute();

        }
        catch (TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'venda', 'inexistente');
        $this->assertErroTransacao($excecao, 'usuario', 'inexistente');
        $this->assertErroTransacao($excecao, 'valor_total', 'negativo');
        //$this->assertErroTransacao($excecao, 'valor_desconto', 'negativo');
        $this->assertErroTransacao($excecao, 'itens', 'vazio');
        $this->assertErroTransacao($excecao, 'metodos_pagamento', 'vazio');
    }

    public function testAlterarVendaOutraLanchonete()
    {
        $addVenda = new AddVendaTransaction($this->usuario->id, $this->valorTotal, $this->itens, $this->metodosVenda, $this->valorDesconto, $this->cliente->id);
        $addVenda->execute();

        $venda = Venda::where('usuario_id', $this->usuario->id)->latest()->first();

        $outraEmpresa = $this->criarEmpresa();
        $outroUsuario = $this->criarUsuario($outraEmpresa);
        $this->actingAs($outroUsuario);

        //criar novos itens para alterar a venda
        $valorTotal = 19.90;
        $valorDesconto = 3;
        //$valorTroco = 200;

        $excecao = null;
        
        try
        {
            $transacao = new AlterarVendaTransaction($venda->id, $outroUsuario->id, $valorTotal, $this->itens, $this->metodosVenda, $valorDesconto, $this->cliente->id);
            $transacao->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');

    }

    public function testRemoverVenda()
    {

        $empresa = $this->criarEmpresa();
        $usuario = $this->criarUsuario($empresa);
        $this->actingAs($usuario);

        $addVenda = new AddVendaTransaction($usuario->id, $this->valorTotal, $this->itens, $this->metodosVenda, $this->valorDesconto, $this->cliente->id);
        $addVenda->execute();

        $venda = Venda::first();

        $removeVenda = new RemoverVendaTransaction($venda->id);
        $removeVenda->execute();

        $venda = Venda::first();

        $this->assertNull($venda);
    }

    public function testRemoverVendaInvalida()
    {
        $excecao = null;

        try
        {
            $t = new RemoverVendaTransaction(0);
            $t->execute();
        } catch (TransactionException $ex) {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'objeto', 'inexistente');
    }

    public function testRemoverVendaOutraEmpresa()
    {

        $empresa = $this->criarEmpresa();

        $outroUsuario = $this->criarUsuario($empresa);
        $this->actingAs($outroUsuario);

        $addVenda = new AddVendaTransaction($this->usuario->id, $this->valorTotal, $this->itens, $this->metodosVenda, $this->valorDesconto, $this->cliente->id);
        $addVenda->execute();

        $venda = Venda::first();

        $excecao = null;

        try
        {
            $removeVenda = new RemoverVendaTransaction($venda->id);
            $removeVenda->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }
        
        $this->assertNotNull($excecao, 'Exceção não jogada');

    }
    
}