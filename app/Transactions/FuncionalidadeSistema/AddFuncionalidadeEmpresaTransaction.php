<?php 

namespace App\Transactions\FuncionalidadeSistema;

use App\Model\Empresa;
use App\Model\FuncionalidadeSistema;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\Transaction;
use App\Transactions\ValidadorTransacao;

class AddFuncionalidadeEmpresaTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(int $idFuncionalidade, int $idEmpresa)
    {
        $this->idFuncionalidade = $idFuncionalidade;
        $this->idEmpresa = $idEmpresa;
    }

    public function execute()
    {
        $this->validarDados();

        $empresa = Empresa::find($this->idEmpresa);
        
        $empresa->funcionalidades()->attach($this->idFuncionalidade);
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->objetoInexistente(new FuncionalidadeSistema(), $this->idFuncionalidade, 'funcionalidade', $erros);
        $validador->objetoInexistente(new Empresa(), $this->idEmpresa, 'empresa', $erros);    
    }

}   

?>