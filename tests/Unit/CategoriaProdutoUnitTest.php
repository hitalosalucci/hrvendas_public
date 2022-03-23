<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use Tests\TestCase;
use App\Model\CategoriaProduto;
use App\Transactions\CategoriaProduto\AddCategoriaProdutoTransaction;
use App\Transactions\CategoriaProduto\AlterarCategoriaProdutoTransaction;
use App\Transactions\CategoriaProduto\RemoverCategoriaProdutoTransaction;
use Illuminate\Auth\Access\AuthorizationException;

class CategoriaProdutoUnitTest extends TestCase
{
    private $nome = 'Categoria de teste';

    public function testCadastrarCategoria()
    {
        $empresa = $this->criarEmpresa();

        $transaction = new AddCategoriaProdutoTransaction($this->nome, $empresa->id);
        $transaction->execute();

        $categoria = CategoriaProduto::first();
        
        $this->assertNotNull($categoria);
        $this->assertEquals($this->nome, $categoria->nome);
        $this->assertEquals($empresa, $categoria->empresa);

    }

    public function testCadastrarCategoriaInvalida()
    {
        $excecao = null;
        
        try
        {
            $transacao = new AddCategoriaProdutoTransaction('', 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Excessão não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'empresa', 'inexistente');
    }

    public function testAlterarCategoria()
    {
        $empresa = $this->criarEmpresa();
        $usuario = $this->criarUsuario($empresa);
        $this->actingAs($usuario);

        $categoria = $this->criarCategoriaProduto($empresa);

        $nome = 'Categoria sendo alterada';

        $transacao = new AlterarCategoriaProdutoTransaction($categoria->id, $nome);
        $transacao->execute();

        $categoria->refresh();

        $this->assertEquals($nome, $categoria->nome);
    }

    public function testAlterarCategoriaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AlterarCategoriaProdutoTransaction(0, '');
            $transacao->execute();
        }
        catch (TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'categoria-produto', 'inexistente');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
    }

    public function testAlterarCategoriaOutraEmpresa()
    {
        $empresa = $this->criarEmpresa();
        $outraEmpresa = $this->criarEmpresa();

        $outroUsuario = $this->criarUsuario($outraEmpresa);
        $this->actingAs($outroUsuario);

        $categoria = $this->criarCategoriaProduto($empresa);

        $nome = 'Categoria sendo alterada';

        $excecao = null;

        try
        {
            $transacao = new AlterarCategoriaProdutoTransaction($categoria->id, $nome);
            $transacao->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');

    }

    public function testRemoverCategoria()
    {
        $empresa = $this->criarEmpresa();
        $usuario = $this->criarUsuario($empresa);
        $this->actingAs($usuario);

        $categoria = $this->criarCategoriaProduto($empresa);
        $idCategoria = $categoria->id;

        $nome = 'Categoria sendo alterada';

        $transacao = new RemoverCategoriaProdutoTransaction($categoria->id);
        $transacao->execute();

        $categoriaProduto = CategoriaProduto::find($idCategoria);

        $this->assertNull($categoriaProduto, 'Categoria de produto não foi removido');

    }

    public function testRemoverCategoriaInvalida()
    {
        $excecao = null;

        try
        {
            $removeCategoria = new RemoverCategoriaProdutoTransaction(0);
            $removeCategoria->execute();
        }
        catch (TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'objeto', 'inexistente');
    }

    public function testRemoverCategoriaOutraEmpresa()
    {
        $empresa = $this->criarEmpresa();
        $outraEmpresa = $this->criarEmpresa();

        $outroUsuario = $this->criarUsuario($outraEmpresa);
        $this->actingAs($outroUsuario);

        $categoria = $this->criarCategoriaProduto($empresa);

        $excecao = null;

        try
        {
            $transacao = new RemoverCategoriaProdutoTransaction($categoria->id);
            $transacao->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');

    }

    public function testGetProdutosDaCategoria()
    {
        $empresa = $this->criarEmpresa();
        $categoriaProduto = $this->criarCategoriaProduto($empresa);
        $marca = $this->criarMarca($empresa);

        $qtdeProdutos = 3;
        
        $produtosSalvos = [];

        for ($i = 0; $i < $qtdeProdutos; $i++)
            $produtosSalvos[] = $this->criarProduto($categoriaProduto, $marca);

        $produtosCategoria = $categoriaProduto->produtos;

        $this->assertNotNull($produtosCategoria);
        $this->assertCount($qtdeProdutos, $produtosCategoria);
        
        for ($i = 0; $i < $qtdeProdutos; $i++)
            $this->assertEquals($produtosSalvos[$i]->nome, $produtosCategoria[$i]->nome);
    }


}
