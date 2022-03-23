<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFornecedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('empresa_id');
            $table->string('cnpj')->nullable();
            $table->string('telefone')->nullable();
            $table->string('telefone2')->nullable();
            $table->string('descricao')->nullable();
            $table->foreignId('endereco_id')->nullable();
            $table->timestamps();

            $table->foreign('endereco_id')->references('id')->on('enderecos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fornecedores');
    }
}
