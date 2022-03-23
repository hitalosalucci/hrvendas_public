<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaFuncionalidadeSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_funcionalidade_sistema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id');
            $table->foreignId('funcionalidade_sistema_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('funcionalidade_sistema_id')->references('id')->on('funcionalidades_sistema');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa_funcionalidade_sistema');
    }
}
