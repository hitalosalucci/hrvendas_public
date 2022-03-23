<?php

namespace App\Transactions\PopularBd;

use App\Transactions\MetodoPagamento\AddMetodoPagamentoTransaction;
use App\Transactions\Transaction;

class PopularMetodosPagamentoTransaction implements Transaction
{
    public function execute()
    {
        $transacoes = [
            new AddMetodoPagamentoTransaction("Dinheiro"),
            new AddMetodoPagamentoTransaction("Cartão"),
            new AddMetodoPagamentoTransaction("PicPay"),
            new AddMetodoPagamentoTransaction("Pix"),
        ];

        foreach ($transacoes as $t )
            $t->execute();
    }
}

?>