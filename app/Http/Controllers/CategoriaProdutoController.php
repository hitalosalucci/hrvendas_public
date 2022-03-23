<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Transactions\CategoriaProduto\AddCategoriaProdutoTransaction;
use App\Transactions\CategoriaProduto\AlterarCategoriaProdutoTransaction;
use App\Transactions\CategoriaProduto\RemoverCategoriaProdutoTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoriaProdutoController extends Controller
{
    
    use FormataErrosTransacoes;

    protected function getNomeCampo(): string
    {
        return 'categoria';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('categoria_produto.categoria_produto', ['titulo' => 'Gerenciar Categorias']);
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
        DB::beginTransaction();

        try
        {
            $resposta = $this->salvarCategoria($request);
            DB::commit();
        }
        catch (TransactionException $ex)
        {
            $resposta = [
                'status' => 0,
                'erros' => $this->formatarErrosTransacao($ex->getErros()),
            ];

            DB::rollBack();
        }
        
        return $resposta;
    }

    private function salvarCategoria(Request $request)
    {
        $usuario = Auth::user();
        $empresa = $usuario->empresa;
        
        $nome = $request->input('nome-categoria', '');

        $nome == null ? $nome = '' : $nome = $nome;

        $addCategoria = new AddCategoriaProdutoTransaction($nome, $empresa->id);
        $addCategoria->execute();

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
        $nome = $request->input('nome-categoria', '');
        
        $alterarMarca = new AlterarCategoriaProdutoTransaction($id, $nome);
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
            $removeCategoria = new RemoverCategoriaProdutoTransaction($id);
            $removeCategoria->execute();

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

        $categorias = $empresa->categorias()->orderBy('nome')->get();
        $categoriasArray = [];

        foreach ($categorias as $categoria)
        {
            $categoriasArray[] = [
                'id' => $categoria->id,
                'nome' => $categoria->nome,
            ];
        }

        return response()->json($categoriasArray);

    }


}
