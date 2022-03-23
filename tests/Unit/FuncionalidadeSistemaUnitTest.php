<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\FuncionalidadeSistema;
use App\Transactions\FuncionalidadeSistema\AddFuncionalidadeEmpresaTransaction;
use App\Transactions\FuncionalidadeSistema\AddFuncionalidadeSistemaTransaction;
use Tests\TestCase;

class FuncionalidadeSistemaUnitTest extends TestCase
{
    private $nome = 'Funcionalidade de teste';
    private $codigo = 'FUNTESTE';

    public function testAdicionarFuncionalidadeDoSistema()
    {
        $transacao = new AddFuncionalidadeSistemaTransaction($this->nome, $this->codigo);
        $transacao->execute();

        $funcionalidade = FuncionalidadeSistema::first();

        $this->assertNotNull($funcionalidade, 'Funcionalidade é nula');
        $this->assertEquals($this->nome, $funcionalidade->nome, 'Nome inválido');
        $this->assertEquals($this->codigo, $funcionalidade->codigo, 'Código inválido');
    }

    public function testAdicionarFuncionalidadeDoSistemaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AddFuncionalidadeSistemaTransaction('', '');
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'codigo', 'vazio');
    }

    public function testRelacionarFuncionalidadeEmEmpresa()
    {
        $empresa = $this->criarEmpresa();

        $funcionalidade = $this->criarFuncionalidadeSistema();
        
        $transacao = new AddFuncionalidadeEmpresaTransaction($funcionalidade->id, $empresa->id);
        $transacao->execute();
        
        $funcionalidadesEmpresa = $empresa->funcionalidades; 

        $this->assertNotNull($funcionalidadesEmpresa);
        $this->assertEquals($funcionalidade->codigo, $funcionalidadesEmpresa[0]->codigo);
        $this->assertTrue($empresa->verificarFuncionalidade($funcionalidade->codigo));
    }

    public function testRelacionarFuncionalidadeEmEmpresaInvalidos()
    {
        $excecao = null;

        try
        {
            $transacao = new AddFuncionalidadeEmpresaTransaction(0, 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'funcionalidade', 'inexistente');
        $this->assertErroTransacao($excecao, 'empresa', 'inexistente');
    }

    /*public function testRemoverFuncionalidadeDeEmpresa()
    {
        $estado = $this->criarEstado();
        $cidade = $this->criarCidade($estado);
        $enderecoEmpresa = $this->criarEndereco($cidade);
        $empresa = $this->criarEmpresa($enderecoEmpresa);

        $funcionalidade = $this->criarFuncionalidadeSistema();

        $transacao = new AddFuncionalidadeEmpresaTransaction($funcionalidade->id, $empresa->id);
        $transacao->execute();


    }
    */
}
