<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Permissao;
use App\Transactions\PermissaoUsuario\AddPermissaoTransaction;
use App\Transactions\PermissaoUsuario\AddPermissaoUsuarioTransaction;
use Tests\TestCase;

class PermissaoUnitTest extends TestCase
{
    private $nome = 'Acesso aos registros financeiros da empresa';
    private $codigo = 'FINAN';

    public function testAdicionarPermissaoUsuario()
    {
        $transacao = new AddPermissaoTransaction($this->nome, $this->codigo);
        $transacao->execute();

        $permissaoUsuario = Permissao::first();

        $this->assertNotNull($permissaoUsuario, 'Permissão do usuário é nula');
        $this->assertEquals($this->nome, $permissaoUsuario->nome, 'Nome incorreto');
        $this->assertEquals($this->codigo, $permissaoUsuario->codigo,'Código incorreto');
    }

    public function testAdicionarPermissaoUsuarioInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AddPermissaoTransaction('', '');
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

    public function testRelacionarPermissaoEmUsuario()
    {
        $empresa = $this->criarEmpresa();
        $usuario = $this->criarUsuario($empresa);

        $permissao = $this->criarPermissao();

        $transacao = new AddPermissaoUsuarioTransaction($permissao->id, $usuario->id);
        $transacao->execute();

        $permissoesUsuario = $usuario->permissoes;

        $this->assertNotNull($permissoesUsuario, 'Permissão não adicionada');
        $this->assertEquals($permissao->codigo, $permissoesUsuario[0]->codigo, 'Permissão usuário incorreta');
        $this->assertTrue($usuario->verificarPermissao($permissao->codigo));

    }

    public function testRelacionarPermissaoEmUsuarioInvalidos()
    {
        $excecao = null;

        try
        {
            $transacao = new AddPermissaoUsuarioTransaction(0, 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'permissao', 'inexistente');
        $this->assertErroTransacao($excecao, 'usuario', 'inexistente');
    }
}
