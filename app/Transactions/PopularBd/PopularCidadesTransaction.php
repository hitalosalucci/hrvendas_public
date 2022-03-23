<?php

namespace App\Transactions\PopularBd;

use App\Model\Endereco\Estado;
use App\Transactions\Cidade\AddCidadeTransaction;
use App\Transactions\Transaction;

class PopularCidadesTransaction implements Transaction
{
    
    public function execute()
    {
        $estadoEs = Estado::where('sigla', 'ES')->first();

        $idEs = $estadoEs->id;
        
        $transacoes = [
            new AddCidadeTransaction('Jerônimo Monteiro', $idEs),
            new AddCidadeTransaction('Alegre', $idEs),
        ];

        foreach ($transacoes as $t)
            $t->execute();
    }
    
}

?>