<?php

namespace Tests\Unit\Lote;

use App\Model\LoteProduto;
use Tests\TestCase;

abstract class BaseLoteUnitTest extends TestCase
{

    protected $empresa;
    protected $categoriaProduto;
    protected $usuario;
    protected $marca;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = $this->criarEmpresa();
        $this->enderecoCliente = $this->criarEndereco();
        $this->usuario = $this->criarUsuario($this->empresa);
        $this->categoriaProduto = $this->criarCategoriaProduto($this->empresa);
        $this->marca = $this->criarMarca($this->empresa);
        $this->outraMarca = $this->criarMarca($this->empresa);
        $this->fornecedor = $this->criarFornecedor($this->empresa);
    }

    protected function criarProdutos($quantidade)
    {
        $produtos = [];
        
        for ($i=0; $i < $quantidade; $i++)
            $produtos[] = $this->criarProduto($this->categoriaProduto, $this->marca);
        
        return $produtos;
    }

    protected function criarItensLote($produtos)
    {
        $itens = [];

        for ($i=0; $i < count($produtos); $i++)
            $itens[] = LoteProduto::construir($produtos[$i], mt_rand(1, 5), mt_rand(10, 1000));

        return $itens;

    }



}

?>