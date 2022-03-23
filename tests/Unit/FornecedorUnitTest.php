<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Fornecedor;
use App\Transactions\Fornecedor\AddFornecedorTransaction;
use Tests\TestCase;

class FornecedorUnitTest extends TestCase
{
    private $nome = 'Fornecedor de teste';
    private $cnpj = '3123122100012';
    private $telefone = '28998876543';
    private $telefone2 = '27885643621';
    private $descricao = 'Descrição para o fornecedor de teste que fornece produtos de teste';

    public function testCadastrarFornecedor()
    {
        
        $empresa = $this->criarEmpresa();
        $enderecoFornecedor = $this->criarEndereco();

        $transacao = new AddFornecedorTransaction($this->nome, $empresa->id, $this->cnpj, $this->telefone, $this->telefone2, $this->descricao, $enderecoFornecedor->id);
        $transacao->execute();

        $fornecedor = Fornecedor::first();

        $this->assertNotNull($fornecedor, 'Fornecedor não cadastrado');
        $this->assertEquals($this->nome, $fornecedor->nome, 'Nome do fornecedor incorreto');
        $this->assertEquals($empresa, $fornecedor->empresa, 'Empresa do fornecedor incorreta');        
        $this->assertEquals($this->cnpj, $fornecedor->cnpj, 'CNPJ do fornecedor incorreto');
        $this->assertEquals($this->telefone, $fornecedor->telefone, 'Telefone do fornecedor incorreto');
        $this->assertEquals($this->telefone2, $fornecedor->telefone2, 'Telefone 2 do fornecedor incorreto');
        $this->assertEquals($this->descricao, $fornecedor->descricao, 'Descricao do fornecedor incorreto');
        $this->assertEquals($enderecoFornecedor, $fornecedor->endereco, 'Endereco do Fornecedor incorreto');
    }
    
    public function testCadastrarFornecedorSemOpcionais()
    {
        $empresa = $this->criarEmpresa();

        $transacao = new AddFornecedorTransaction($this->nome, $empresa->id);
        $transacao->execute();

        $fornecedor = Fornecedor::first();

        $this->assertNotNull($fornecedor, 'Fornecedor não cadastrado');
        $this->assertEquals($this->nome, $fornecedor->nome, 'Nome do fornecedor incorreto');
        $this->assertEquals($empresa, $fornecedor->empresa, 'Empresa do fornecedor incorreta');        
        $this->assertEquals($fornecedor->cnpj, null, 'CNPJ do fornecedor incorreto');
        $this->assertEquals($fornecedor->telefone, null, 'Telefone do fornecedor incorreto');
        $this->assertEquals($fornecedor->telefone2, null, 'Telefone 2 do fornecedor incorreto');
        $this->assertEquals($fornecedor->descricao, null, 'Descricao do fornecedor incorreto');
        $this->assertEquals($fornecedor->endereco, null, 'Endereco do Fornecedor incorreto');
    }

    public function testCadastrarFornecedorInvalido()
    {
        $excecao = null;

        try
        {
            $transacao = new AddFornecedorTransaction('', 0, '', '', '', '', 0);
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
}
