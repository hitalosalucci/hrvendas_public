<?php

namespace App\Transactions\Produto;

use App\Model\Produto;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class RemoverProdutoTransaction implements Transaction
{

    use VerificaEmpresa;
    use ValidaDados;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function execute()
    {
        $produto = Produto::find($this->id);

        $this->validarDados();

        $this->autorizarMesmaEmpresa($produto);

        $produto->delete();

    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Produto(), $this->id, 'objeto', $erros);
    }

}

?>