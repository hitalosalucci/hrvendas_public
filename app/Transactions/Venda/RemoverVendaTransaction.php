<?php

namespace App\Transactions\Venda;

use App\Model\Venda;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class RemoverVendaTransaction implements Transaction
{

    use VerificaEmpresa;
    use ValidaDados;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function execute()
    {
        $venda = Venda::find($this->id);

        $this->validarDados();

        $this->autorizarMesmaEmpresa($venda);

        $venda->itens()->delete();
        $venda->pagamentos()->delete();

        $venda->delete();

    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Venda(), $this->id, 'objeto', $erros);
    }

}

?>