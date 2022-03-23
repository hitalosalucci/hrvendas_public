listaProdutosDisponiveis = null;
numItemPedido = 0;
selectAddProduto = $('#selectAddProduto');

//inputs form e buttons
inputCodigoProduto = $('#inputCodigoProduto');
inputCodigoBarrasProduto = $('#inputCodigoBarrasProduto');
inputValorUnitarioProduto = $('#inputValorUnitarioProduto');
inputQuantidadeProduto = $('#inputQuantidadeProduto');
inputDescontoProduto = $('#inputDescontoProduto');
inputValorTotalProduto = $('#inputValorTotalProduto');
btnAdicionarProduto = $('#btnAdicionarProduto');
btnLimparProduto = $('#btnLimparProduto');
btnLimparVenda = $('#btnLimparVenda');
btnContinuarVenda = $('#btnContinuarVenda')

$(document).ready(function () 
{
    carregarProdutos();
    carregarUsuarioLogado()
});

//funções

function carregarUsuarioLogado()
{
    $.get('/usuarios/usuario_logado', function(resposta){
        vendedor = resposta;
        preencherNomeVendedor(vendedor);
    });
}

function preencherNomeVendedor(vendedor)
{
    $('#spanNomeVendedor').text(vendedor.nome);
}

function carregarProdutos()
{
    $.get('/produtos/lista_por_categoria', function(resposta){
        listaProdutosDisponiveis = resposta;
        popularSelectComProdutos(selectAddProduto, listaProdutosDisponiveis);
    });    
}

function popularSelectComProdutos(select, listaProdutos, valorSelecionado = null)
{
    var html = '';

    $.each(listaProdutos, function(categoria, produtos)
    {
        html += '<optgroup label="'+categoria+'">';

        $.each(produtos, function (index, produto)
        {
            html += '<option value="'+produto.id+'" data-nome="'+produto.nome+'" data-tokens="'+categoria+' '+produto.marca+'"  '+(valorSelecionado != null && valorSelecionado == produto.id ? 'selected' : '')+'>'+produto.nome+'</option>';
        });

        html += '</optgroup>';
    });

    select.html(html);
    iniciarSelectPicker(select);
}

function iniciarSelectPicker(select)
{
    select.selectpicker({
        style: '',
        styleBase: 'custom-select custom-select-lg',
        liveSearch: true,
        container: 'body'
    });

    //o select picker muda a referência do select
    $("[data-id='"+select.prop('id')+"']").focus();
}

function getProdutoLista(idProduto)
{
    var retorno = null;

    $.each(listaProdutosDisponiveis, function (categoria, produtos)
    {
        //console.log(categoria);

        $.each(produtos, function(index, produto){
            if (produto.id == idProduto)
            retorno = produto
        });

    });

    return retorno;
}

function inserirInformacoesProdutoFormulario(produto)
{

    inputCodigoProduto.val(produto.codigo_produto);
    inputCodigoBarrasProduto.val(produto.codigo_barras);
    inputValorUnitarioProduto.val(produto.preco);
    inputDescontoProduto.val('');

    quantidade = inputQuantidadeProduto.val();
    valorUnitario = produto.preco;
    desconto = inputDescontoProduto.val();

    calcularEPreencherTotalUnitario(valorUnitario, quantidade, desconto);

    inputQuantidadeProduto.prop('disabled', false);
    inputDescontoProduto.prop('disabled', false);

    verificarSeProdutoTemValor(produto.preco);

    verificarUnidadeProdutoEPreencherQuantidade(produto.unidade);

    inputQuantidadeProduto.focus();

}

function verificarUnidadeProdutoEPreencherQuantidade(unidade)
{
    if (unidade.toUpperCase() == 'KG')
        inputQuantidadeProduto.val('0');
        
}

function verificarSeProdutoTemValor(precoProduto)
{

    if (precoProduto == 0)
        inputValorUnitarioProduto.prop('disabled', false);
    else
        inputValorUnitarioProduto.prop('disabled', true);


}

function calcularEPreencherTotalUnitario(valorUnitario, quantidade, desconto)
{

    if(desconto == '')
        desconto = 0;

    var valorTotalProduto = (parseFloat(valorUnitario) * parseFloat(quantidade)) - parseFloat(desconto);

    inputValorTotalProduto.val(valorTotalProduto.toFixed(2));

}

function construirHtmlLinhaItemVenda(produto, numItem)
{

    var html = '';

    valorDesconto = produto.valorDesconto == 0 || produto.valorDesconto == null ? '-' : formatarPreco(produto.valorDesconto);
    valorTotalProduto = produto.valorTotalProduto == 0 || produto.valorTotalProduto == null ? '-' : formatarPreco(produto.valorTotalProduto);
    
    valorDescontoNumber = produto.valorDesconto == null ? '' : produto.valorDesconto;


    html += '<div class="produto-adicionado list-group-item py-2 border-bottom" data-item="'+numItem+'">';
    html += '<div class="row">';
    html += '<div class="col-1">';
    html += '<span>'+produto.valorQuantidade+'x</span>';
    html += '</div>';
    html += '<input class="produto-quantidade" type="hidden" value="'+produto.valorQuantidade+'" disabled>';
    html += '<div class="col text-truncate">';
    html += '<span title="'+produto.nomeProduto+'">'+produto.nomeProduto+'</span>';
    html += '</div>';
    html += '<input class="produto-id" type="hidden" value="'+produto.idProduto+'" disabled>';
    html += '<div class="col-2">';
    html += '<span>'+valorDesconto+'</span>';
    html += '</div>';
    html += '<input class="produto-desconto" type="hidden" value="'+valorDescontoNumber+'" disabled>';
    html += '<div class="col-3" data-item="'+numItem+'">';
    html += '<input type="hidden" class="valor-item" value="'+produto.valorTotalProduto+'">'
    html += '<span>'+valorTotalProduto+'</span>';
    html += '</div>';
    html += '<input class="produto-valorTotal" type="hidden" value="'+produto.valorTotalProduto+'" disabled>';
    html += '<div class="col-auto">';
    html += '<button class="btn btn-sm btn-dark btn-editar-item" style="width: 30px;" title="Editar item" data-item="'+numItem+'" data-produto="'+produto.idProduto+'" data-quantidade="'+produto.valorQuantidade+'" data-desconto="'+produto.valorDesconto+'"><i class="fas fa-edit"></i></button>';
    html += '<button class="btn btn-sm btn-danger btn-remover-item ml-1" style="width: 30px;" title="Excluir item" data-item="'+numItem+'"><i class="fas fa-times"></i></button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

function adicionarItemVenda(produto)
{
    adicionarLinhaItemVenda(produto, numItemPedido);
    inserirEventos(numItemPedido);
    resetarInputsProduto();

    numItemPedido++;
}

function adicionarLinhaItemVenda(produto, numItem)
{
    var htmlNovaLinha = construirHtmlLinhaItemVenda(produto, numItem);
    $('#listaItensVenda').append(htmlNovaLinha);

    atualizarValorTotal();
}

function atualizarValorTotal()
{
    var valorTotal = calcularValorTotal();
    var valorTotalTexto = formatarPreco(valorTotal);

    $('#valorTotalVenda').text(valorTotalTexto);
    $('#valorSubtotalPagamento').text(valorTotalTexto);

}
    
function calcularValorTotal()
{
    var valorTotalVenda = 0;

    $('.valor-item').each(function() 
    {
        valorTotalVenda += Number($(this).val());
        
    });

    return valorTotalVenda.toFixed(2);
}

function resetarInputsProduto()
{

    inputCodigoProduto.val('');
    inputCodigoBarrasProduto.val('');
    inputValorUnitarioProduto.val('');
    inputQuantidadeProduto.val('1');
    inputDescontoProduto.val('');
    inputValorTotalProduto.val('');

    inputDescontoProduto.prop('disabled', true);

    selectAddProduto.prop('selectedIndex', false);
    selectAddProduto.selectpicker('refresh');

    $("[data-id='selectAddProduto']").focus();
}

function resetarVenda()
{
    resetarInputsProduto();
    $('.produto-adicionado').remove();

    $('#valorTotalVenda').text('R$ 0,00');

}

function inserirEventos(numItem)
{
    $('.btn-remover-item[data-item='+numItem+']').click(eventoBotaoRemoverClicado);
    $('.btn-editar-item[data-item='+numItem+']').click(eventoBotaoEditarClicado);

}

function eventoBotaoRemoverClicado()
{
    var numItem = $(this).data('item');
    removerLinhaItem(numItem);

    atualizarValorTotal()
}

function removerLinhaItem(numItem)
{
    $('div[data-item='+numItem+']').remove();

    $("[data-id='selectAddProduto']").focus();
}

function eventoBotaoEditarClicado()
{
    var numItem = $(this).data('item');

    var produtoEscolhido = $(this).data('produto');
    var quantidade = $(this).data('quantidade');
    var desconto = $(this).data('desconto');
    
    produto = getProdutoLista(produtoEscolhido);

    informacoesAnteriores = {'quantidade': quantidade, 'desconto': desconto}; //criar lista
    
    titulo = 'Editar '+produto.nome;
    texto = 'Essse produto será apagado da lista';

    mostrarMensagemConfirmarEdicaoProduto(titulo, texto, numItem, produto, informacoesAnteriores);
}

function mostrarMensagemConfirmarEdicaoProduto(titulo, texto, numItem, produto, informacoesAnteriores)
{

    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'question',
        confirmButtonText: 'Sim',
        confirmButtonColor: '#174578',
        showCancelButton: true,
        cancelButtonText: 'Não',
    }).then(function (resultado)
    {
        if (resultado.value)
            inserirInformacoesProdutoFormularioEditar(numItem, produto, informacoesAnteriores);
    });
}

function inserirInformacoesProdutoFormularioEditar(numItem, produto, informacoes)
{
    removerLinhaItem(numItem);

    //informacoes fixas
    selectAddProduto.selectpicker('val', produto.id);

    inputCodigoProduto.val(produto.codigo_produto);
    inputCodigoBarrasProduto.val(produto.codigo_barras);
    inputValorUnitarioProduto.val(produto.preco);
    valorUnitario = produto.preco;

    //informacoes anteriores
    inputQuantidadeProduto.val(informacoes.quantidade);
    inputDescontoProduto.val(informacoes.desconto);

    //infromacoesCalculadas
    calcularEPreencherTotalUnitario(valorUnitario, informacoes.quantidade, informacoes.desconto);

    inputQuantidadeProduto.prop('disabled', false);
    inputDescontoProduto.prop('disabled', false);

    verificarSeProdutoTemValor(produto.preco);

    inputQuantidadeProduto.focus();

}

// eventos
selectAddProduto.change(function()
{
    var valorSelecionado = $(this).val();
    var produto = getProdutoLista(valorSelecionado);

    inserirInformacoesProdutoFormulario(produto);
});

inputQuantidadeProduto.on('keyup change', function()
{
    
    var quantidade = $(this).val();
    var valorUnitario = inputValorUnitarioProduto.val();
    var desconto = inputDescontoProduto.val();

    if (isNaN(quantidade) || quantidade <= 0){
        mostrarAlertaToast('info', 'Não pode ser zero. Foi automaticamente preenchido')
        $(this).val('1');
    }

    calcularEPreencherTotalUnitario(valorUnitario, quantidade, desconto);
});

inputDescontoProduto.on('keyup change', function()
{
    var desconto = $(this).val();
    var quantidade = inputQuantidadeProduto.val();
    var valorUnitario = inputValorUnitarioProduto.val();    

    calcularEPreencherTotalUnitario(valorUnitario, quantidade, desconto);
});

inputValorUnitarioProduto.on('keyup change', function()
{
    var valorUnitario = $(this).val();
    var quantidade = inputQuantidadeProduto.val();
    var desconto = inputDescontoProduto.val();

    calcularEPreencherTotalUnitario(valorUnitario, quantidade, desconto);
});

btnAdicionarProduto.click(function()
{
    var idProduto = selectAddProduto.val();
    var nomeProduto = selectAddProduto.find(':selected').data('nome');
    var valorQuantidade = inputQuantidadeProduto.val();
    var valorDesconto = inputDescontoProduto.val();
    var valorTotalProduto = inputValorTotalProduto.val();

    produto = {
                'idProduto':idProduto,
                'valorQuantidade':valorQuantidade, 
                'nomeProduto':nomeProduto,
                'valorDesconto':valorDesconto,
                'valorTotalProduto':valorTotalProduto
            };

    if(selectAddProduto.val() == '')
        mostrarAlertaToast('info', 'Selecione um produto para adicionar');
    else
        adicionarItemVenda(produto);  

});

btnLimparProduto.click(function()
{
    acao = 'resetarInputsProduto';

    titulo = 'Deseja mesmo limpar os dados do produto?';
    texto = 'Isso não poderá ser desfeito';
    
    mostrarMensagemConfirmacaoLimpar(acao, titulo, texto);
    
});

btnLimparVenda.click(function()
{
    acao = 'resetarVenda';

    titulo = 'Deseja mesmo limpar todos os dados inseridos?';
    texto = 'Isso não poderá ser desfeito';
    
    mostrarMensagemConfirmacaoLimpar(acao, titulo, texto);
});

function mostrarMensagemConfirmacaoLimpar(acao, titulo, texto)
{
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'warning',
        confirmButtonText: 'Sim',
        confirmButtonColor: '#174578',
        showCancelButton: true,
        cancelButtonText: 'Não',
    }).then(function (resultado)
    {
        if (resultado.value){

            if (acao == 'resetarInputsProduto')
                resetarInputsProduto();
            
            if (acao == 'resetarVenda')
                resetarVenda();
        }
    });

}
