$(document).ready(function()
{
    if (typeof informacoesAlteracao != 'undefined')
        preencherInformacoesPagina(informacoesAlteracao);

    function preencherInformacoesPagina(informacoes)
    {
        adicionarInputNumeroPedido(informacoes.venda);
        
        preencherInformacoes(informacoes);
    }

    function adicionarInputNumeroPedido(venda)
    {
        var html = '<input type="hidden" id="inputNumeroVenda" value="'+venda+'">';
        $('#btnContinuarVenda').before(html);
    }

    function preencherInformacoes(informacoes)
    {
        var esperaCarregamentoInformacoes = setInterval(function ()
        {
            if (listaProdutosDisponiveis != null)
            {
                clearInterval(esperaCarregamentoInformacoes);
                
                preencherItensVenda(informacoes.itens);
                
                preencherItensPagamento(informacoes.pagamentos);

                preencherClienteSeHouver(informacoes.cliente);
            }
        }, 15);
       
    }

    function preencherItensVenda(itens)
    {
    
        $.each(itens, function(index, item)
        {
            adicionarItemVenda(item);
        });

    }

    function preencherItensPagamento(pagamentos)
    {
        for (var i = 0; i < pagamentos.length; i++){
            var pagamento = pagamentos[i];
            
            adicionarItemMetodoPagamento(pagamento);
        }
    }

    function preencherClienteSeHouver(cliente)
    {
        //console.log(cliente);
        inserirInformacoesClienteInicio(cliente)
    }

});