<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Transactions\Marca\AddMarcaTransaction;
use Tests\TestCase;
use App\Model\Marca;
use App\Transactions\Marca\AlterarMarcaTransactiion;
use App\Transactions\Marca\AlterarMarcaTransaction;
use App\Transactions\Marca\RemoverMarcaTransaction;
use Illuminate\Auth\Access\AuthorizationException;

class MarcaUnitTest extends TestCase
{
    private $nome = 'Marca de teste';
    private $codigo = 'MATES';

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = $this->criarEmpresa();
    }

    public function testCadastrarMarca()
    {   
        
        $transacao = new AddMarcaTransaction($this->nome, $this->empresa->id, $this->codigo);
        $transacao->execute();

        $marca = Marca::first();

        $this->assertNotNull($marca, 'Marca não cadastrada');
        $this->assertEquals($this->nome, $marca->nome, 'Nome incorreto');
        $this->assertEquals($this->codigo, $marca->codigo, 'Código incorreto');
        $this->assertEquals($this->empresa, $marca->empresa, 'Empresa incorreta');
    } 

    public function testCadastrarMarcaSemOpcionais()
    {
        $transacao = new AddMarcaTransaction($this->nome, $this->empresa->id);
        $transacao->execute();

        $marca = Marca::first();

        $this->assertNotNull($marca, 'Marca não cadastrada');
        $this->assertEquals($this->nome, $marca->nome, 'Nome incorreto');
        $this->assertEquals($marca->codigo, null, 'Código incorreto');
        $this->assertEquals($this->empresa, $marca->empresa, 'Empresa incorreta');
    }

    public function testCadastrarMarcaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AddMarcaTransaction('', 0, '');
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'empresa', 'inexistente');
    }

    public function testAlterarMarca()
    {
        $marca = $this->criarMarca($this->empresa);
        $usuario = $this->criarUsuario($this->empresa);

        $this->actingAs($usuario);

        $nome = 'Apple';
        $codigo = 'APL';

        $transacao = new AlterarMarcaTransaction($marca->id, $nome, $codigo);
        $transacao->execute();

        $marca->refresh();

        $this->assertEquals($nome, $marca->nome);
        $this->assertEquals($codigo, $marca->codigo);

    }

    public function testAlterarMarcaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AlterarMarcaTransaction(0, '');
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'marca', 'inexistente');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');

    }

    public function testAlterarMarcaOutraEmpresa()
    {   
        $marca = $this->criarMarca($this->empresa);
        
        $outraEmpresa = $this->criarEmpresa();
        $outroUsuario = $this->criarUsuario($outraEmpresa);

        $this->actingAs($outroUsuario);

        $nome = 'Apple';
        $codigo = 'APL';

        $excecao = null;
        
        try
        {
            $transacao = new AlterarMarcaTransaction($marca->id, $nome, $codigo);
            $transacao->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');

    }

    public function testRemoverMarca()
    {
        $marca = $this->criarMarca($this->empresa);
        $usuario = $this->criarUsuario($this->empresa);

        $idMarca = $marca->id;

        $this->actingAs($usuario);

        $transacao = new RemoverMarcaTransaction($marca->id);
        $transacao->execute();

        $marca = Marca::find($idMarca);

        $this->assertNull($marca, 'Categoria de produto não foi removido');

    }

    public function testRemoverMarcaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new RemoverMarcaTransaction(0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'objeto', 'inexistente');

    }

    public function testRemoverMarcaOutraEmpresa()
    {   
        $marca = $this->criarMarca($this->empresa);
        
        $outraEmpresa = $this->criarEmpresa();
        $outroUsuario = $this->criarUsuario($outraEmpresa);

        $this->actingAs($outroUsuario);

        $excecao = null;
        
        try
        {
            $transacao = new RemoverMarcaTransaction($marca->id);
            $transacao->execute();
        }
        catch(AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');

    }

}
