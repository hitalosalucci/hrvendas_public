<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MetodoPagamentoVenda extends Model
{
    public $table = 'metodo_pagamento_venda';
    public $timestamps = false;

    public static function construir(MetodoPagamento $metodoPagamento, float $valorPago, float $trocoPara = null, float $desconto = null) : MetodoPagamentoVenda
    {
        $metodoVenda = new MetodoPagamentoVenda();

        $metodoVenda->metodo_pagamento_id = $metodoPagamento->id;
        $metodoVenda->valor_pago = $valorPago;
        $metodoVenda->troco_para = $trocoPara;
        $metodoVenda->desconto = $desconto;


        return $metodoVenda;
    }

    public function metodo()
    {
        return $this->belongsTo('App\Model\MetodoPagamento', 'metodo_pagamento_id');
    }

    public function calcularValorTroco()
    {
        $valorPago = $this->valor_pago;
        $trocoPara = $this->troco_para;

        if ($trocoPara != null)
            $troco = ($trocoPara - $valorPago);
        else
            $troco = 0;

        return $troco;
    }
}
