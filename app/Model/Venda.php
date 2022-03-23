<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{

    public function usuario()
    {
        return $this->belongsTo('App\Model\Usuario\Usuario');
    }

    public function empresa()
    {
        return $this->usuario->empresa();
    }

    public function cliente()
    {
        return $this->belongsTo('App\Model\Cliente')->withDefault([
            'id' => 0,
            'nome' => 'Consumidor não identificado',
        ]);;
    }

    public function itens()
    {
        return $this->hasMany('App\Model\ItemVenda');
    }

    public function pagamentos()
    {
        return $this->hasMany('App\Model\MetodoPagamentoVenda');
    }

    public function calcularValorTotal()
    {
        $itens = $this->itens;
        
        $valorTotal = 0;

        foreach ($itens as $item)
        {
            $valorTotal += $item->preco * $item->quantidade;
        }

        return $valorTotal;
        
    }

    public function calcularValoresPagamentosTotal()
    {
        $pagamentos = $this->pagamentos;

        $pagamentosTotal = 0;

        foreach ($pagamentos as $pagamento)
        {
            $pagamentosTotal += $pagamento->valor_pago;
        }

        return $pagamentosTotal;
    }

    public function calcularQuantidadeItensTotal()
    {
        $itens = $this->itens;
        $quantidadeTotal = 0;

        foreach ($itens as $item)
            $quantidadeTotal += $item->quantidade;

        return $quantidadeTotal;
    }
    
}
