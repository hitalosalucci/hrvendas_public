class ModalFormularioMarca extends ModalFormulario
{
    preencher(objeto)
    {
        this.esconderErros();
        $('#inputNome').val(objeto.nome);
        
    }

    limpar()
    {
        super.limpar();
    }

    enviarForm(form, e)
    {
        super.enviarForm(form, e);
    }

}