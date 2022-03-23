$(document).ready(function()
{

    //variaveis
    var btnFinalizarVenda = $('#btnFinalizarVenda');
    
    var esperandoResposta = false;
    var timeout = null;
    
    //funções
    function ativarTimeout()
    {
        esperandoResposta = true;
        timeout = setTimeout(respostaNaoRecebida, 10000);        
    }

    function desativarTimeout()
    {
        clearTimeout(timeout);
        timeout = null;
        esperandoResposta = false;
    }

    function respostaNaoRecebida()
    {
        mostrarAlertaToast('error', 'Erro! Servidor não respondeu ao seu registro de venda');
        pararAnimacaoEspera(btnFinalizarVenda);

        esperandoResposta = false;
        timeout = null;
    }

    function verificarPagamentosEContinuar()
    {
        var valorTotalPagamento = calcularValorTotalPagamento();
        var valorTotalVenda = calcularValorTotal();

        if (valorTotalPagamento == 0){
            mostrarAlertaToast('warning', 'Nenhum pagamento adicionado');
            selectMetodoPagamento.focus();
        }
        
        else if ($('#inputValorTotal').val() == '')
            mostrarAlertaToast('error', 'Não foi possível coletar os dados da venda. Recarregue e tente novamente');
        
        else if ( parseFloat(valorTotalPagamento) < parseFloat(valorTotalVenda) )
            mostrarMensagemConfirmacaoContinuarVenda('Deseja finalizar a venda?', 'Há pagamentos faltando');
        
        else if ( parseFloat(valorTotalPagamento) > parseFloat(valorTotalVenda) )
            mostrarMensagemConfirmacaoContinuarVenda('Deseja finalizar a venda?', 'Há pagamentos passando');
        else
            finalizarVenda();

    }

    function mostrarMensagemConfirmacaoContinuarVenda(titulo, texto)
    {
        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'warning',
            confirmButtonText: 'Sim',
            confirmButtonColor: '#174578',
            showCancelButton: true,
            cancelButtonText: 'Não',
            allowOutsideClick: true,
            allowEscapeKey: true,
            animation: false
        }).then(function (resultado)
        {
            if (resultado.value)
                finalizarVenda();
        });
    }

    function finalizarVenda()
    {
        dados = getDados();
    
        //console.log(dados)
        salvarVenda(dados);
    }

    function verificarPaginaAtualizacao()
    {
        return $('#inputNumeroVenda').length > 0;
    }

    function salvarVenda(dados)
    {
        if(verificarPaginaAtualizacao())
            atualizar(dados);
        else
            salvar(dados);
    }

    function salvar(dados)
    {
        mostrarAnimacaoEspera(btnFinalizarVenda);
        ativarTimeout();
        $.post('/vendas', dados, callbackSalvar);
    }

    function atualizar(dados)
    {
        var numPedido = getNumVendasAtualizando();

        $.ajax({
            url: '/vendas/'+numPedido,
            method: 'PUT',
            data: dados,
            success: callbackSalvar
        });
    }

    function getNumVendasAtualizando()
    {
        return $('#inputNumeroVenda').val();
    }

    function callbackSalvar(resposta)
    {
    
        if (resposta.status == 1){
            mostrarAlertaToast('success', 'Venda registrada');
            resetarPaginaVendaCompletamente();
            abrirPaginaImpressaoVenda(resposta.numero_venda);
        }
            //console.log(resposta)
            //pedidoSalvo(resposta.numero_pedido);
        else
            mostrarErroServidor(resposta.erro);

        pararAnimacaoEspera(btnFinalizarVenda);
        desativarTimeout();
    }

    function abrirPaginaImpressaoVenda(numeroVenda)
    {
        window.open('/vendas/' + numeroVenda + '/imprimir');
        document.location.href = '/vendas/realizar_venda';
    }

    function mostrarErroServidor(stringErros)
    {
        pararAnimacaoEspera($('#btnSalvar'));

        var mensagensErro = {
            vazio: 'Este campo não pode ficar vazio',
            invalido: 'Informação inválida',
            duplicado: 'Este valor já está cadastrado',
            negativo: 'Este valor não pode ser negativo',
        };

        var erros = stringErros.split(';');

        var mensagemErro = '';

        for (var i = 0; i < erros.length - 1; i++)
        {
            var erro = erros[i].split(': ');

            if (i > 0)
                mensagemErro += '<br>';

            console.log(erro[1]);

            mensagemErro += getNomeCampo(erro[0]) + ': ' + mensagensErro[erro[1]];
        }

        mostrarAlertaToast('error', mensagemErro);
    }

    function getNomeCampo(campo)
    {
        var campos = {
            'valor-recebido': 'Valor pago',
        }

        return campos[campo] != undefined ? campos[campo] : campo;
    }

    function getDados()
    {
        var dados = {
            _token: $('[name=_token]').val(),
            idCliente: $('#inputHiddenClienteSelecionado').val(),
            
            valorTotal: $('#inputValorTotal').val(),
            valorDescontos: $('#inputValorDescontos').val(),
            //valorTrocos: $('#inputValorTrocos').val(),
            pagamentos: JSON.stringify(getInformacoesPagamento()),
            produtos: JSON.stringify(getProdutosVenda()),
        };

        return dados;
    }

    function getProdutosVenda()
    {
        var divsProdutos = $('.produto-adicionado');
        var listaProdutos = [];

        divsProdutos.each(function ()
        {
            dadosProdutos = {
                produto: $(this).find('.produto-id').val(),
                quantidade: $(this).find('.produto-quantidade').val(),
                valorTotal: $(this).find('.produto-valorTotal').val(),
                desconto: $(this).find('.produto-desconto').val(),
            };

            listaProdutos.push(dadosProdutos);
        });

        return listaProdutos;
    }

    function getInformacoesPagamento()
    {
        var divsPagamento = $('.pagamento-adicionado');
        var listaPagamentos = [];

        divsPagamento.each(function(){ 
            dadosPagamento = {
                metodo: $(this).find('.pagamento-id').val(),
                valorPagamento: $(this).find('.pagamento-valorPagamentoComDesconto').val(),
                trocoPara: $(this).find('.pagamento-trocoPara').val(),
                valorDesconto: $(this).find('.pagamento-valorDesconto').val(),
            };

            listaPagamentos.push(dadosPagamento)
        });

        return listaPagamentos;
    }

    function resetarPaginaVendaCompletamente()
    {
        $('#modalPagamento').modal('hide');
        
        cancelarPagamento();
        resetarVenda();
        atualizarTotaisPagamento();
        limparSelecaoCliente();
        limparCadastroCliente();

        $("[data-id='selectAddProduto']").focus();

    }

    //eventos

    btnFinalizarVenda.click(function(){
        verificarPagamentosEContinuar();
    });

});