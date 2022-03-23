<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Empresa;
use App\Transactions\Empresa\AddEmpresaTransaction;
use Tests\TestCase;

class EmpresaUnitTest extends TestCase
{

    private $nome = 'Empresa de teste';
    private $nome_empresarial = 'Nome empresarial ME';
    private $cnpj = '111222333444';
    private $telefone = '(27)999999009';
    private $telefone2 = '(27)988123122'; 

    protected function setUp(): void
    {
        parent::setUp();

        $this->endereco = $this->criarEndereco();
    }

    public function testCadastrarEmpresa()
    {   

        $transacao = new AddEmpresaTransaction($this->nome, $this->nome_empresarial, $this->cnpj, $this->telefone, $this->endereco->id, $this->telefone2);
        $transacao->execute();

        $empresa = Empresa::first();

        $this->assertNotNull($empresa, 'Empresa não cadastrada');
        $this->assertEquals($this->nome, $empresa->nome, 'Nome incorreto');
        $this->assertEquals($this->nome_empresarial, $empresa->nome_empresarial, 'Nome empresarial incorreto');
        $this->assertEquals($this->cnpj, $empresa->cnpj, 'CNPJ');
        $this->assertEquals($this->telefone, $empresa->telefone, 'Telefone incorreto');
        $this->assertEquals($this->telefone2, $empresa->telefone2, 'Telefone 2 incorreto');
        $this->assertEquals($this->endereco, $empresa->endereco, 'Endereço incorreto');
    }

    public function testCadastrarEmpresaSemOpcionais()
    {
        $transacao = new AddEmpresaTransaction($this->nome, $this->nome_empresarial, $this->cnpj, $this->telefone, $this->endereco->id);
        $transacao->execute();

        $empresa = Empresa::first();

        $this->assertNotNull($empresa, 'Empresa não cadastrada');
        $this->assertEquals($this->nome, $empresa->nome, 'Nome incorreto');
        $this->assertEquals($this->nome_empresarial, $empresa->nome_empresarial, 'Nome empresarial incorreto');
        $this->assertEquals($this->cnpj, $empresa->cnpj, 'CNPJ');
        $this->assertEquals($this->telefone, $empresa->telefone, 'Telefone incorreto');
        $this->assertEquals($empresa->telefone2, null, 'Telefone 2 incorreto');
        $this->assertEquals($this->endereco, $empresa->endereco, 'Endereço incorreto');
    }

    public function testCadastrarEmpresaInvalida()
    {
        $excecao = null;

        try
        {
            $transacao = new AddEmpresaTransaction('', '', '', '', 0, '');
            $transacao->execute();
        }
        catch(TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'nome_empresarial', 'vazio');
        $this->assertErroTransacao($excecao, 'cnpj', 'vazio');
        $this->assertErroTransacao($excecao, 'telefone', 'vazio');
        $this->assertErroTransacao($excecao, 'endereco', 'inexistente');
    }

}

