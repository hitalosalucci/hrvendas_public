<?php

namespace Tests\Unit\Lote;

use App\Exceptions\TransactionException;
use App\Model\Lote;
use App\Transactions\Lote\AddLoteTransaction;

class LoteUnitTest extends BaseLoteUnitTest
{
    public $notaFiscal = '31231231231231231221121234121';
    public $descricao = 'Nota fiscal ref à compra de produtos de teste para testar o teste';
    

    protected function setUp(): void
    {
        parent::setUp();

        $this->produtos = $this->criarProdutos(mt_rand(1, 10));
        $this->itensLote = $this->criarItensLote($this->produtos);
    }

    public function testAdicionarLote()
    {

        $transacao = new AddLoteTransaction($this->usuario->id, $this->itensLote, $this->notaFiscal, $this->descricao, $this->fornecedor->id);
        $transacao->execute();

        $lote = Lote::first();

        $this->assertNotNull($lote, 'Lote não adicionado');
        $this->assertEquals($this->notaFiscal, $lote->nota_fiscal, 'Nota fiscal inválida');
        $this->assertEquals($this->descricao, $lote->descricao, 'Descrição inválida');
        $this->assertEquals($this->usuario->nome, $lote->usuario->nome, 'Usuário incorreto');
        $this->assertCount(count($this->itensLote), $lote->itensLote, 'Quantidade de itens do lote incorreta');
        $this->assertEquals($this->fornecedor->nome, $lote->fornecedor->nome, 'Fornecedor incorreto');        
        $this->assertEquals($this->empresa->nome, $lote->usuario->empresa->nome, 'Empresa incorreta');                
    }

    public function testAdicionarLoteSemOpcionais()
    {

        $transacao = new AddLoteTransaction($this->usuario->id, $this->itensLote);
        $transacao->execute();

        $lote = Lote::first();

        $this->assertNotNull($lote, 'Lote não adicionado');
        $this->assertEquals($lote->nota_fiscal, null, 'Nota fiscal inválida');
        $this->assertEquals($lote->descricao, null, 'Descrição inválida');
        $this->assertEquals($this->usuario->nome, $lote->usuario->nome, 'Usuário incorreto');
        $this->assertCount(count($this->itensLote), $lote->itensLote, 'Quantidade de itens do lote incorreta');
        $this->assertEquals($lote->fornecedor, null, 'Fornecedor incorreto'); 
        $this->assertEquals($this->empresa->nome, $lote->usuario->empresa->nome, 'Empresa incorreto');
    }

    public function testAdicionarLoteInvalido()
    {
        $excecao = null;

        try
        {   
            $transacao = new AddLoteTransaction(0, [], '', '', 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'usuario', 'inexistente');
        $this->assertErroTransacao($excecao, 'itens_lote', 'vazio');
        $this->assertErroTransacao($excecao, 'nota_fiscal', 'vazio');
        $this->assertErroTransacao($excecao, 'descricao', 'vazio');
    }

}