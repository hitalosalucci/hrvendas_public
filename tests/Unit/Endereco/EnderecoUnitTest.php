<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Endereco\Endereco;
use App\Transactions\Endereco\AddEnderecoTransaction;
use Tests\TestCase;

class EnderecoUnitTest extends TestCase
{
    private $bairro = 'Bairro de teste';
    private $rua = 'Rua de teste';
    private $referencia = 'Perto do lugar de teste';
    private $numero = 12;

    protected function setUp(): void
    {
        parent::setUp();

        $this->estado = $this->criarEstado();
        $this->cidade = $this->criarCidade($this->estado);
    }
    
    public function testAdicionarEndereco()
    {   

        $transacao = new AddEnderecoTransaction($this->bairro, $this->rua, $this->numero, $this->cidade->id, $this->referencia);
        $transacao->execute();

        $endereco = Endereco::first();

        $this->assertNotNull($endereco, 'Endereço inválido');
        $this->assertEquals($this->bairro, $endereco->bairro, 'Bairro incorreto');
        $this->assertEquals($this->rua, $endereco->rua, 'Rua incorreta');
        $this->assertEquals($this->numero, $endereco->numero, 'Número incorreto');
        $this->assertEquals($this->referencia, $endereco->referencia, 'Referencia incorreta');        
        $this->assertEquals($this->cidade, $endereco->cidade, 'Cidade incorreta');
    }

    public function testAdicionarEnderecoSemOpcionais()
    {

        $transacao = new AddEnderecoTransaction($this->bairro, $this->rua, $this->numero, $this->cidade->id);
        $transacao->execute();

        $endereco = Endereco::first();
        
        $this->assertNotNull($endereco, 'Endereço inválido');
        $this->assertEquals($this->bairro, $endereco->bairro, 'Bairro incorreto');
        $this->assertEquals($this->rua, $endereco->rua, 'Rua incorreta');
        $this->assertEquals($this->numero, $endereco->numero, 'Número incorreto');
        $this->assertEquals($endereco->referencia, null, 'Referencia com valor');      
        $this->assertEquals($this->cidade, $endereco->cidade, 'Cidade incorreta');
    }

    public function testAdicionarEnderecoInvalido()
    {
        $excecao = null;

        try
        {
            $transacao = new AddEnderecoTransaction('', '', -52, 0, '');
            $transacao->execute();
        }
        catch (TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'bairro', 'vazio');
        $this->assertErroTransacao($excecao, 'rua', 'vazio');
        $this->assertErroTransacao($excecao, 'cidade', 'inexistente');
    }
}
