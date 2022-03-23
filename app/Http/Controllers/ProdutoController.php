<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Model\Empresa;
use App\Transactions\Produto\AddProdutoTransaction;
use App\Transactions\Produto\AlterarProdutoTransaction;
use App\Transactions\Produto\RemoverProdutoTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdutoController extends Controller
{
    use FormataErrosTransacoes;

    protected function getNomeCampo(): string
    {
        return 'produto';
    }
   
    public function index()
    {
        return view('produto.produto', ['titulo' => 'Produtos']);
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

    public function store(Request $request)
    {
        try
        {
            $resposta = $this->salvar($request);
        }
        catch(TransactionException $ex)
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
        $nome = $request->input('nome-produto', '');
        $preco = $request->input('preco-produto', '');
        $unidade = $request->input('unidade-produto', '');
        $idCategoriaProduto = $request->input('categoria-produto', 0);
        $codigoProduto = $request->input('codigo-produto');
        $codigoBarras = $request->input('codigo_barras-produto');
        $descricao = $request->input('descricao-produto');
        $idMarca = $request->input('marca-produto');

        //tratamento das informações
        $nome == null ? $nome = '' : $nome = $nome;
        $preco == null ? $preco = 0 : $preco = $preco;
        $idCategoriaProduto == null ? $idCategoriaProduto = 0 : $idCategoriaProduto = $idCategoriaProduto;

        $addProduto = new AddProdutoTransaction($nome, $preco, $unidade, $idCategoriaProduto, $codigoProduto, $codigoBarras, $descricao, $idMarca);
        $addProduto->execute();

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

    public function update(Request $request, $id)
    {
        try
        {
            $resposta = $this->atualizarProduto($request, $id);
        }
        catch(TransactionException $ex)
        {
            $resposta = [
                'status' => 0,
                'erros' => $this->formatarErrosTransacao($ex->getErros()),
            ];
        }

        return $resposta;
    }

    protected function atualizarProduto(Request $request, $id)
    {
        $nome = $request->input('nome-produto', '');
        $preco = $request->input('preco-produto', '');
        $unidade = $request->input('unidade-produto', '');
        $idCategoriaProduto = $request->input('categoria-produto', 0);
        $codigoProduto = $request->input('codigo-produto');
        $codigoBarras = $request->input('codigo_barras-produto');
        $descricao = $request->input('descricao-produto');
        $idMarca = $request->input('marca-produto');

        //tratamento das informações
        $nome == null ? $nome = '' : $nome = $nome;
        $preco == null ? $preco = 0 : $preco = $preco;
        $idCategoriaProduto == null ? $idCategoriaProduto = 0 : $idCategoriaProduto = $idCategoriaProduto;

        $addProduto = new AlterarProdutoTransaction($id, $nome, $preco, $unidade, $idCategoriaProduto, $codigoProduto, $codigoBarras, $descricao, $idMarca);
        $addProduto->execute();

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
            $removeProduto = new RemoverProdutoTransaction($id);
            $removeProduto->execute();

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

    public function getListaPorCategoriaJson()
    {
        
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $categoriasProduto = $empresa->categorias()->get();

        foreach ($categoriasProduto as $categoria)
        {
            $produtosArray = [];
            $produtos = $categoria->produtos()->get(); 

            foreach ($produtos as $produto)
            {
                $produtosArray[] = [
                    'id' => $produto->id,
                    'nome' => $produto->nome,
                    'codigo_produto' => $produto->codigo_produto,
                    'codigo_barras' => $produto->codigo_barras,
                    'preco' => $produto->preco,
                    'unidade' => $produto->unidade_preco,
                    'descricao' => $produto->descricao,
                    'marca' => $produto->marca->nome,
                ];
            }
        
            $listaProdutos[$categoria->nome] = $produtosArray;
        }

        return response()->json($listaProdutos);

    }

    public function getListaJson()
    {
        
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $produtos = $empresa->produtos()->orderBy('categoria_produto_id')->get();

        $produtosArray = [];

        foreach ($produtos as $produto)
        {
            $categoria = $produto->categoria_produto;

            $marca = $produto->marca;
            
            $produtosArray[] = [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'unidade' => $produto->unidade_preco,
                'codigo_produto' => $produto->codigo_produto,
                'codigo_barras' => $produto->codigo_barras,
                'categoria' => $categoria->nome,
                'categoria_id' => $categoria->id,
                'marca' => $marca->nome,
                'marca_id' => $marca->id,
                'descricao' => $produto->descricao,
            ];
            
        }
        
        return response()->json($produtosArray);

    }

    public function verificarCodigoExistente(Request $request)
    {
        $codigoProduto = $request->input('codigo-produto');

        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $produtos = $empresa->produtos()->where('codigo_produto', $codigoProduto);
        
        $numProdutos = $produtos->count();

        if($numProdutos == 0)
            return false;
        else
            return true;
        
    }

    public function verificarCodigoBarrasExistente(Request $request)
    {
        $codigoBarras = $request->input('codigo_barras-produto');

        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $produtos = $empresa->produtos()->where('codigo_barras', $codigoBarras);
        
        $numProdutos = $produtos->count();

        if($numProdutos == 0)
            return false;
        else
            return true;

    }

}
