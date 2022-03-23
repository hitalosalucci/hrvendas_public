<?php

namespace App\Transactions\CategoriaProduto;

use App\Model\CategoriaProduto;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Traits\VerificaEmpresa;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class RemoverCategoriaProdutoTransaction implements Transaction
{

    use VerificaEmpresa;
    use ValidaDados;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function execute()
    {
        $categoria = CategoriaProduto::find($this->id);

        $this->validarDados();

        $this->autorizarMesmaEmpresa($categoria);

        $categoria->delete();

    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new CategoriaProduto(), $this->id, 'objeto', $erros);
    }

}

?>