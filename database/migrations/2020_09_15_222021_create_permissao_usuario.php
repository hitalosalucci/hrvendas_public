<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissaoUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissao_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permissao_id');            
            $table->unsignedBigInteger('usuario_id');
            
            $table->foreign('permissao_id')->references('id')->on('permissoes');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissao_usuario');
    }
}
