//variaveis

//iniciando variáveis de trabalho
numPagamento = 0;
metodoDinheiroId = 1; 
telaPagamentoAtiva = false;

selectMetodoPagamento = $('#selectMetodoPagamento');

inputValorPagamento = $('#inputValorPagamento');
inputTrocoPara = $('#inputTrocoPara');
inputDescontoPagamento = $('#inputDescontoPagamento');

btnAdicionarMetodo = $('#btnAdicionarMetodo');
btnCancelarPagamento = $('.btnCancelarPagamento');

//iniciando a máscara no input de pagamento
//inputValorPagamento.mask('#.##0,00', {reverse: true});

//funções

function exibirModalPagamento()
{
    $('#modalPagamento').modal('show');
    selectMetodoPagamento.focus();

    telaPagamentoAtiva = true;
}

function irParaPagamento()
{

    var valorTotal = calcularValorTotal();

    //adiciona no input hidden o total do pagamento
    $('#inputValorTotal').val(valorTotal);

    if (valorTotal == 0){
        mostrarAlertaToast('warning', 'Nenhum produto adicionado');
        $("[data-id='selectAddProduto']").focus();
    }
    else
        exibirModalPagamento();

    atualizarTotaisPagamento();

    verificarSeHaPagamentosAdicionados();

    verificarPagamentosAdicionadosSeFalta();
}

function verificarSeHaPagamentosAdicionados()
{
    numPagamentosAdicionados = $('.pagamento-adicionado').length;
    
    mostrarEsconderBarraPagamentos(numPagamentosAdicionados);

}

function mostrarEsconderBarraPagamentos(numPagamentos)
{
    if(numPagamentos == 0){
        $('#listaPagamentosAdicionados').addClass('invisible');
        $('#mensagemMetodoPagamento').removeClass('invisible');
    }
    else{
        $('#listaPagamentosAdicionados').removeClass('invisible');
        $('#mensagemMetodoPagamento').addClass('invisible');
    }
        
}

function preencherInputValorPagamento()
{
    
    valorFaltaPagar = calcularValorFaltaPagar();
    valorFaltaPagarTexto = formatarPreco(valorFaltaPagar)

    inputValorPagamento.val(valorFaltaPagar);
}

function calcularValorFaltaPagar()
{
    
    var valorTotalVenda = calcularValorTotal();
    var valorTotalPagamento = calcularValorTotalPagamento();

    valorFaltaPagar = (valorTotalVenda - valorTotalPagamento);

    return valorFaltaPagar.toFixed(2);
}

function calcularValorTotalPagamento()
{   
    var valorTotalPagamento = 0;

    $('.valor-pagamento').each(function() 
    {
        valorTotalPagamento += Number($(this).val());
    });

    return valorTotalPagamento;
}

function calcularDescontoTotal()
{   
    var valorTotalDesconto = 0;

    $('.valor-desconto').each(function() 
    {
        valorTotalDesconto += Number($(this).val());
    });

    return valorTotalDesconto;
}

function calcularTrocoTotal()
{
    var valorTotalTroco = 0;

    $('.valor-troco').each(function() 
    {
        valorTotalTroco += Number($(this).val());
    });

    return valorTotalTroco;

}

function calcularValorTotalAPagar(valorTotalDesconto)
{
    valorTotal = calcularValorTotal();
    
    valorTotalAPagar = (valorTotal - valorTotalDesconto); 

    return valorTotalAPagar;
} 

function verificarMetodoSelecionado()
{

    idMetodo = selectMetodoPagamento.val();
    nomeMetodo = getNomeMetodoSelecionado();

    valorPagamento = inputValorPagamento.val();
    trocoPara = inputTrocoPara.val();
    desconto = inputDescontoPagamento.val();

    valorTroco = calcularTrocoPagamento();

    metodoPagamento = {
        'idMetodo' : idMetodo,
        'nomeMetodo' : nomeMetodo,
        'valorPagamento' : valorPagamento,
        'trocoPara' : trocoPara,
        'valorDesconto' : desconto,
        'valorTroco' : valorTroco

    };

    if (idMetodo == null)
        mostrarAlertaToast('warning', 'Nenhum método selecionado');
    else
        verificarValoresInformadosInputs(metodoPagamento);
}

function verificarValoresInformadosInputs(metodoPagamento)
{
    var valorPagamento = inputValorPagamento.val().trim();
    var trocoPara = inputTrocoPara.val().trim();
    var valorTotalVenda = calcularValorTotal();

    if (valorPagamento == '')
        mostrarAlertaToast('info', 'Insira o valor pago');
    else if (trocoPara != '' && (parseFloat(trocoPara) < parseFloat(valorPagamento)))
        mostrarAlertaToast('info', 'Troco para não pode ser menor que valor pago');
    else if (valorPagamento <= 0)
        mostrarAlertaToast('info', 'Valor pago deve ser maior que 0');
    else if (parseFloat(valorPagamento) > parseFloat(valorTotalVenda))
        mostrarAlertaToast('info', 'Valor do pagamento maior que o total');
    else
        adicionarItemMetodoPagamento(metodoPagamento);

    console.log(valorPagamento+' venda:'+valorTotalVenda);


}

function mostrarInpusMetodoPagamento()
{
    metodoSelecionado = selectMetodoPagamento.val();
    
    if(metodoSelecionado == metodoDinheiroId)
        mostrarTodosCamposPagamento();
    else
        ocultarCamposTrocoPagamento();
}

function mostrarTodosCamposPagamento()
{
    
    $('#divTrocoPara').show();
    $('#divTrocoPagamento').show();

    inputValorPagamento.prop('disabled', false);
    inputDescontoPagamento.prop('disabled', false);
    inputTrocoPara.prop('disabled', false);
}

function ocultarCamposTrocoPagamento()
{
    inputValorPagamento.prop('disabled', false);
    inputDescontoPagamento.prop('disabled', false);

    $('#divTrocoPara').hide();
    $('#divTrocoPagamento').hide();
}

function getNomeMetodoSelecionado()
{

    var metodoSelecionado = selectMetodoPagamento.find('option:selected');
    var nomeMetodo = metodoSelecionado.data('nome');

    return nomeMetodo;
}

function adicionarItemMetodoPagamento(metodoPagamento)
{  
    
    adicionarLinhaItemMetodoPagamento(metodoPagamento, numPagamento);
    inserirEventosPagamentos(numPagamento);

    numPagamento++;

    resetarInputsMetodosPagamento();
    resetarValorTrocoPagamento();
    verificarPagamentosAdicionadosSeFalta();

    atualizarTotaisPagamento();
}

function verificarPagamentosAdicionadosSeFalta()
{
    valorFaltaPagar = calcularValorFaltaPagar();
    valorFaltaPagarTexto = formatarPreco(Math.abs(valorFaltaPagar));

    pagamentosAdicionados = calcularValorTotalPagamento();

    if (Number(pagamentosAdicionados) == 0)
        esconderAvisoFaltaPagar();
    else if(Number(valorFaltaPagar) == 0 )
        mostrarAvisoTudoPago();
    else if(Number(valorFaltaPagar) > 0)
        mostrarAvisoFaltaPagar(valorFaltaPagarTexto);
    else if (Number(valorFaltaPagar) < 0)
        mostrarAvisoPagamentoAMais(valorFaltaPagarTexto);

}

function mostrarAvisoTudoPago()
{
    $('#divAvisoFaltandoPagamento').removeClass('invisible')
    .addClass('alert-success')
    .removeClass('alert-warning')
    .removeClass('alert-danger');

    $('#textoAvisoPagamento').html('<i class="fa-1x fas fa-check-circle"></i> Pagamento total já adicionado');

}

function mostrarAvisoFaltaPagar(valorFaltaPagarTexto)
{
    $('#divAvisoFaltandoPagamento').removeClass('invisible')
    .addClass('alert-warning')
    .removeClass('alert-success')
    .removeClass('alert-danger');

    $('#textoAvisoPagamento').html('<i class="fas fa-exclamation-circle"></i> Falta pagar ' +valorFaltaPagarTexto);

}

function mostrarAvisoPagamentoAMais()
{
    $('#divAvisoFaltandoPagamento').removeClass('invisible')
    .addClass('alert-danger')
    .removeClass('alert-success')
    .removeClass('alert-warning')

    $('#textoAvisoPagamento').html('<i class="fas fa-times-circle"></i> O pagamento passou ' +valorFaltaPagarTexto);
}

function esconderAvisoFaltaPagar()
{
    $('#divAvisoFaltandoPagamento').addClass('invisible');
}

function adicionarLinhaItemMetodoPagamento(metodoPagamento, numPagamento)
{
    
    var htmlNovaLinha = construirHtmlLinhaItemMetodo(metodoPagamento, numPagamento);

    $('#listaMetodosPagamentoVenda').append(htmlNovaLinha);

    atualizarTotaisPagamento();
    verificarSeHaPagamentosAdicionados();
}

function calcularTrocoPagamento()
{
    valorPagamento = inputValorPagamento.val();
    trocoParaCalculo = inputTrocoPara.val();
    valorDesconto = inputDescontoPagamento.val();

    if(trocoParaCalculo.trim() == ''){
        trocoParaCalculo = valorPagamento;
        valorDesconto = ''; //para que o valor do troco não fique negativo
    }

    troco = (trocoParaCalculo - valorPagamento + Number(valorDesconto));

    return troco;
}

function atualizarValorTrocoPagamento()
{
    valorTroco = calcularTrocoPagamento();
    valorTrocoTexto = formatarPreco(valorTroco);
    trocoPara = inputTrocoPara.val();
    desconto = inputDescontoPagamento.val();

    if (valorTroco < 0 && trocoPara.trim() != '')
        $('#valorTrocoMetodo').addClass('text-danger').removeClass('text-tema-success');
    else if (valorTroco > 0 && trocoPara.trim() != '')
        $('#valorTrocoMetodo').removeClass('text-danger').addClass('text-tema-success');
    else if(trocoPara.trim() == '')
        resetarValorTrocoPagamento();    

    if (trocoPara.trim() != '')
        $('#valorTrocoMetodo').html(valorTrocoTexto);
}

function construirHtmlLinhaItemMetodo(metodoPagamento, numPagamento)
{

    html = '';

    valorTrocoVerificado = metodoPagamento.valorTroco == 0 || metodoPagamento.valorTroco == null ? '-' : formatarPreco(metodoPagamento.valorTroco);
    valorDescontoVerificado = metodoPagamento.valorDesconto == 0 || metodoPagamento.valorDesconto == null ? '-' : formatarPreco(metodoPagamento.valorDesconto);
    
    valorDescontoVerificadoNumber = metodoPagamento.valorDesconto == null ? '' : metodoPagamento.valorDesconto;
    valorTrocoParaNumber = metodoPagamento.trocoPara == null ? '' : metodoPagamento.trocoPara;

    valorPagamentoComDesconto = (Number(metodoPagamento.valorPagamento) - Number(metodoPagamento.valorDesconto));

    html += '<div class="list-group-item py-1 pagamento-adicionado" data-pagamento="'+numPagamento+'">'
    html += '<div class="row">';
    html += '<div title="'+metodoPagamento.nomeMetodo+'" class="col text-truncate">';
    html += '<span>'+metodoPagamento.nomeMetodo+'</span>';
    html += '</div>';
    html += '<input class="pagamento-id" type="hidden" value="'+metodoPagamento.idMetodo+'" disabled>';
    html += '<div class="col-3">';
    html += '<span>'+formatarPreco(metodoPagamento.valorPagamento)+'</span>';
    html += '</div>';
    html += '<input class="pagamento-valorPagamento" type="hidden" value="'+metodoPagamento.valorPagamento+'" disabled>';
    html += '<input class="pagamento-valorPagamentoComDesconto" type="hidden" value="'+valorPagamentoComDesconto+'" disabled>';
    // html += '<div class="col-2">';
    // html += '<span>'+trocoPara+'</span>';
    // html += '</div>';
    html += '<div class="col-2">';
    html += '<span>'+valorTrocoVerificado+'</span>';
    html += '</div>';
    html += '<input class="pagamento-trocoPara" type="hidden" value="'+valorTrocoParaNumber+'" disabled>';
    html += '<div class="col-2">';
    html += '<span class="text-danger">'+valorDescontoVerificado+'</span>';
    html += '</div>';
    html += '<input class="pagamento-valorDesconto" type="hidden" value="'+valorDescontoVerificadoNumber+'" disabled>';
    //html += '<input type="hidden" value="'+id+'">'; //id do método
    html += '<input type="hidden" class="valor-pagamento" value="'+metodoPagamento.valorPagamento+'">';
    html += '<input type="hidden" class="valor-desconto" value="'+valorDescontoVerificadoNumber+'">';
    html += '<input type="hidden" class="valor-troco" value="'+metodoPagamento.valorTroco+'">';
    html += '<div class="col-auto">';
    html += '<button class="btn btn-sm btn-danger btn-remover-pagamento p-1 ml-1" title="Excluir item" data-pagamento="'+numPagamento+'"><i class="fas fa-times fa-fw"></i></button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    
    return html;
}

function inserirEventosPagamentos(numPagamento)
{
    $('.btn-remover-pagamento[data-pagamento='+numPagamento+']').click(eventoBotaoRemoverPagamentoClicado);
    $('.btn-editar-pagamento[data-pagamento='+numPagamento+']').click(eventoBotaoEditarPagamentoClicado);

}

function eventoBotaoRemoverPagamentoClicado()
{
    var numPagamento = $(this).data('pagamento');
    removerLinhaPagamento(numPagamento);

    atualizarTotaisPagamento();
}

function eventoBotaoEditarPagamentoClicado()
{
    var numPagamento = $(this).data('pagamento');

    console.log(numPagamento);
}

function removerLinhaPagamento(numPagamento)
{
    $('div[data-pagamento='+numPagamento+']').remove();

    resetarInputsMetodosPagamento();
    resetarValorTrocoPagamento();
    verificarSeHaPagamentosAdicionados();

    verificarPagamentosAdicionadosSeFalta();
}

function resetarValorTrocoPagamento()
{
    $('#valorTrocoMetodo').removeClass('text-danger').addClass('text-tema-success').html('R$ 0,00');

}

function resetarInputsMetodosPagamento()
{
    selectMetodoPagamento.val('');
    inputValorPagamento.val('');
    inputDescontoPagamento.val('');
    inputTrocoPara.val('');

    inputValorPagamento.prop('disabled', true);
    inputDescontoPagamento.prop('disabled', true);
    inputTrocoPara.prop('disabled', true);

    $('#divTrocoPara').show();
    $('#divDescontoPagamento').show();
    $('#divTrocoPagamento').show();
}

function limparDadosInputsTrocoDesconto()
{
    inputValorPagamento.val('');
    inputDescontoPagamento.val('');
    inputTrocoPara.val('');
}

function atualizarTotaisPagamento()
{

    valorTotalPagamento = calcularValorTotalPagamento();
    valorTotalDesconto = calcularDescontoTotal();
    valorTotalTroco = calcularTrocoTotal()
    valorTotalPagarLiquido = calcularValorTotalAPagar(valorTotalDesconto);

    valorTotalPagamentoTexto = formatarPreco(valorTotalPagamento);
    valorTotalDescontoTexto = formatarPreco(valorTotalDesconto);
    valorTotalTrocoTexto = formatarPreco(valorTotalTroco);
    valorTotalPagarLiquidoTexto = formatarPreco(valorTotalPagarLiquido);

    $('#valorPagamentos').html(valorTotalPagamentoTexto);
    $('#valorDescontoPagamento').html(valorTotalDescontoTexto);
    $('#valorTrocoPagamento').html(valorTotalTrocoTexto);
    $('#valorTotalPagarLiquido').html(valorTotalPagarLiquidoTexto);

    //atualizar valores nos inputs hidden

    $('#inputValorDescontos').val(valorTotalDesconto);
    $('#inputValorTrocos').val(valorTotalTroco);
    
    

}

function mostrarMensagemConfirmacaoCancelarPagamento(titulo, texto)
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
            cancelarPagamento();
    });

}

function cancelarPagamento()
{
    $('#modalPagamento').modal('hide');
    resetarInputsMetodosPagamento();
    removerTodosPagamentosAdicionados();
    esconderAvisoFaltaPagar();

    telaPagamentoAtiva = false;
}

function removerTodosPagamentosAdicionados()
{
    $('.pagamento-adicionado').remove();
}

//eventos

btnContinuarVenda.click(function(){
    irParaPagamento();
});

selectMetodoPagamento.change(function()
{
    limparDadosInputsTrocoDesconto();

    mostrarInpusMetodoPagamento();
    preencherInputValorPagamento();

    inputValorPagamento.focus();

});

btnAdicionarMetodo.click(function(){
    
    verificarMetodoSelecionado();
    
});

inputValorPagamento.keyup(function(){
    atualizarValorTrocoPagamento();
});

inputTrocoPara.keyup(function(){
    atualizarValorTrocoPagamento();
});

inputDescontoPagamento.keyup(function(){
    atualizarValorTrocoPagamento();
});

btnCancelarPagamento.click(function(){

    mostrarMensagemConfirmacaoCancelarPagamento('Cancelar pagamento?', 'Você perderá as informações que adicionou até agora');
});

//eventos atalhos

$(document).keyup(function(e){
    //tecla esc
    if(telaPagamentoAtiva && (e.wich == 27 || e.keyCode == 27))
        mostrarMensagemConfirmacaoCancelarPagamento('Cancelar pagamento?', 'Você perderá as informações que adicionou até agora');
    
});