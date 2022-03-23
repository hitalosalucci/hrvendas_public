<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Model\Empresa;
use App\Model\Marca;
use App\Transactions\Marca\AddMarcaTransaction;
use App\Transactions\Marca\AlterarMarcaTransaction;
use App\Transactions\Marca\RemoverMarcaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarcaController extends Controller
{

    public function index()
    {
        return view('marca.marca', ['titulo' => 'Gerenciar Marcas']);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        try
        {
            $resposta = $this->salvar($request);
        }
        catch (TransactionException $ex)
        {
            $resposta = [
                'status' => 0,
                'erros' => $this->formatarErrosTransacao($ex->getErros()),
            ];
        }

        return $resposta;
    }

    private function salvar(Request $request)
    {
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $nome = $request->input('nome-marca', '');

        $addMarca = new AddMarcaTransaction($nome, $empresa->id);
        $addMarca->execute();

        return ['status' => 1];
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

    }

    public function update(Request $request, $id)
    {
        $nome = $request->input('nome-marca', '');
        
        $alterarMarca = new AlterarMarcaTransaction($id, $nome);
        $alterarMarca->execute();

        return ['status' => 1];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $removeMarca = new RemoverMarcaTransaction($id);
            $removeMarca->execute();

            $resposta = ['status' => 1];
        }
        catch (TransactionException $ex)
        {
            $resposta = [
                'status' => 0,
                'erros' => $this->formatarErrosTransacao($ex->getErros()),
            ];
        }

        return $resposta;
    }

    public function getListaJson()
    {
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $marcas = $empresa->marcas()->orderBy('created_at')->get();

        $marcasArray = [];

        foreach ($marcas as $marca)
        {
            $marcasArray[] = [
                'id' => $marca->id,
                'nome' => $marca->nome,
            ];
        }

        return response()->json($marcasArray);
    }
}
