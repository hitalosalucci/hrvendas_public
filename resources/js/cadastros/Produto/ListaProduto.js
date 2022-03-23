class ListaProduto extends Lista
{
    constructor(pagina)
    {
        super(pagina);

        this.listaCategorias = null;
        this.listaMarcas = null;

        this.carregarCategorias();
        this.carregarMarcas();
        this.inserirEventosPesquisa();
    }

    carregarCategorias()
    {
        var self = this;

        $.get('/categorias_produtos/lista', function (resposta)
        {
            self.listaCategorias = resposta;
            self.popularSelectsCategorias();
        });
    }

    popularSelectsCategorias()
    {
        var html = '';

        for (var i = 0; i < this.listaCategorias.length; i++)
            html += this.construirHtmlOptionCategoria(this.listaCategorias[i]);

        $('#selectCategorias').html('<option value="" disabled selected>Selecionar categoria</option>' + html);

        html = '<option value="0">Qualquer categoria</option>' + html;

        $('#selectFiltroCategoria').html(html);
    }

    construirHtmlOptionCategoria(categoria) 
    {
        return '<option value="'+categoria.id+'">'+categoria.nome+'</option>';
    }

    carregarMarcas()
    {
        var self = this;

        $.get('marcas/lista', function (resposta)
        {
            self.listaMarcas = resposta;
            self.popularSelectMarcas();
        });
    }

    popularSelectMarcas()
    {
        var html = '';

        for (var i = 0; i < this.listaMarcas.length; i++)
            html += this.construirHtmlOptionMarca(this.listaMarcas[i]);

        $('#selectMarcas').html('<option value="" disabled selected>Selecionar marca</option>' + html);

        html = '<option value="0">Qualquer marca</option>' + html;

        $('#selectFiltroMarca').html(html);

    }

    construirHtmlOptionMarca(marca)
    {
        return '<option value="'+marca.id+'">'+marca.nome+'</option>'; 
    }

    construirHtmlObjeto(objeto)
    {

        var codigoProduto = objeto.codigo_produto == null ? 'Sem código' : objeto.codigo_produto;

        var html = '';

        html += '<div class="list-group-item border-bottom">';
        html += '<div class="row">';
        html += '<div class="col-2 text-uppercase">'+codigoProduto+'</div>';
        html += '<div class="col">'+objeto.nome+'</div>';
        html += '<div class="col-2">'+formatarPreco(objeto.preco)+'<small>('+objeto.unidade+')</small>'+'</div>';
        html += '<div class="col-2">'+objeto.categoria+'</div>';
        html += '<div class="col-2">'+objeto.marca+'</div>';
        html += '<div class="col-auto">';
        html += '<button class="btn btn-icon-tema-primary btn-editar" data-item="'+objeto.id+'" title="Editar"><i class="fas fa-edit fa-lg"></i></button>';
        html += '<button class="btn btn-icon-tema-primary btn-apagar" data-item="'+objeto.id+'" title="Apagar"><i class="fas fa-trash-alt fa-lg"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        return html;
    }

    filtrar(objeto)
    {
        var textoPesquisa = $('#inputPesquisaProduto').val();
        var categoriaFiltro = $('#selectFiltroCategoria').val();
        var marcaFiltro = $('#selectFiltroMarca').val();

        return this.verificarObjetoPesquisado(objeto, textoPesquisa) && this.verificarCategoriaObjeto(objeto, categoriaFiltro) && this.verificarMarcaObjeto(objeto, marcaFiltro);
    }

    verificarObjetoPesquisado(objeto, textoPesquisa)
    {
        // verificar se código do produto é null
        var codigoProduto =  objeto.codigo_produto == null ? '' : objeto.codigo_produto;

        return textoPesquisa == '' || objeto.nome.toLowerCase().includes(textoPesquisa.toLowerCase()) || codigoProduto.toLowerCase().includes(textoPesquisa.toLowerCase());
    }

    verificarCategoriaObjeto(objeto, categoria)
    {
        return categoria == 0 || categoria == null || objeto.categoria_id == categoria;
    }

    verificarMarcaObjeto(objeto, marca)
    {
        return marca == 0 || marca == null || objeto.marca_id == marca;
    }

    inserirEventosPesquisa()
    {
        var self = this;

        $('#inputPesquisaProduto').on('keyup', function ()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });

        $('#selectFiltroCategoria').change(function ()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });

        $('#selectFiltroMarca').change(function ()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });

    }
}