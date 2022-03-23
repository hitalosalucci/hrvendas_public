<?php

namespace App\Transactions\PopularBd;

use App\Transactions\Estado\AddEstadoTransaction;
use App\Transactions\Transaction;

class PopularEstadosTransaction implements Transaction
{
    public function execute()
    {
        $transacoes = [

            new AddEstadoTransaction('Espírito Santo', 'ES'),
            new AddEstadoTransaction('São Paulo', 'SP'),
            new AddEstadoTransaction('Rio de Janeiro', 'RJ'),
            new AddEstadoTransaction('Minas Gerais', 'MG'),
            new AddEstadoTransaction('Acre', 'AC'),
            new AddEstadoTransaction('Alagoas', 'AL'),
            new AddEstadoTransaction('Amapá', 'AP'),
            new AddEstadoTransaction('Amazonas', 'AM'),
            new AddEstadoTransaction('Bahia', 'BA'),
            new AddEstadoTransaction('Ceará', 'CE'),
            new AddEstadoTransaction('Distrito Federal', 'DF'),
            new AddEstadoTransaction('Goiás', 'GO'),
            new AddEstadoTransaction('Maranhão', 'MA'),
            new AddEstadoTransaction('Mato Grosso', 'MT'),
            new AddEstadoTransaction('Mato Grosso do Sul', 'MS'),
            new AddEstadoTransaction('Pará', 'PA'),
            new AddEstadoTransaction('Paraíba', 'PB'),
            new AddEstadoTransaction('Paraná', 'PR'),
            new AddEstadoTransaction('Pernambuco', 'PE'),
            new AddEstadoTransaction('Piauí', 'PI'),
            new AddEstadoTransaction('Rio Grande do Norte', 'RN'),
            new AddEstadoTransaction('Rio Grande do Sul', 'RS'),
            new AddEstadoTransaction('Rondônia', 'RO'),
            new AddEstadoTransaction('Roraima', 'RR'),
            new AddEstadoTransaction('Santa Catarina', 'SC'),
            new AddEstadoTransaction('Sergipe', 'SE'),
            new AddEstadoTransaction('Tocantins', 'TO')

        ];

        foreach ($transacoes as $t)
            $t->execute();
    }


}


?>