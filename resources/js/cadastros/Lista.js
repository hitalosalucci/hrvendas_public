class Lista
{
    constructor(pagina)
    {
        this.pagina = pagina;
        this.lista = $('#listaObjetos');
    }

    popularListaObjetos(objetos)
    {
        var html = '';

        for (var i = 0; i < objetos.length; i++)
        {
            var objeto = objetos[i];

            if (this.filtrar(objeto))
                html += this.construirHtmlObjeto(objeto);
        }

        this.lista.html(html);
        this.inserirEventosLista();
    }

    filtrar(objeto)
    {
        return true;
    }

    construirHtmlObjeto(objeto)
    {
        var html = '';

        html += '<div class="list-group-item border-bottom">';
        html += '<div class="row">';
        html += '<div class="col">'+objeto.nome+'</div>';
        html += '<div class="col-auto">';
        html += '<button class="btn btn-icon-tema-primary btn-editar" data-item="'+objeto.id+'" title="Editar"><i class="fas fa-edit fa-lg"></i></button>';
        html += '<button class="btn btn-icon-tema-primary btn-apagar" data-item="'+objeto.id+'" title="Apagar"><i class="fas fa-trash-alt fa-lg"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        return html;
    }

    inserirEventosLista()
    {
        var self = this;

        $('.btn-editar').click(function ()
        {
            var idObjeto = $(this).data('item');
            self.pagina.mostrarAlterar(idObjeto);
        });

        $('.btn-apagar').click(function ()
        {
            var idObjeto = $(this).data('item');
            self.pagina.mostrarApagar(idObjeto);
        });
    }
}