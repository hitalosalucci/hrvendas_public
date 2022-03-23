<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    public $table = 'itens_vendas';
    public $timestamps = false;

    public static function construir(Produto $produto, float $quantidade, float $valorPago, float $desconto = null) : ItemVenda
    {
        $item = new ItemVenda();
        $item->quantidade = $quantidade;
        $item->preco = $produto->preco;
        $item->unidade_preco = $produto->unidade_preco;
        $item->desconto = $desconto;
        $item->valor_pago = $valorPago;
        $item->produto_id = $produto->id;

        return $item;
    }

    public function produto()
    {
        //return $this->belongsTo('App\Model\Produto');
        return $this->belongsTo('App\Model\Produto')->withTrashed();
    }

    public function venda()
    {
        return $this->belongsTo('App\Model\Venda');
    }

}
