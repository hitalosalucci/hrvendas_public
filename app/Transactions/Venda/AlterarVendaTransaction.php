<?php

namespace App\Transactions\Venda;

use App\Model\Usuario\Usuario;
use App\Model\Venda;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class AlterarVendaTransaction implements Transaction
{
    use ValidaDados;
    use VerificaEmpresa;

    public function __construct(int $idVenda, int $idUsuario, float $valorTotal, array $itens, array $metodosPagamento, float $valorDesconto = null, int $idCliente = null)
    {
        $this->idVenda = $idVenda;
        $this->idUsuario = $idUsuario;
        $this->valorTotal = $valorTotal;
        $this->itens = $itens;
        $this->metodosPagamento = $metodosPagamento;
        $this->valorDesconto = $valorDesconto;
        //$this->valorTroco = $valorTroco;
        $this->idCliente = $idCliente; 
    }

    public function execute()
    {
        $this->validarDados();

        $venda = Venda::find($this->idVenda);

        $this->autorizarMesmaEmpresa($venda);

        $venda->usuario_id = $this->idUsuario;
        $venda->cliente_id = $this->idCliente;
        $venda->valor_total = $this->valorTotal;
        $venda->valor_desconto = $this->valorDesconto;
        //$venda->valor_troco = $this->valorTroco;
        
        $venda->save();


        $venda->itens()->delete();

        foreach ($this->itens as $item)
        {
            $item->venda_id = $venda->id;
            $item->save();
        }


        $venda->pagamentos()->delete();

        foreach ($this->metodosPagamento as $metodo)
        {
            $metodo->venda_id = $venda->id;
            $metodo->save();
        }

    }

    public function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Venda(), $this->idVenda, 'venda', $erros);
        $validador->objetoInexistente(new Usuario(), $this->idUsuario, 'usuario', $erros);
        $validador->numeroNegativo($this->valorTotal, 'valor_total', $erros);
        $validador->numeroNegativoOuNull($this->valorDesconto, 'valor_desconto', $erros);
        $validador->arrayVazia($this->itens, 'itens', $erros);
        $validador->arrayVazia($this->metodosPagamento, 'metodos_pagamento', $erros);
    }

}



?>