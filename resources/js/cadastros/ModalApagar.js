class ModalApagar extends Modal
{
    constructor(pagina, nomeTipoObjeto)
    {
        super('modalApagar', pagina);

        this.botaoConfirmar = $('#modalApagarSim');
        this.mudarTitulo('Apagar '+nomeTipoObjeto)

        this.inserirEventos();
    }

    mostrar(nomeObjeto)
    {
        $('#modalApagarObjeto').text(nomeObjeto);
        super.mostrar();
    }

    inserirEventos()
    {
        var self = this;

        this.modal.on('hidden.bs.modal', function ()
        {
            self.pagina.apagarCancelado();
        });

        this.botaoConfirmar.click(function ()
        {
            mostrarAnimacaoEspera(self.botaoConfirmar);
            self.pagina.apagarConfirmado();
        });
    }
}