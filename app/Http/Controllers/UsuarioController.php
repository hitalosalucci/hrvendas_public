<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Transactions\Usuario\AlterarSenhaUsuarioTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function paginaAlterarSenha()
    {
        return view('usuario.alteracao_senha', ['titulo' => 'Alteraçaão de senha']);
    }

    public function alterarSenha(Request $request)
    {
        $senhaAntiga = $request->input('senha', '');
        $novaSenha = $request->input('nova-senha', '');
        $usuario = $request->user();

        if (Hash::check($senhaAntiga, $usuario->senha))
            return $this->executarTransacaoAlterarSenha($usuario->id, $novaSenha);
        else
        {
            return [
                'status' => 0,
                'erro' => 'Senha incorreta',
            ];
        }
    }

    private function executarTransacaoAlterarSenha($idUsuario, $novaSenha)
    {
        try
        {
            $alterarSenha = new AlterarSenhaUsuarioTransaction($idUsuario, $novaSenha);
            $alterarSenha->execute();

            return ['status' => 1];
        }
        catch (TransactionException $ex)
        {
            return [
                'status' => 0,
                'erro' => 'Senha vazia',
            ];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function verificarUsuarioLogado()
    {
        $usuarioLogado = Auth::check();

        if ($usuarioLogado)
            return 1;
        else
            return 0;
    }

    public function getUsuarioLogado()
    {
        $usuario = Auth::user();

        $informacoesUsuario = [
           'nome' => $usuario->nome,
           'login' => $usuario->login,
           'empresa_id' => $usuario->empresa_id,
           'data_cadastro' => $usuario->created_at,
        ];

        return $informacoesUsuario;
    }

    public function getListaJson()
    {
        $usuario = Auth::user();

        $empresa = $usuario->empresa;

        //echo $empresa->endereco();

        $usuariosEmpresa = $empresa->usuarios()->get();

        $listaUsuarios = [];
        
        foreach ($usuariosEmpresa as $usuario)
        {

            $listaUsuarios[] = [
                'id' => $usuario->id,
                'nome' => $usuario->nome,
            ];
        }

        return response()->json($listaUsuarios);
    }
}
