<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    public function usuarios()
    {
        return $this->hasMany('App\Model\Usuario\Usuario');
    }

    public function vendas()
    {
        return $this->hasManyThrough('App\Model\Venda', 'App\Model\Usuario\Usuario');
    }

    public function endereco()
    {
        return $this->belongsTo('App\Model\Endereco\Endereco');
    }

    public function clientes()
    {
        return $this->hasMany('App\Model\Cliente');
    }

    public function fornecedor()
    {
        return $this->hasMany('App\Model\Fornecedor');
    }

    public function produtos()
    {
        return $this->hasManyThrough('App\Model\Produto', 'App\Model\CategoriaProduto');
    }

    public function categorias()
    {
        return $this->hasMany('App\Model\CategoriaProduto');
    }

    public function marcas()
    {
        return $this->hasMany('App\Model\Marca');
    }

    public function funcionalidades()
    {
        return $this->belongsToMany('App\Model\FuncionalidadeSistema');
    }

    public function verificarFuncionalidade($codigo)
    {        
        // $funcionalidadesEmpresa = $this->funcionalidades;

        // foreach($funcionalidadesEmpresa as $funcionalidade)
        // {
        //     if($codigo == $funcionalidade->codigo)
        //         return true;
        // }
        
        // return false;

        $countFuncionalidades = $this->funcionalidades()->where('codigo', $codigo)->count();
        
        return $countFuncionalidades > 0;
    }
}
