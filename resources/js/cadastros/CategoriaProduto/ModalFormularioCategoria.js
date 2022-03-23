class ModalFormularioCategoria extends ModalFormulario
{
    preencher(objeto)
    {
        this.esconderErros();
        $('#inputNome').val(objeto.nome);
        
    }

}