class ListaCategoria extends Lista
{
    constructor (pagina)
    {
        super(pagina);

        this.inserirEventosPesquisa();
    }

    inserirEventosPesquisa()
    {
        var self = this;

        $('#inputPesquisaCategoria').on('keyup', function ()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });
    }

    filtrar(objeto)
    {
        var textoPesquisa = $('#inputPesquisaCategoria').val();

        return textoPesquisa = '' ||
            objeto.nome.toLowerCase().includes( textoPesquisa.toLowerCase() );
    }


}