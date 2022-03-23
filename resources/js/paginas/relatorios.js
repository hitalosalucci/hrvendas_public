$(document).ready(function(){
    
    listaVendas = null;
    listaValoresVendasPorDia = null;

    var graficoValorVendasPorDia = null;
    var graficoQuantidadeProdutosPorDia = null;
    
    carregarDadosRelatorio();

    $('.inputs-data').change(function () {
        carregarDadosRelatorio();
    });

    function carregarDadosRelatorio()
    {
        var dataInicial = $('#inputDataInicial').val();
        var dataFinal = $('#inputDataFinal').val();

        var enderecoLista = '/relatorios/lista_vendas'+'?dataInicial='+dataInicial+'&'+'dataFinal='+dataFinal;

        $.get(enderecoLista, function(resposta){
            listaVendas = resposta;
            calcularValores(listaVendas);
        })
        .fail(function(error){
            mostrarAlertaToast('error', 'Erro '+error.status+'. Falha ao carregar informações do peŕiodo');
        });

        getValorVendaDia();
    }

    function calcularValores(listaVendas)
    {   
        var diasIntervaloDatas = calcularDiasIntervaloDatas();

        var valorVendas = 0;
        var quantidadeProdutos = 0;
        var quantidadeVendas = 0;

        $.each(listaVendas, function (index, venda)
        {   
            //calcular valor total de vendas
            valorVendas += venda.valor_total;
            
            //calcular total de produtos vendidos
            quantidadeProdutos += venda.quantidade_itens;
            
            //calcular Quantidade de Vendas - somar mais um a cada venda
            quantidadeVendas++;
        });

        //calcular ticket médio
        var ticketMedio = Number(valorVendas)/Number(quantidadeVendas);

        if (isNaN(ticketMedio)) {
            ticketMedio = 0;
        }

        //preencher boxes com os valores
        $('#spanTotalVendas').text(formatarPreco(valorVendas));

        $('#spanQuantidadeVendas').text(quantidadeVendas);

        $('#spanTicketMedio').text(formatarPreco(ticketMedio));

        $('#spanQuantidadeItens').text(quantidadeProdutos);

        $('#spanDiasIntervalo').text(diasIntervaloDatas);
        
    }

    function calcularInformacoesVendasPorDia(listaValoresVendasPorDia)
    {

        var valorTotalDia = 0;
        var valoresTotaisDiaArray = [];

        var quantidadeTotalDia = 0;
        var quantidadesTotaisDiaArray = [];
        // console.log(listaValoresVendasPorDia);
        $.each(listaValoresVendasPorDia, function(data, vendas)
        {   
            //zerando a variavel a cada repetição
            valorTotalDia = 0;
            quantidadeTotalDia = 0;

            $.each(vendas, function (index, venda)
            {
                valorTotalDia += venda.valor_total;
                quantidadeTotalDia += venda.quantidade_itens;
            });

            valoresTotaisDiaArray.push(valorTotalDia);

            quantidadesTotaisDiaArray.push(quantidadeTotalDia);

        });

        var datasIntervaloSelecionado = separarDatasIntervalo();

        if (graficoValorVendasPorDia != null)
            graficoValorVendasPorDia.destroy();

        if (graficoQuantidadeProdutosPorDia != null)
            graficoQuantidadeProdutosPorDia.destroy();

        //chama as funções que irão para gerar os gráficos
        gerarGraficoValorVendasPorDia(datasIntervaloSelecionado, valoresTotaisDiaArray);
        
        gerarGraficoQntProdutosVendidosPorDia(datasIntervaloSelecionado, quantidadesTotaisDiaArray);

    }


    function getValorVendaDia()
    {
        var dataInicial = $('#inputDataInicial').val();
        var dataFinal = $('#inputDataFinal').val();

        var endereco = '/relatorios/vendas_por_dia'+'?dataInicial='+dataInicial+'&'+'dataFinal='+dataFinal;

        $.get(endereco, function(resposta){
            listaValoresVendasPorDia = resposta;
           
            calcularInformacoesVendasPorDia(listaValoresVendasPorDia); 
        });

    }

    function separarDatasIntervalo()
    {
        var dataInicial = $('#inputDataInicial').val();
        var dataFinal = $('#inputDataFinal').val();

        var qntDiasIntervaloDatas = calcularDiasIntervaloDatas();

        var datasSelecionadas = [];
        
        var dataInicialSomada = dataInicial;
        for (let i = 0; i <= qntDiasIntervaloDatas; i++) {
            
            datasSelecionadas.push(formatarData(dataInicialSomada)); 

            dataInicialSomada = somarMaisUmDiaNaData(dataInicialSomada);
            
        }
        
        return datasSelecionadas;
        
    }

    function somarMaisUmDiaNaData(data)
    {
        var partesData = data.split("-");
        var ano = partesData[0];
        var mes = partesData[1]-1;
        var dia = partesData[2];

        var dataDate = new Date(ano, mes, dia);

        let dataSomada = new Date(dataDate);

        dataSomada.setDate(dataDate.getDate()+1);

        //formatar data para o padrão de data
        var diaFormado = ("0" + dataSomada.getDate()).slice(-2);
        var mesFormado = ("0" + (dataSomada.getMonth()+1)).slice(-2);
        var anoFormado = dataSomada.getFullYear();

        var dataFormada = anoFormado + '-' + mesFormado + '-' + diaFormado;

        return dataFormada;

    }

    function formatarData(data)
    {
        var partesData = data.split("-");
        var ano = partesData[0];
        var mes = partesData[1];
        var dia = partesData[2];

        var dataFormatada = dia + '/' + mes + '/' + ano;

        return dataFormatada;
    }

    function calcularDiasIntervaloDatas()
    {
        var dataInicial = $('#inputDataInicial').val();
        var dataFinal = $('#inputDataFinal').val();

        var dataInicialDate = new Date(dataInicial);
        var dataFinalDate = new Date(dataFinal);

        //calular o intervalo de dias no formato time e depois converter para dias.
        var intervaloDatasTime = Math.abs(dataFinalDate.getTime() - dataInicialDate.getTime());;
        var intervaloDatasEmDias = Math.ceil(intervaloDatasTime / (1000 * 3600 * 24));

        return intervaloDatasEmDias;
    }

    function gerarGraficoValorVendasPorDia(datasEixoX, valorVendasEixoY)
    {

        var ctx = $('#graficoValorVendasPorDia');

        var data = {
            type: 'bar',
            data: {
                labels: datasEixoX,
                datasets: [{
                    
                    label: 'Valor das vendas',

                    data: valorVendasEixoY,
                    
                    backgroundColor: 'rgba(23, 69, 120, 1)',

                }]
            },
            options: {

                title: {
                    display: true,
                    text: 'Resultado de vendas por dia',
                    fontSize: 20,
                },

                legend: {
                    display: true,
                    position: 'right',
                },

                tooltips: {
                    mode: 'point',
                    
                    //presonalizando tootip e adicionando moeda
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
        
                            if (label) {
                                label += ': ';
                            }
                            label += formatarPreco(tooltipItem.yLabel);
                            return label;
                        }
                    }
                },

                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            
                            //incluir formato dinheiro no valor
                            callback: function(value, index, values) {
                                return formatarPreco(value);
                            }
                        }
                    }]
                },

                //desativar as animações
                hover: {
                    animationDuration: 0 
                },

                responsiveAnimationDuration: 0
            }
        };

        graficoValorVendasPorDia = new Chart(ctx, data);

    }

    function gerarGraficoQntProdutosVendidosPorDia(datasEixoX, qntVendasEixoY)
    {
        var ctx = $('#graficoQntProdutosVendidosPorDia');
        
        var data = {
            type: 'line',
            data: {
                labels: datasEixoX,
                datasets: [{
                    
                    label: 'Qnt produtos vendidos',

                    data: qntVendasEixoY,
                    
                    backgroundColor: 'rgba(23, 69, 120, 0)',

                    borderColor: 'rgba(23, 69, 120, 1)'

                }]
            },
            options: {

                title: {
                    display: true,
                    text: 'Produtos vendidos',
                    fontSize: 20,
                },

                legend: {
                    display: true,
                    position: 'right',
                },

                tooltips: {
                    mode: 'point',
                },

                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },

                //desativar as animações
                hover: {
                    animationDuration: 0 
                },

                responsiveAnimationDuration: 0
            }
        };

        graficoQuantidadeProdutosPorDia = new Chart(ctx, data);

    }
    
});