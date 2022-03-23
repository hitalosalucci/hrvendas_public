<?php

namespace Tests\Unit\Venda;

use App\Model\ItemVenda;
use App\Model\MetodoPagamentoVenda;
use App\Model\Venda;
use App\Transactions\Venda\AddVendaTransaction;
use Tests\TestCase;

abstract class BaseVendaUnitTest extends TestCase
{

    protected $empresa;
    protected $categoriaProduto;
    protected $usuario;
    protected $cliente;

    protected function setUp() : void
    {
        parent::setUp();

        $this->empresa = $this->criarEmpresa();
        $this->enderecoCliente = $this->criarEndereco();
        $this->cliente = $this->criarCliente($this->empresa, $this->enderecoCliente);
        $this->usuario = $this->criarUsuario($this->empresa);
        $this->categoriaProduto = $this->criarCategoriaProduto($this->empresa);
        $this->marca = $this->criarMarca($this->empresa);
        $this->outraMarca = $this->criarMarca($this->empresa);
        $this->fornecedor = $this->criarFornecedor($this->empresa);
        $this->produtos = $this->criarProdutos();

    }

    protected function criarProdutos()
    {
        $produtos = [];
        
        for ($i=0; $i < mt_rand(1, 10); $i++)
            $produtos[] = $this->criarProduto($this->categoriaProduto, $this->marca);
        
        return $produtos;
    }

    protected function criarItensVenda($produtos)
    {
        $itens = [];

        for ($i=0; $i < count($produtos); $i++)
            $itens[] = ItemVenda::construir($produtos[$i], mt_rand(1, 5), mt_rand(100, 10000), mt_rand(25, 680));

        return $itens;
    }

    protected function criarMetodosDePagamento()
    {
        $metodos = [];

        for ($i=0; $i < mt_rand(1, 5); $i++)
            $metodos[] = $this->crarMetodoDePagamento();

        return $metodos;
    }

    protected function criarMetodosPagamentoVenda($metodos)
    {
        $metodosVenda = [];

        for ($i=0; $i < count($metodos); $i++)
            $metodosVenda[] = MetodoPagamentoVenda::construir($metodos[$i], mt_rand(10, 10000), mt_rand(10, 1025), mt_rand(10, 1085));

        return $metodosVenda;
    }

    protected function criarVenda($usuario)
    {
        $valorTotal = 18.00;
        $valorDesconto = 2;
        $valorTroco = 250;

        //criando itens e venda
        $itens = $this->criarItensVenda($this->produtos);
        $metodosPagamento = $this->criarMetodosDePagamento();
        $metodosVenda = $this->criarMetodosPagamentoVenda($metodosPagamento);

        $transacao = new AddVendaTransaction($usuario->id, $valorTotal, $itens, $metodosVenda, $valorDesconto, $valorTroco, $this->cliente->id);
        $transacao->execute();

        return [
            'venda' => Venda::where('usuario_id', $usuario->id)->latest()->first(),
            'valorTotal' => $valorTotal,
            'valorDesconto' => $valorDesconto,
            'valorTroco' => $valorTroco,
            'cliente' => $this->cliente,
            'itens' => $itens,
            'metodosPagamento' => $metodosPagamento,
            'metodosVenda' => $metodosVenda,
        ];
    }

}

?>