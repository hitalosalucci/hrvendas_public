<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Produto;
use App\Transactions\Produto\AddProdutoTransaction;
use App\Transactions\Produto\AlterarProdutoTransaction;
use App\Transactions\Produto\RemoverProdutoTransaction;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;

class ProdutoUnitTest extends TestCase
{
    private $nome = 'Produto de teste';
    private $codigoProduto = '00129';
    private $codigoBarras = '4234234249087020234239837682762334553453';
    private $preco = 17.89;
    private $unidade = 'UN';
    private $descricao = 'Descrição de teste para testar essa texto de descrição do produto de teste. Testando ok';

    public function testAdicionarProduto()
    {
        $empresa = $this->criarEmpresa();
        $categoriaProduto = $this->criarCategoriaProduto($empresa);
        $marca = $this->criarMarca($empresa);

        $transacao = new AddProdutoTransaction($this->nome, $this->preco, $this->unidade, $categoriaProduto->id, $this->codigoProduto, $this->codigoBarras, $this->descricao, $marca->id);
        $transacao->execute();

        $produto = Produto::first();

        $this->assertNotNull($produto, 'Produto inexistente');
        $this->assertEquals($this->nome, $produto->nome, 'Nome incorreto');
        $this->assertEquals($this->preco, $produto->preco, 'Preco incorreto');
        $this->assertEquals($this->unidade, $produto->unidade_preco, 'Unidade incorreto');
        $this->assertEquals($categoriaProduto, $produto->categoria_produto, 'Categoria incorreta');
        $this->assertEquals($this->descricao, $produto->descricao, 'Descrição incorreta');                
        $this->assertEquals($this->codigoBarras, $produto->codigo_barras, 'Código de barras incorreto');
        $this->assertEquals($this->codigoProduto, $produto->codigo_produto, 'Código do produto incorreto');
        $this->assertEquals($marca, $produto->marca, 'Marca incorreta');
        $this->assertEquals($empresa, $produto->empresa);
    }

    public function testAdicionarProdutoSemOpcionais()
    {
        $empresa = $this->criarEmpresa();
        $categoriaProduto = $this->criarCategoriaProduto($empresa);

        $transacao = new AddProdutoTransaction($this->nome, $this->preco, $this->unidade, $categoriaProduto->id);
        $transacao->execute();

        $produto = Produto::first();

        $this->assertNotNull($produto, 'Produto inexistente');
        $this->assertEquals($this->nome, $produto->nome, 'Nome incorreto');
        $this->assertEquals($this->preco, $produto->preco, 'Preco incorreto');
        $this->assertEquals($this->unidade, $produto->unidade_preco, 'Unidade incorreta');
        $this->assertEquals($categoriaProduto, $produto->categoria_produto, 'Categoria incorreta');
        $this->assertEquals($produto->codigo_produto, null, 'Código do produto incorreto');                        
        $this->assertEquals($produto->codigo_barras, null, 'Código de barras incorreta');        
        $this->assertEquals($produto->descricao, null, 'Descrição incorreta');        
        $this->assertEquals($produto->marca->nome, 'Sem marca', 'Marca incorreta');
        $this->assertEquals($empresa->nome, $produto->empresa->nome);
    }

    public function testAdicionarProdutoInvalido()
    {
        $empresa = $this->criarEmpresa();
        $categoriaProduto = $this->criarCategoriaProduto($empresa);

        $excecao = null;
        try
        {
            $transacao = new AddProdutoTransaction('',-21, '', 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'preco', 'negativo');
        $this->assertErroTransacao($excecao, 'unidade', 'vazio');
        $this->assertErroTransacao($excecao, 'categoria', 'inexistente');
    }

    public function testAlterarProduto()
    {
        $empresa = $this->criarEmpresa();
        $categoriaProduto = $this->criarCategoriaProduto($empresa);
        $outraCategoriaProduto = $this->criarCategoriaProduto($empresa);

        $marca = $this->criarMarca($empresa);

        $produto = $this->criarProduto($categoriaProduto, $marca);
        $descricao = 'sadasdasdasdaasdda asdasdww asd';
        $unidade = 'Kg';
        
        $usuario = $this->criarUsuario($empresa);
        $this->actingAs($usuario);

        $transacao = new AlterarProdutoTransaction($produto->id, $this->nome, $this->preco, $unidade, $outraCategoriaProduto->id, $this->codigoProduto, $this->codigoBarras, $descricao, $marca->id);
        $transacao->execute();

        $produto->refresh();

        $this->assertEquals($this->nome, $produto->nome);
        $this->assertEquals($this->preco, $produto->preco);
        $this->assertEquals($unidade, $produto->unidade_preco);
        $this->assertEquals($descricao, $produto->descricao);
        $this->assertEquals($outraCategoriaProduto->nome, $produto->categoria_produto->nome);
    }

    public function testAlterarProdutoInvalido()
    {
        
        $excecao = null;

        try
        {
            $transacao = new AlterarProdutoTransaction(0, '', -56, '', 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'produto', 'inexistente');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'preco', 'negativo');
        $this->assertErroTransacao($excecao, 'categoria', 'inexistente');
    }

    public function testAlterarProdutoOutraEmpresa()
    {
        $empresa = $this->criarEmpresa();
        $outraEmpresa = $this->criarEmpresa();

        $categoriaProduto = $this->criarCategoriaProduto($empresa);
        $outraCategoriaProduto = $this->criarCategoriaProduto($empresa);

        $marca = $this->criarMarca($empresa);

        $produto = $this->criarProduto($categoriaProduto, $marca);
        $descricao = 'sadasdasdasdaasdda asdasdww asd';
        
        $outroUsuario = $this->criarUsuario($outraEmpresa);
        $this->actingAs($outroUsuario);

        $excecao = null;

        try
        {
            $transacao = new AlterarProdutoTransaction($produto->id, $this->nome, $this->preco, $this->unidade, $outraCategoriaProduto->id, $this->codigoProduto, $this->codigoBarras, $descricao, $marca->id);
            $transacao->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        
    }

    public function testRemoverProduto()
    {
        $empresa = $this->criarEmpresa();
        $categoriaProduto = $this->criarCategoriaProduto($empresa);
        $outraCategoriaProduto = $this->criarCategoriaProduto($empresa);

        $marca = $this->criarMarca($empresa);

        $produto = $this->criarProduto($categoriaProduto, $marca);
        $idProduto = $produto->id;
        
        $usuario = $this->criarUsuario($empresa);
        $this->actingAs($usuario);

        $transacao = new RemoverProdutoTransaction($produto->id);
        $transacao->execute();

        $produto = Produto::find($idProduto);

        $this->assertNull($produto, 'Produto não foi removido');

        // $produto = Produto::withTrashed()->find($idProduto);

        // $this->assertNotNull($produto, 'Produto não está usando soft delete');

    }

    public function testRemoverProdutoInvalido()
    {
        $excecao = null;

        try
        {
            $t = new RemoverProdutoTransaction(0);
            $t->execute();
        } catch (TransactionException $ex) {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'objeto', 'inexistente');
    }

    public function testRemoverProdutoOutraEmpresa()
    {
        $empresa = $this->criarEmpresa();
        $outraEmpresa = $this->criarEmpresa();

        $categoriaProduto = $this->criarCategoriaProduto($empresa);

        $marca = $this->criarMarca($empresa);

        $produto = $this->criarProduto($categoriaProduto, $marca);
        
        $outroUsuario = $this->criarUsuario($outraEmpresa);
        $this->actingAs($outroUsuario);

        $excecao = null;

        try
        {
            $transacao = new RemoverProdutoTransaction($produto->id);
            $transacao->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
    }
    
}
