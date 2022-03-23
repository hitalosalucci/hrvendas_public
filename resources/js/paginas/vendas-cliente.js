listaClientesDisponiveis = null;
selectCliente = $('#selectCliente');

$(document).ready(function(){
    iniciarSelectPicker(selectCliente);
});

// funções

function abrirModalClientes()
{
    $('#modalClientes').modal('show');

    carregarClientes();

    $("[data-id='selectCliente']").focus();
}

function carregarClientes()
{
    $.get('/clientes/lista', function (resposta)
    {
        listaClientesDisponiveis = resposta;
        popularListaComClientes();
    });
}

function popularListaComClientes()
{
    var html = '';
    var pesquisa = $('#inputPesquisarClientes').val();

    $.each(listaClientesDisponiveis, function (index, cliente)
    {

        cliente.telefone == null ? telefone = 'Nenhum tel cadastrado' : telefone = formatarTelefone(cliente.telefone);
        cliente.cpf == null ? cpf = 'Nenhum CPF cadastrado' : cpf = cliente.cpf;

        if (pesquisa == '' ||
            cliente.nome.toLowerCase().includes(pesquisa.toLowerCase()) ||
            cpf.includes(pesquisa) ||
            telefone.includes(pesquisa))
        {
            html += construirItemListaClientes(cliente);
        }
    });

    $('#listaClientes').html(html);
    inserirEventosBotoesSelecionarCliente();
}

function construirItemListaClientes(cliente)
{
    cliente.telefone == null ? telefone = 'Nenhum tel cadastrado' : telefone = formatarTelefone(cliente.telefone)
    cliente.cpf == null ? cpf = 'Nenhum CPF cadastrado' : cpf = cliente.cpf

    var html = '';

    html += '<div class="list-group-item border-bottom"><div class="row">';
    html += '<div class="col">'+cliente.nome+'</div>';
    html += '<div class="col">'+cpf+'</div>';
    html += '<div class="col">'+telefone+'</div>';
    html += '<div class="col-2"><button class="btn btn-tema-primary btn-selecionar-cliente float-right" title="Selecionar cliente '+cliente.nome+'" data-cliente="'+cliente.id+'"><i class="fas fa-user-check"></i> <small>Selecionar</small></button></div>';
    html += '</div></div>';

    return html;
}

function inserirEventosBotoesSelecionarCliente()
{
    $('.btn-selecionar-cliente').click(eventoBotaoSelecionarClienteClicado);
}

function eventoBotaoSelecionarClienteClicado()
{
    var id = $(this).data('cliente');
    var cliente = getClienteLista(id);

    fecharModalClientes();
    inserirInformacoesClienteInicio(cliente);
}

function getClienteLista(id)
{
    var retorno = null;

    $.each(listaClientesDisponiveis, function (index, cliente)
    {
        if (cliente.id == id)
            retorno = cliente;
    });

    return retorno;
}

function inserirInformacoesClienteInicio(cliente)
{

    $('#textoClienteSelecionado').text(cliente.nome);

    //verificando id do cliente
    idCliente = cliente.id == 0 || null ? '' : cliente.id;
    $('#inputHiddenClienteSelecionado').val(idCliente);
}

function limparSelecaoCliente()
{
    $('#textoClienteSelecionado').text('CONSUMIDOR NÃO IDENTIFICADO');

    $('#inputHiddenClienteSelecionado').val('');

    fecharModalClientes();
}

function limparCadastroCliente()
{
    $('#inputPesquisarClientes').val('');

    pagina.modalFormulario.limpar();
}

function fecharModalClientes()
{
    $('#modalClientes').modal('hide');
}

function abrirModalCadastroCliente()
{

    $('#modalCadastro').modal('show');

    pagina.lista.preencherSelectEstados();

}

//eventos

$('#linkSelecionarCliente').click(function(event){
    event.preventDefault();
    abrirModalClientes();
});

$('#btnLimparCliente').click(function(){
    limparSelecaoCliente();
});

$('#btnNovoCliente').click(function(){
    
    fecharModalClientes();

    abrirModalCadastroCliente();
});

$('#modalCadastro').on('hidden.bs.modal', function () {
    abrirModalClientes();
});

$('#inputPesquisarClientes').on('keyup', popularListaComClientes);

//isso resolve o bug de quando abre um modal por cima do outro, o scroll some, pois a classe modal-open some
$('#modalClientes').on('hidden.bs.modal', function () {
    $("body").addClass("modal-open");
});