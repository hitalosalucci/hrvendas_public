<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Usuario\Usuario;
use App\Transactions\Usuario\AddUsuarioTransaction;
use Tests\TestCase;

class UsuarioUnitTest extends TestCase
{   
    private $nome = 'Florisbelo Flores';
    private $login = 'florisbelo';
    private $senha = '1234';
    private $cpf = '12345678910';

    public function testCadastrarUsuario()
    {
        $empresa = $this->criarEmpresa();

        $transacao = new AddUsuarioTransaction($this->nome, $this->login, $this->senha, $empresa->id, $this->cpf);
        $transacao->execute();

        $usuario = Usuario::first();
        
        $this->assertNotNull($usuario, 'Usuário é nulo');
        $this->assertEquals($this->nome, $usuario->nome, 'Nome incorreto');
        $this->assertEquals($empresa, $usuario->empresa,'Empresa incorreta');
        $this->assertTrue($usuario->validarSenha($this->senha), 'Senha do usuário incorreta');
    }

    public function testCadastrarUsuarioInvalido()
    {
        $excecao = null;
        
        try
        {
            $transacao = new AddUsuarioTransaction('', '', '', 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'login', 'vazio');
        $this->assertErroTransacao($excecao, 'senha', 'vazio');
        $this->assertErroTransacao($excecao, 'empresa', 'inexistente');
    }

}
