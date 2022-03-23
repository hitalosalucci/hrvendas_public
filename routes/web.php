<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() 
{
    
    if(Auth::check())
        return redirect('vendas/realizar_venda');
    else 
        return redirect('login');    
});

Route::get('usuarios/verificar_usuario_logado', 'UsuarioController@verificarUsuarioLogado'); //verificar se usuário está logado

//middleware auth
Route::middleware('auth:web')->group(function()
{
    Route::get('produtos/lista_por_categoria', 'ProdutoController@getListaPorCategoriaJson');
    Route::get('produtos/lista', 'ProdutoController@getListaJson');
    Route::get('produtos/verificar-codigo', 'ProdutoController@verificarCodigoExistente');
    Route::get('produtos/verificar-codigo-barras', 'ProdutoController@verificarCodigoBarrasExistente');
    Route::get('estados/lista', 'EstadoController@getListaJson');
    Route::get('clientes/lista', 'ClienteController@getListaJson');
    Route::get('marcas/lista', 'MarcaController@getListaJson');
    Route::get('categorias_produtos/lista', 'CategoriaProdutoController@getListaJson');
    Route::get('usuarios/lista', 'UsuarioController@getListaJson');
    Route::get('usuarios/usuario_logado', 'UsuarioController@getUsuarioLogado');
    Route::get('usuarios/alterar_senha', 'UsuarioController@paginaAlterarSenha');
    Route::post('usuarios/alterar_senha', 'UsuarioController@alterarSenha');

    Route::get('relatorios', 'RelatorioController@index');
    Route::get('relatorios/lista_vendas', 'RelatorioController@getListaVendasJson');
    Route::get('relatorios/vendas_por_dia', 'RelatorioController@getVendasPorDia');

    Route::prefix('vendas')->group(function()
    {
        Route::get('realizar_venda', 'VendaController@create');
        Route::get('lista', 'VendaController@getListaJson');
        Route::get('{id}/imprimir', 'VendaController@imprimirCupomVenda');

    });


    // Resources
    Route::resource('vendas', 'VendaController');
    Route::resource('usuarios', 'UsuarioController');
    Route::resource('clientes', 'ClienteController');
    Route::resource('marcas', 'MarcaController');
    Route::resource('categorias_produtos', 'CategoriaProdutoController');
    Route::resource('produtos', 'ProdutoController');

});

//fim middleware auth

Route::get('login', 'LoginController@index')->name('login');
Route::post('login', 'LoginController@autenticar');
Route::get('sair', 'LoginController@sair');

