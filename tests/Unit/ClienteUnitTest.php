<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Cliente;
use App\Transactions\Cliente\AddClienteTransaction;
use App\Transactions\Cliente\AlterarClienteTransaction;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;

class ClienteUnitTest extends TestCase
{
     private $nome = 'Cliente de teste'; 
     private $telefone = '(28)999007700'; 
     private $telefone2 = '(28)999990000'; 
     private $dataNascimento = '2020-06-10'; 
     private $cpf = '12312312312'; 
     private $identidade = '3865421ES'; 
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = $this->criarEmpresa();
        $this->enderecoCliente = $this->criarEndereco();

    } 

    public function testCadastrarCliente()
    {

        $transacao = new AddClienteTransaction($this->nome, $this->empresa->id, $this->telefone, $this->telefone2, $this->dataNascimento, $this->cpf, $this->identidade, $this->enderecoCliente->id);
        $transacao->execute();

        $cliente = Cliente::first();

        $this->assertNotNull($cliente, 'Cliente não cadastrado');
        $this->assertEquals($this->nome, $cliente->nome, 'Nome incorreto');
        $this->assertEquals($this->empresa->nome, $cliente->empresa->nome, 'Empresa incorreta');
        $this->assertEquals($this->telefone, $cliente->telefone, 'Telefone incorreta');
        $this->assertEquals($this->telefone2, $cliente->telefone2, 'Telefone2 incorreta');        
        $this->assertEquals($this->dataNascimento, $cliente->data_nascimento, 'Data de Nascimento incorreta');
        $this->assertEquals($this->cpf, $cliente->cpf, 'CPF incorreta');
        $this->assertEquals($this->identidade, $cliente->identidade, 'Identidade incorreta');
        $this->assertEquals($this->enderecoCliente->bairro, $cliente->endereco->bairro, 'Endereço incorreta');        
    }

    public function testCadastrarClienteSemOpcionais()
    {
        $transacao = new AddClienteTransaction($this->nome, $this->empresa->id);
        $transacao->execute();

        $cliente = Cliente::first();

        $this->assertNotNull($cliente, 'Cliente não cadastrado');
        $this->assertEquals($this->nome, $cliente->nome, 'Nome incorreto');
        $this->assertEquals($this->empresa->nome, $cliente->empresa->nome, 'Empresa incorreta');
        $this->assertEquals($cliente->telefone, null, 'Telefone incorreto');
        $this->assertEquals($cliente->telefone2, null, 'Telefone2 incorreto');        
        $this->assertEquals($cliente->dataNascimento, null, 'Data de Nascimento incorreta');
        $this->assertEquals($cliente->cpf, null, 'CPF incorreto');
        $this->assertEquals($cliente->identidade, null, 'Identidade incorreta');
        $this->assertEquals($cliente->endereco->referencia, 'Sem referência', 'Endereço incorreto');
    }

    public function testCadastrarClienteInvalido()
    {
        $excecao = null;
        
        try
        {
            $transacao = new AddClienteTransaction('', 0, '', '', '', '', '', 0);
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

    public function testAlterarCliente()
    {
        $cliente = $this->criarCliente($this->empresa, $this->enderecoCliente);
        $usuario = $this->criarUsuario($this->empresa);
        $this->actingAs($usuario);

        $transacao = new AlterarClienteTransaction($cliente->id, $this->nome, $this->telefone, $this->telefone2, $this->dataNascimento, $this->cpf, $this->identidade, $this->enderecoCliente->id);
        $transacao->execute();

        $cliente->refresh();

        $this->assertEquals($this->nome, $cliente->nome, 'Nome incorreto');
        $this->assertEquals($this->empresa->nome, $cliente->empresa->nome, 'Empresa incorreta');
        $this->assertEquals($this->telefone, $cliente->telefone, 'Telefone incorreto');
        $this->assertEquals($this->telefone2, $cliente->telefone2, 'Telefone2 incorreto');        
        $this->assertEquals($this->dataNascimento, $cliente->data_nascimento, 'Data de Nascimento incorreta');
        $this->assertEquals($this->cpf, $cliente->cpf, 'CPF incorreto');
        $this->assertEquals($this->identidade, $cliente->identidade, 'Identidade incorreta');
        $this->assertEquals($this->enderecoCliente->bairro, $cliente->endereco->bairro, 'Endereço incorreto');        

    }

    public function testAlterarClienteInvalido()
    {
        $excecao = null;
        
        try
        {
            $transacao = new AlterarClienteTransaction(0, '', '', '', '', '', '', 0);
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Excessão não jogada');
        $this->assertErroTransacao($excecao, 'cliente', 'inexistente');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
    }

    public function testAlterarClienteOutraEmpresa()
    {
        $cliente = $this->criarCliente($this->empresa, $this->enderecoCliente);
        
        $outraEmpresa = $this->criarEmpresa();

        $usuario = $this->criarUsuario($outraEmpresa);

        $this->actingAs($usuario);

        $excecao = null;

        try
        {
            $transacao = new AlterarClienteTransaction($cliente->id, $this->nome, $this->telefone, $this->telefone2, $this->dataNascimento, $this->cpf, $this->identidade, $this->enderecoCliente->id);
            $transacao->execute();
        }
        catch (AuthorizationException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');

    }


}
