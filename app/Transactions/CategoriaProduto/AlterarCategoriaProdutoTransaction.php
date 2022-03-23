<?php

namespace App\Transactions\CategoriaProduto;

use App\Model\CategoriaProduto;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;
use App\Transactions\Traits\VerificaEmpresa;

class AlterarCategoriaProdutoTransaction implements Transaction
{
    use ValidaDados;
    use VerificaEmpresa;

    public function __construct(int $id, string $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }

    public function execute()
    {

        $this->validarDados();
        
        $categoria = CategoriaProduto::find($this->id);
        
        $this->autorizarMesmaEmpresa($categoria);

        $categoria->nome = $this->nome;
        $categoria->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->objetoInexistente(new CategoriaProduto(), $this->id, 'categoria-produto', $erros);
    }
}

?>