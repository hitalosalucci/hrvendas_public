<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Model\Empresa;
use App\Model\ItemVenda;
use App\Model\MetodoPagamento;
use App\Model\MetodoPagamentoVenda;
use App\Model\Produto;
use App\Model\Venda;
use App\Transactions\Venda\AddVendaTransaction;
use App\Transactions\Venda\AlterarVendaTransaction;
use App\Transactions\Venda\RemoverVendaTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;


class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Auth::user();
        // $empresa = $usuario->empresa;

        // $venda_ultima = Empresa::find($empresa->id);

        // if ($venda_ultima != null)
        //     $dataUltimaVenda = $venda_ultima->updated_at->format('Y-m-d');
        // else
        //     $dataUltimaVenda = date('Y-m-d');
        
        $dataUltimaVenda = date('Y-m-d');

        return view('venda.lista', ['titulo => Vendas realizadas'], compact('dataUltimaVenda', 'usuario'));
    }

    public function create()
    {
        $metodosPagamento = MetodoPagamento::all();

        return view('venda.formulario', ['titulo' => 'Realizar Venda'], compact('metodosPagamento'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $resposta = $this->salvarVenda($request);
            DB::commit();
        }
        catch(\Exception $ex)
        {
            $resposta = [
                'status' => 0,
                'erro' => $ex->getMessage(),
            ];

            DB::rollBack();
        }

        return $resposta;
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


    public function edit($id)
    {
        $venda = Venda::find($id);
        
        Gate::authorize('mesma-empresa', $venda->usuario);

        $metodosPagamento = MetodoPagamento::all();

        $itens = $venda->itens;

        $itensVendasArray = [];
        foreach ($itens as $item)
        {
            $itensVendasArray[] = [
                'idProduto' => $item->produto->id,
                'nomeProduto' => $item->produto->nome,
                'codigo_produto' => $item->produto->codigo,
                'valorQuantidade' => $item->quantidade,
                'valorTotalProduto' => ($item->preco * $item->quantidade) - $item->desconto,
                //'valor_pago' => $item->valor_pago,
                'valorDesconto' => $item->desconto,
            ];
        }

        $pagamentos = $venda->pagamentos;

        $pagamentosArray = [];
        foreach ($pagamentos as $pagamento)
        {
            $pagamentosArray[] = [
                'idMetodo' => $pagamento->metodo->id,
                'nomeMetodo' => $pagamento->metodo->nome,
                'valorPagamento' => ($pagamento->valor_pago + $pagamento->desconto),
                'trocoPara' => $pagamento->troco_para,
                'valorDesconto' => $pagamento->desconto,
                'valorTroco' => $pagamento->calcularValorTroco(),
                'valor_pago' => $pagamento->valor_pago,
            ];
        }

        $informacoesCliente = [
            'id' => $venda->cliente->id,
            'nome' => $venda->cliente->nome,
        ];

        $informacoesAlteracao = [
            'venda' => $venda->id,
            'cliente' => $informacoesCliente,
            'pagamentos' => $pagamentosArray,
            'itens' => $itensVendasArray,
        ];

        return view('venda.formulario', compact('informacoesAlteracao', 'metodosPagamento'));

    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try
        {
            $venda = Venda::find($id);

            $resposta = $this->salvarVenda($request, $venda);

            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();

            $resposta = [
                'status' => 0,
                'erro' => $ex->getMessage(),
            ];
        }

        return $resposta;
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
            $removeVenda = new RemoverVendaTransaction($id);
            $removeVenda->execute();

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

    private function construirItensVenda(array $itens)
    {
        $itensVenda = [];

        $itemVenda = new ItemVenda();

        foreach ($itens as $item)
        {
            $produto = Produto::find($item->produto);

            $item->desconto == '' || $item->desconto == null ? $desconto = null : $desconto = $item->desconto; //verificação se o desconto for string vazia

            $itensVenda[] = $itemVenda::construir($produto, $item->quantidade, $item->valorTotal, $desconto);
        }

        return $itensVenda;
    }

    private function construirItensPagamento(array $pagamentos)
    {
        $itensPagamento = [];

        $itemPagamento = new MetodoPagamentoVenda();

        foreach ($pagamentos as $item)
        {
            $metodo = MetodoPagamento::find($item->metodo);

            $item->valorDesconto == '' || $item->valorDesconto == null ? $desconto = null : $desconto = $item->valorDesconto; //verificação se o desconto for string vazia

            $item->trocoPara == '' || $item->trocoPara == null ? $trocoPara = null : $trocoPara = $item->trocoPara; //verificação se trocoPara for string vazia
            
            $itensPagamento[] = $itemPagamento::construir($metodo, $item->valorPagamento, $trocoPara, $desconto);
        }

        return $itensPagamento;
    }

    private function salvarVenda(Request $request, ?Venda $venda = null) : array
    {
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $idCliente = $request->input('idCliente') != '' || 0  ? $request->input('idCliente') : null;
        $itens = json_decode($request->input('produtos'));
        $pagamentos = json_decode($request->input('pagamentos'));
        $valorTotal = $request->input('valorTotal');
        $valorDescontos = $request->input('valorDescontos');
        //$valorTrocos = $request->input('valorTrocos');

        if (!is_array($itens))
            throw new Exception('Nenhum produto adicionado');
        
        if (!is_array($pagamentos))
            throw new Exception('Nenhum pagamento adicionado');

        $usuario = $request->user();
        
        $itensVenda = $this->construirItensVenda($itens);
        $itensPagamento = $this->construirItensPagamento($pagamentos);

        //montar a transaction e executar
        if(!is_null($venda))
            $transacao = new AlterarVendaTransaction($venda->id, $usuario->id, $valorTotal, $itensVenda, $itensPagamento, $valorDescontos, $idCliente);
        else
            $transacao = new AddVendaTransaction($usuario->id, $valorTotal, $itensVenda, $itensPagamento, $valorDescontos, $idCliente);

        $transacao->execute();

        $venda = Empresa::find($empresa->id)->vendas()->where('valor_total', $valorTotal)->latest()->first();

        return [
            'status' => 1,
            'numero_venda' => $venda->id,
        ];
        
        
    }

    public function imprimirCupomVenda($id)
    {
        $venda = Venda::find($id);
        
        if (!isset($venda))
            return view('errors.404', ['titulo' => 'Página inexistente']);

        Gate::authorize('mesma-empresa', $venda->usuario);

        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        if (View::exists('venda.impressao_empresas.empresa_'.$empresa->id))
            $view_impressao = 'venda.impressao_empresas.empresa_'.$empresa->id;
        else
            $view_impressao = 'venda.impressao_empresas.impressao_padrao'; 

        return view($view_impressao, compact('empresa', 'venda'));

    }

    public function getListaJson(Request $request)
    {
        $usuario = Auth::user();

        $dataPesquisa = $request->input('data');
        $empresa = $usuario->empresa;

        $vendas = $empresa->vendas()->whereDate('vendas.updated_at', $dataPesquisa)->orderBy('updated_at', 'desc')->get();

        $vendasArray = [];

        foreach ($vendas as $venda)
        {
            
            $itensVendasArray = [];
            foreach ($venda->itens as $item)
            {
                $itensVendasArray[] = [
                    'produto' => $item->produto->nome,
                    'codigo_produto' => $item->produto->codigo,
                    'quantidade' => $item->quantidade,
                    'preco' => $item->preco,
                    'valor_pago' => $item->valor_pago,
                    'desconto' => $item->desconto,
                ];
            }


            $pagamentosArray = [];
            foreach ($venda->pagamentos as $pagamento)
            {
                $pagamentosArray[] = [
                    'metodo_pagamento' => $pagamento->metodo->nome,
                    'valor_pago' => $pagamento->valor_pago,
                    'troco_para' => $pagamento->troco_para,
                    'desconto' => $pagamento->desconto,
                ];
            }


            $vendasArray[] = [
                'id_venda' => $venda->id,
                'cliente' => $venda->cliente->nome,
                'usuario_id' => $venda->usuario_id,
                'usuario' => $venda->usuario->nome,
                'valor_troco' => $venda->valor_troco,
                'valor_desconto' => $venda->valor_desconto,
                'data_criacao' => $venda->created_at->format('d/m/Y H:i'),
                'data_update' => $venda->updated_at->format('d/m/Y H:i'), 
                'itens' => $itensVendasArray,
                'pagamentos' => $pagamentosArray,
                'quantidade_itens' => $venda->calcularQuantidadeItensTotal(),
                'valor_total' => $venda->calcularValoresPagamentosTotal() 
            ];
        
        }

        return response()->json($vendasArray);

    }
}
