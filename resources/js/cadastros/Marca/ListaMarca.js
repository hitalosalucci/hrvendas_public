class ListaMarca extends Lista
{
    constructor(pagina)
    {
        super(pagina);

        this.inserirEventosPesquisa();
    }

    inserirEventosPesquisa()
    {
        var self = this;

        $('#inputPesquisaMarca').on('keyup', function ()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });
    }

    filtrar(objeto)
    {
        var textoPesquisa = $('#inputPesquisaMarca').val();

        return textoPesquisa == '' ||
            objeto.nome.toLowerCase().includes(textoPesquisa.toLowerCase());
    }

    // construirHtmlObjeto(objeto)
    // {
    //     var html = '';

    //     html += '<div class="list-group-item">';
    //     html += '<div class="row">';
    //     html += '<div class="col">'+objeto.nome+'</div>';
    //     html += '<div class="col-auto">';
    //     html += '<button class="btn btn-icon-tema-primary btn-editar" data-item="'+objeto.id+'" title="Editar"><i class="fas fa-edit fa-lg"></i></button>';
    //     html += '<button class="btn btn-icon-tema-primary btn-apagar" data-item="'+objeto.id+'" title="Apagar"><i class="fas fa-trash-alt fa-lg"></i></button>';
    //     html += '</div>';
    //     html += '</div>';
    //     html += '</div>';

    //     return html; 
    // }


}