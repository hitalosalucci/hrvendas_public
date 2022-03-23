class ListaVenda extends Lista
{
    constructor(lista)
    {
        super(lista);

        this.listaVendedores = null;

        this.carregarVendedores();
        this.inserirEventosPesquisa();

    }

    popularListaObjetos(objetos)
    {
        var html = '';

        var valorTotalVendas = 0;
        var valorTotalTroco = 0;
        var valorTotalDesconto = 0;
        var quantidadeVendas = 0;

        for (var i = 0; i < objetos.length; i++)
        {
            var objeto = objetos[i];

            if (this.filtrar(objeto))
            {
                
                //quantidade de vendas recebe mais um
                quantidadeVendas++;

                html += this.construirHtmlObjeto(objeto);

                valorTotalVendas += objeto.valor_total; 
                
                for (var j = 0; j < objeto.pagamentos.length; j++){
                    var trocoPara = objeto.pagamentos[j].troco_para == null || '' ? objeto.pagamentos[j].valor_pago : objeto.pagamentos[j].troco_para;
                    valorTotalTroco += (trocoPara - objeto.pagamentos[j].valor_pago);

                    var desconto = objeto.pagamentos[j].desconto == null || '' ? 0 : objeto.pagamentos[j].desconto;
                    valorTotalDesconto += desconto;
                }
            }

        }


        this.lista.html(html);
        this.inserirEventosLista();
        this.inserirTotalQuantidadeVendas(quantidadeVendas);
        this.inserirValorTotalVendas(valorTotalVendas);
        this.inserirValorTotalTroco(valorTotalTroco);
        this.inserirValorTotalDesconto(valorTotalDesconto);
    }

    inserirEventosPesquisa()
    {
        var self = this;

        $('#inputPesquisaVendaPorCliente').on('keyup', function ()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });

        $('#selectPesquisaVendaPorVendedor').change(function ()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });

        $('#inputDataVenda').change(function ()
        {
            self.pagina.carregarObjetos();
        });
    }

    construirHtmlObjeto(objeto)
    {

        var html = '';

        html += '<div class="list-group-item linha-tabela border-bottom">';
        html += '<div class="row">';
        html += '<div class="col-2">'+formatarPreco(objeto.valor_total)+'</div>';
        
        html += preencherPagamentos(objeto.pagamentos)
        
        html += '<div class="col text-truncate" title="'+objeto.usuario+'">'+objeto.usuario+'</div>';
        html += '<div class="col text-truncate" title="'+objeto.cliente+'">'+objeto.cliente+'</div>';
        html += '<div class="col-2">'+objeto.data_update+'</div>';
        html += '<div class="col-auto">';
        html += '<button class="btn btn-icon-tema-primary btn-ver" data-item="'+objeto.id_venda+'" title="Editar"><i class="fas fa-eye fa-lg"></i></button>';
        html += '<a href="/vendas/'+objeto.id_venda+'/imprimir" target="_blank" class="btn btn-icon-tema-primary" title="Imprimir"><i class="fas fa-print fa-lg"></i></a>';
        html += '<a href="/vendas/'+objeto.id_venda+'/edit" class="btn btn-icon-tema-primary" data-item="'+objeto.id_venda+'" title="Editar"><i class="fas fa-edit fa-lg"></i></a>';
        html += '<button class="btn btn-icon-tema-primary btn-apagar" data-item="'+objeto.id_venda+'" title="Apagar"><i class="fas fa-trash-alt fa-lg"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        return html;

        function preencherPagamentos(pagamentos)
        {

            var htmlPagamentos = '';
            htmlPagamentos += '<div class="col">';

            console.log(pagamentos);


            if (pagamentos.length == 1)
                htmlPagamentos += pagamentos[0].metodo_pagamento+': '+formatarPreco(pagamentos[0].valor_pago);
            else{
                
                $.each(pagamentos, function (index, pagamento)
                {
                    htmlPagamentos += pagamento.metodo_pagamento+': '+formatarPreco(pagamento.valor_pago)+'<br>';
                });

            }

            htmlPagamentos += '</div>';

            return htmlPagamentos;
        }

    }

    inserirEventosLista()
    {
        var self = this;

        $('.btn-ver').click(eventoBotaoVerClicado);

        function eventoBotaoVerClicado()
        {

            var id = $(this).data('item');
            var venda = pagina.getObjeto(id);

            self.abrirModalVerVenda(venda);
        }

        $('.btn-apagar').click(function (e)
        {
            var idObjeto = $(this).data('item');
            self.pagina.mostrarApagar(idObjeto);

        });

        $('.btn-icon-tema-primary').click(function (e)
        {
            e.stopPropagation();
        });

        this.inserirTotalQuantidadeVendas();
    }

    abrirModalVerVenda(venda){
        
        $('#modalVerVenda').modal('show');

        mudarTituloModalVer(venda.valor_total);

        preencherModalVerVenda(venda);

        function mudarTituloModalVer(valor)
        {
            $('#modalVerVenda .modal-title').html('Detalhes da venda '+'<small class="text-muted">'+formatarPreco(valor)+'</small>');

        }

        function preencherModalVerVenda(venda)
        {   
            var itensVenda = '';
            $.each(venda.itens, function (index, item)
            {
                itensVenda += construirHtmlVerItens(item);
            }); 

            var itensPagamento = '';
            $.each(venda.pagamentos, function (index, ItemPagamento)
            {
                itensPagamento += construirHtmlVerPagamentos(ItemPagamento);
            }); 

            $('#spanQntItensTotais').text(venda.quantidade_itens);
            $('#spanDataUpdate').text(venda.data_update);

            $('#verValorVenda').val(formatarPreco(venda.valor_total));
            $('#verDataVenda').val(venda.data_criacao);
            $('#verVendedor').val(venda.usuario);
            $('#verCliente').val(venda.cliente);
            $('#verDescontos').val(formatarPreco(venda.valor_desconto));

            $('#divItensVenda').html(itensVenda);
            $('#divItensPagamento').html(itensPagamento);
        }
        
        function construirHtmlVerItens(item)
        {
            var html = '';
            
            html += '<input type="text" id="verQntItens" class="form-control col-2" value="'+formatarQuantidade(item.quantidade)+'x'+'" readonly>';
            html += '<input type="text" id="verProdutoItens" class="form-control col-6" value="'+item.produto+'" readonly>';
            html += '<input type="text" id="verValorPagoItens" class="form-control col-4" value="'+formatarPreco(item.valor_pago)+'" readonly>';
            html += '<div class="col-12 mb-2"> </div>' 

            return html;
        }

        function construirHtmlVerPagamentos(pagamento)
        {   
            var desconto = pagamento.desconto ==  null ? 0 : pagamento.desconto; 
            var html = '';
            
            html += '<input type="text" id="verQntItens" class="form-control col-6" value="'+pagamento.metodo_pagamento+'" readonly>';
            html += '<input type="text" id="verProdutoItens" class="form-control col-3" value="'+formatarPreco(pagamento.valor_pago)+'" readonly>';
            html += '<input type="text" id="verValorPagoItens" class="form-control col-3" value="'+formatarPreco(desconto)+'" readonly>';
            html += '<div class="col-12 mb-2"> </div>' 

            return html;
        }

    }

    carregarVendedores()
    {
        var self = this;

        $.get('/usuarios/lista', function (resposta)
        {
            self.listaVendedores = resposta;
            self.popularSelectVendedores();
        });
    }

    popularSelectVendedores()
    {
        var html = '';

        for (var i = 0; i < this.listaVendedores.length; i++)
            html += this.construirHtmlOptionVendedores(this.listaVendedores[i]);

        $('#selectPesquisaVendaPorVendedor').html('<option value="0" selected>Todos vendedores</option>' + html);

    }

    filtrar(objeto)
    {
        var textoPesquisaCliente = $('#inputPesquisaVendaPorCliente').val().trim();
        var vendedorFiltro = $('#selectPesquisaVendaPorVendedor').val();

        return this.verificarClienteDaVendaPesquisado(objeto, textoPesquisaCliente) && this.verificarVendededorPesquisado(objeto, vendedorFiltro);
    }

    verificarClienteDaVendaPesquisado(objeto, textoPesquisaCliente)
    {
        return textoPesquisaCliente == '' || objeto.cliente.toLowerCase().includes(textoPesquisaCliente.toLowerCase());
    }

    verificarVendededorPesquisado(objeto, vendedor)
    {
        return vendedor == 0 || objeto.usuario_id == vendedor;
    }

    construirHtmlOptionVendedores(vendedor)
    {
        return '<option value="'+vendedor.id+'">'+vendedor.nome+'</option>';
    }

    inserirTotalQuantidadeVendas(totalVendas)
    {        
        $('#spanQuantidadeVendas').html(totalVendas);
    }

    inserirValorTotalVendas(valorTotalVendas)
    {
        $('#spanValorTotalVendas').html(formatarPreco(valorTotalVendas));
    }

    inserirValorTotalTroco(valorTotalTroco)
    {
        $('#spanValorTotalTroco').html(formatarPreco(valorTotalTroco));
    }

    inserirValorTotalDesconto(valorTotalDesconto)
    {
        $('#spanValorTotalDesconto').html(formatarPreco(valorTotalDesconto));
    }

}