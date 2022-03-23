<?php

namespace App\Transactions\Usuario;

use App\Model\Empresa;
use App\Model\Usuario\Usuario;
use App\Transactions\Transaction;
use App\Transactions\Traits\ValidaDados;
use App\Transactions\ValidadorTransacao;
use Illuminate\Support\Facades\Hash;

class AddUsuarioTransaction implements Transaction
{
    use ValidaDados;

    public function __construct(string $nome, string $login, string $senha, int $idEmpresa, string $cpf = null)
    {
        $this->nome = $nome;
        $this->login = $login;
        $this->senha = $senha;
        $this->idEmpresa = $idEmpresa;
        $this->cpf = $cpf; 
    }

    public function execute()
    {
        $this->validarDados();

        $usuario = new Usuario();
        $usuario->nome = $this->nome;
        $usuario->login = $this->login;
        $usuario->senha = Hash::make($this->senha);
        $usuario->empresa_id = $this->idEmpresa;
        $usuario->cpf = $this->cpf;

        $usuario->save();
    }

    protected function validar(ValidadorTransacao $validador, array &$erros)
    {
        $validador->textoVazio($this->nome, 'nome', $erros);
        $validador->textoVazio($this->login, 'login', $erros);
        $validador->textoVazio($this->senha, 'senha', $erros);
        $validador->objetoInexistente(new Empresa(), $this->idEmpresa, 'empresa', $erros);
    }
    
}

?>