<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoteProduto extends Model
{
    public $table = 'lote_produto';
    public $timestamps = false;

    public static function construir(Produto $produto, int $quantidade, float $valorCusto) : LoteProduto
    {
        $loteProduto = new LoteProduto();
        $loteProduto->quantidade = $quantidade;
        $loteProduto->valor_custo = $valorCusto;
        $loteProduto->produto_id = $produto->id;

        return $loteProduto;
    }
}
