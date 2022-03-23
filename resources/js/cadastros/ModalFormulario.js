class ModalFormulario extends Modal
{
    constructor(pagina)
    {
        super('modalCadastro', pagina);

        this.botaoSalvar = $('#modalCadastroSalvar');

        this.inserirEventos();
    }

    inserirEventos()
    {
        var self = this;

        $('#formCadastro').submit(function (e)
        {
            self.enviarForm(this, e);
        });

        $('#modalCadastro').on('hidden.bs.modal', function ()
        {
            self.pagina.modalFormularioFechado();
        });
    }

    enviarForm(form, e)
    {
        e.preventDefault();

        this.esconderErros();
        mostrarAnimacaoEspera(this.botaoSalvar);

        var dados = $(form).serialize();

        this.pagina.enviarForm(dados);
    }

    limpar()
    {
        this.esconderErros();
        $('#formCadastro')[0].reset();
    }

    getMensagemErro(erro)
    {
        var mensagens = {
            'vazio': 'Este campo não pode ficar vazio',
            'invalido': 'Número digitado é inválido',
            'negativo': 'Este campo não pode ser negativo',
            'inexistente': 'Objeto selecionado não existe, selecione um objeto ou tente recarregar a página',
        }

        return mensagens[erro];
    }

    mostrarErros(erros)
    {
        var self = this;
        $.each(erros, function (nomeCampo, erro)
        {
            var mensagem = self.getMensagemErro(erro);
            var campo = $('[name='+nomeCampo+']');

            campo.next().text(mensagem);
            campo.addClass('is-invalid');
        });
    }

    esconderErros()
    {
        $('#formCadastro').find('.form-control').removeClass('is-invalid');
    }
}