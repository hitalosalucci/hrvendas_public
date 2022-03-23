<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->double('preco', 12, 2);
            $table->unsignedBigInteger('categoria_produto_id');
            $table->string('codigo_produto')->unique()->nullable();
            $table->string('codigo_barras')->unique()->nullable();
            $table->string('descricao')->nullable();
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('categoria_produto_id')->references('id')->on('categorias_produtos');
            $table->foreign('marca_id')->references('id')->on('marcas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
