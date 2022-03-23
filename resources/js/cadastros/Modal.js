class Modal
{
    constructor(idModal, pagina)
    {
        this.idModal = idModal;
        this.modal = $('#'+idModal);
        this.pagina = pagina;

        this.focarInput();
    }

    mudarTitulo(titulo)
    {
        $('#'+this.idModal+' .modal-title').text(titulo);
    }

    mostrar()
    {
        this.modal.modal('show');
    }

    fechar()
    {
        this.modal.modal('hide');
    }

    focarInput()
    {
        //quando o modal abrir, vai pegar o primeiro input text do modal-body e focar
        this.modal.on("shown.bs.modal", function(e) {
            $('.modal-body input[type=text]').filter(':first').focus();
        });
    }

}