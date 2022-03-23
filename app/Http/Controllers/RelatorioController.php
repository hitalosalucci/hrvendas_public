<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RelatorioController extends Controller
{
    public function index()
    {
        Gate::authorize('autorizacao-relatorios');
        
        $dataAtual = date('Y-m-d');
        $data7DiasAtras = date('Y-m-d', strtotime($dataAtual. ' - 7 days'));

        return view('relatorio.relatorio', ['titulo' => 'Relatórios'], compact('dataAtual', 'data7DiasAtras'));
    }

    public function getListaVendasJson(Request $request)
    {
        $usuario = Auth::user();

        $dataInicial = $request->input('dataInicial');
        $dataFinal = $request->input('dataFinal');

        $empresa = $usuario->empresa;

        $vendas = $empresa->vendas()->whereDate('vendas.updated_at', '>=', $dataInicial)->whereDate('vendas.updated_at', '<=',  $dataFinal)->orderBy('updated_at', 'desc')->get();

        $vendasArray = [];

        foreach ($vendas as $venda)
        {
            
            $itensVendasArray = [];
            foreach ($venda->itens as $item)
            {
                $itensVendasArray[] = [
                    'produto' => $item->produto->nome,
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
                //'cliente' => $venda->cliente->nome,
                //'usuario_id' => $venda->usuario_id,
                //'usuario' => $venda->usuario->nome,
                'valor_troco' => $venda->valor_troco,
                'valor_desconto' => $venda->valor_desconto,
                'data_criacao' => $venda->created_at->format('d/m/Y H:i'),
                'data_update' => $venda->updated_at->format('d/m/Y H:i'), 
                'data_update_venda' => $venda->updated_at->format('Y-m-d'), 
                'itens' => $itensVendasArray,
                'pagamentos' => $pagamentosArray,
                'quantidade_itens' => $venda->calcularQuantidadeItensTotal(),
                'valor_total' => $venda->calcularValoresPagamentosTotal()  
            ];
        
        }

        return response()->json($vendasArray);

    }

    public function getVendasPorDia(Request $request)
    {
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $dataInicial = $request->input('dataInicial');
        $dataFinal = $request->input('dataFinal');
        
        //$vendas = $empresa->vendas()->whereDate('vendas.updated_at', '>=', $dataInicial)->whereDate('vendas.updated_at', '<=',  $dataFinal)->orderBy('updated_at', 'desc')->get();
        
        //calcular Dias do intervalo
        $dataInicialDate = new DateTime($dataInicial);
        $dataFinalDate = new DateTime($dataFinal);

        $intervaloDatas = $dataFinalDate->diff($dataInicialDate);
        $intervaloDatasEmDias = $intervaloDatas->days;
        
        $dataIntervalo = $dataInicialDate;
        for ($i = 0; $i <= $intervaloDatasEmDias; $i++)
        {

            $vendas = $empresa->vendas()->whereDate('vendas.updated_at', $dataIntervalo)->orderBy('updated_at', 'desc')->get();

            $valorVendasArray = [];
            foreach ($vendas as $venda)
            {
                //echo $venda;
                $valorVendasArray[] = [
                    'valor_total' => $venda->calcularValoresPagamentosTotal(),
                    'quantidade_itens' => $venda->calcularQuantidadeItensTotal(),
                ];
            }

            $dataString = $dataIntervalo->format('d-m-Y');
            $vendasPorDia[$dataString] = $valorVendasArray;

            //soma mais um à data
            $dataIntervalo = $dataInicialDate->modify('+1 day');
        } 

        return response()->json($vendasPorDia);

    }
}
