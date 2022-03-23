<?php

namespace App\Transactions\Marca;

use App\Model\Marca;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class RemoverMarcaTransaction implements Transaction
{

    use VerificaEmpresa;
    use ValidaDados;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function execute()
    {
        $marca = Marca::find($this->id);

        $this->validarDados();

        $this->autorizarMesmaEmpresa($marca);

        $marca->delete();

    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new Marca(), $this->id, 'objeto', $erros);
    }

}

?>