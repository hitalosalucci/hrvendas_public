<?php

namespace Tests\Unit;

use App\Exceptions\TransactionException;
use App\Model\Endereco\Estado;
use App\Transactions\Estado\AddEstadoTransaction;
use Tests\TestCase;

class EstadoUnitTest extends TestCase
{

    private $nome = 'Estado de teste';
    private $sigla = 'TE';
    
    public function testAdicionarEstado()
    {
        $transacao = new AddEstadoTransaction($this->nome, $this->sigla);
        $transacao->execute();

        $estado = Estado::first();

        $this->assertNotNull($estado, 'Estado inválido');
        $this->assertEquals($this->nome, $estado->nome, 'Nome incorreto');
        $this->assertEquals($this->sigla, $estado->sigla, 'Sigla incorreta');
    }

    public function testAdicionarEstadoInvalido()
    {
        $excecao = null;

        try
        {
            $transacao = new AddEstadoTransaction('', '');
            $transacao->execute();
        }
        catch (TransactionException $ex)
        {
            $excecao = $ex;
        }

        $this->assertNotNull($excecao, 'Exceção não jogada');
        $this->assertErroTransacao($excecao, 'nome', 'vazio');
        $this->assertErroTransacao($excecao, 'sigla', 'vazio');
    }

}
