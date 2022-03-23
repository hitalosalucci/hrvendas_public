<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoteProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote_produto', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->double('valor_custo', 12, 2);
            $table->foreignId('produto_id');
            $table->foreignId('lote_id');

            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('lote_id')->references('id')->on('lotes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lote_produto');
    }
}
