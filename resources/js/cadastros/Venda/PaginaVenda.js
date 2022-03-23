class PaginaVenda extends Pagina
{
    carregarObjetos()
    {
        var self = this;

        var pesquisa_data= $('#inputDataVenda').val();
        var endereco = this.enderecoLista + '?data='+pesquisa_data;

        $('#listaObjetos').html('<div class="text-tema-primary text-center mt-3"><i class="fas fa-3x fa-spinner fa-pulse"></i></div>');

        $.get(endereco, function (resposta)
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

            if (objeto.id_venda == id)
                return objeto;
        }

        return null;
    }

    mostrarApagar(idObjeto)
    {
        this.objetoSelecionado = this.getObjeto(idObjeto);

        var mensagem = 'a venda realizada por '+this.objetoSelecionado.usuario;

        this.modalApagar.mostrar(mensagem);
        
    }

    apagarConfirmado()
    {
        var self = this;

        var dados = {
            _token: $('[name=_token]').val(),
            _method: 'DELETE',
        };

        var id = self.objetoSelecionado.id_venda;

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
}