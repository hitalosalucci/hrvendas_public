<?php

namespace App\Transactions\Produto;

use App\Model\CategoriaProduto;
use App\Model\Produto;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class AlterarProdutoTransaction implements Transaction
{

    use VerificaEmpresa;
    use ValidaDados;

    public function __construct(int $id, string $nome, float $preco, string $unidade, int $idCategoria, string $codigoProduto = null, string $codigoBarras = null, string $descricao = null, int $idMarca = null)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->unidade = $unidade;
        $this->idCategoria = $idCategoria;
        $this->codigoProduto = $codigoProduto;
        $this->codigoBarras = $codigoBarras;
        $this->descricao = $descricao;        
        $this->idMarca = $idMarca;
    }

    public function execute()
    {
        $this->validarDados();

        $produto = Produto::find($this->id);

        $this->autorizarMesmaEmpresa($produto);

        $produto->nome = $this->nome;
        $produto->preco = $this->preco;
        $produto->unidade_preco = $this->unidade;
        $produto->categoria_produto_id = $this->idCategoria;
        $produto->codigo_produto = $this->codigoProduto;
        $produto->codigo_barras = $this->codigoBarras;
        $produto->descricao = $this->descricao;        
        $produto->marca_id = $this->idMarca;

        $produto->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Produto(), $this->id, 'produto', $erros);
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->textoVazio($this->unidade, 'unidade', $erros);
        $validador->numeroNegativo($this->preco, 'preco', $erros);
        $validador->objetoInexistente(new CategoriaProduto(), $this->idCategoria, 'categoria', $erros);
    }
}

?>