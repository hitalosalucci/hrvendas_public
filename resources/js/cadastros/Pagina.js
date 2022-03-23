class Pagina
{
    constructor(nomeController, nomeTipoObjeto, enderecoLista = 'lista')
    {
        this.listaObjetos = null;
        this.objetoSelecionado = null;

        this.lista = null;
        this.modalApagar = null;
        this.modalFormulario = null;

        this.enderecoController = nomeController;
        this.enderecoLista = this.enderecoController+'/'+enderecoLista;

        this.nomeTipoObjeto = nomeTipoObjeto;  
    }

    mostrarApagar(idObjeto)
    {
        this.objetoSelecionado = this.getObjeto(idObjeto);

        this.modalApagar.mostrar(this.objetoSelecionado.nome);
    }

    apagarCancelado()
    {
        this.objetoSelecionado = null;
    }

    apagarConfirmado()
    {
        var self = this;

        var dados = {
            _token: $('[name=_token]').val(),
            _method: 'DELETE',
        };

        var id = self.objetoSelecionado.id;

        $.post(self.enderecoController+'/'+id, dados, callbackApagar);

        function callbackApagar(resposta)
        {
            pararAnimacaoEspera(self.modalApagar.botaoConfirmar);

            self.carregarObjetos();
            self.modalApagar.fechar();

            self.objetoSelecionado = null;

            if(resposta.status == 1)
                self.mostrarAlertaToast('success', self.nomeTipoObjeto+' foi apagad'+self.verificarGeneroObjeto());
        }
    }

    mostrarAlterar(idObjeto)
    {
        this.objetoSelecionado = this.getObjeto(idObjeto);
        
        this.modalFormulario.mudarTitulo('Alterar '+this.nomeTipoObjeto);
        this.modalFormulario.preencher(this.objetoSelecionado);
        this.modalFormulario.mostrar();

    }

    modalFormularioFechado()
    {
        if (this.objetoSelecionado != null)
        {
            this.objetoSelecionado = null;
            this.modalFormulario.limpar();
            this.modalFormulario.mudarTitulo('Cadastrar '+this.nomeTipoObjeto);
        }
    }

    enviarForm(dados)
    {
        var self = this;
        var url = this.enderecoController;

        if (this.objetoSelecionado == null)
            $.post(url, dados, callbackSalvar);
        else
            this.enviarAlteracao(url, this.objetoSelecionado.id, dados, callbackSalvar);

        function callbackSalvar(resposta)
        {
            pararAnimacaoEspera(self.modalFormulario.botaoSalvar);

            if (resposta.status == 1)
                self.objetoSalvo();
            else
                self.modalFormulario.mostrarErros(resposta.erros);
        }
    }

    enviarAlteracao(url, id, dados, callback)
    {
        dados += '&_method=PUT';
        url += '/' + id;

        $.post(url, dados, callback);
    }

    objetoSalvo()
    {
        this.modalFormulario.fechar();
        this.modalFormulario.limpar();
        this.carregarObjetos();
        this.mostrarAlertaToast('success', this.nomeTipoObjeto+' foi salv'+this.verificarGeneroObjeto());
    }

    carregarObjetos()
    {
        var self = this;
        
        $('#listaObjetos').html('<div class="text-tema-primary text-center mt-3"><i class="fas fa-3x fa-spinner fa-pulse"></i></div>');
        
        $.get(this.enderecoLista, function (resposta)
        {
            self.listaObjetos = resposta;
            self.lista.popularListaObjetos(resposta);
        });
    }

    getObjeto(id)
    {
        for (var i = 0; i < this.listaObjetos.length; i++)
        {
            var objeto = this.listaObjetos[i];

            if (objeto.id == id)
                return objeto;
        }

        return null;
    }

    verificarGeneroObjeto()
    {

        this.ultimoCaractere = this.nomeTipoObjeto[this.nomeTipoObjeto.length-1];

        if (this.ultimoCaractere == 'a')
            return 'a';
        else
            return 'o';   

    }

    mostrarAlertaToast(iconeAlerta, mensagem)
    {
        Swal.fire({
            icon: iconeAlerta,
            title: mensagem,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    }
}