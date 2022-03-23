<?php

namespace App\Transactions\Lote;

use App\Model\Lote;
use App\Model\Usuario\Usuario;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;

class AddLoteTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(int $idUsuario, array $loteProduto, string $notaFiscal = null, string $descricao = null, int $idFornecedor = null)
    {
        $this->idUsuario = $idUsuario;
        $this->notaFiscal = $notaFiscal;
        $this->descricao = $descricao;
        $this->loteProduto = $loteProduto;
        $this->idFornecedor = $idFornecedor;
    }

    public function execute()
    {
        $this->validarDados();

        $lote = new Lote();
        $lote->usuario_id = $this->idUsuario;
        $lote->nota_fiscal = $this->notaFiscal;
        $lote->descricao = $this->descricao;
        $lote->fornecedor_id = $this->idFornecedor;

        $lote->save();

        foreach ($this->loteProduto as $lp)
        {
            $lp->lote_id = $lote->id;
            $lp->save();
        }        

    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Usuario(), $this->idUsuario, 'usuario', $erros);
        $validador->arrayVazia($this->loteProduto, 'itens_lote', $erros);
        $validador->textoVazioOuNull($this->notaFiscal, 'nota_fiscal', $erros);
        $validador->textoVazioOuNull($this->descricao, 'descricao', $erros);
    }

}



?>