const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts([ //TEMPLATE PRINCIPAL
    'resources/js/jquery-3.4.1.min.js',
    'resources/js/jquery-mask.min.js',
    'resources/js/mascara-input.js',
    'resources/js/popper.min.js',
    'resources/js/bootstrap.min.js',
    'resources/js/bootstrap-select.min.js',
    'resources/js/animacao.js',
    'resources/js/helpers.js',
    'resources/js/sweetalert2.min.js',
    'resources/js/verificar-logado.js',

    ], 'public/js/compilado/principal.js')

.scripts([ //LISTAS
    'resources/js/cadastros/Pagina.js',
    'resources/js/cadastros/Lista.js',
    'resources/js/cadastros/Modal.js',
    'resources/js/cadastros/ModalApagar.js',
    'resources/js/cadastros/ModalFormulario.js',

    //VENDA
    'resources/js/cadastros/Venda/ListaVenda.js',
    'resources/js/cadastros/Venda/PaginaVenda.js',

    //PRODUTO
    'resources/js/cadastros/Produto/ListaProduto.js',
    'resources/js/cadastros/Produto/ModalFormularioProduto.js',

    //MARCA
    'resources/js/cadastros/Marca/ListaMarca.js',
    'resources/js/cadastros/Marca/ModalFormularioMarca.js',

    //CLIENTE
    'resources/js/cadastros/Cliente/ListaCliente.js',
    'resources/js/cadastros/Cliente/ModalFormularioCliente.js',

    //CATEGORIA PRODUTO
    'resources/js/cadastros/CategoriaProduto/ListaCategoria.js',
    'resources/js/cadastros/CategoriaProduto/ModalFormularioCategoria.js',    
], 'public/js/compilado/lista.js')

.scripts([
    'resources/js/paginas/vendas-produtos.js',
    'resources/js/paginas/vendas-cliente.js',
    'resources/js/paginas/vendas-pagamento.js',
    'resources/js/paginas/vendas-salvar.js',
    'resources/js/paginas/vendas-alterar.js',

], 'public/js/compilado/vendas.js')

.scripts([
    'resources/js/chart.js-2.8.0.js',
    'resources/js/paginas/relatorios.js',

], 'public/js/compilado/relatorios.js')

.scripts('resources/js/paginas/alteracao-senha.js', 'public/js/compilado/alteracao-senha.js')
.scripts('resources/js/paginas/login.js', 'public/js/compilado/login.js');

if (mix.inProduction())
    mix.version();