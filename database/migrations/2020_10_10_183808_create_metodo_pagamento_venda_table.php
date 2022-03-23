<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetodoPagamentoVendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metodo_pagamento_venda', function (Blueprint $table) {
            $table->id();
            $table->double('valor_pago', 12, 2);
            $table->double('troco_para', 12, 2)->nullable();
            $table->double('desconto', 12, 2)->nullable();
            $table->foreignId('metodo_pagamento_id');
            $table->foreignId('venda_id');

            $table->foreign('metodo_pagamento_id')->references('id')->on('metodos_pagamentos');
            $table->foreign('venda_id')->references('id')->on('vendas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metodo_pagamento_venda');
    }
}
