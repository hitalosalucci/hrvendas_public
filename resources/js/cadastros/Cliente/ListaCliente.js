class ListaCliente extends Lista
{
    constructor (pagina)
    {
        super(pagina);

        this.inserirEventosPesquisa();
    }

    inserirEventosPesquisa()
    {
        var self = this;

        $('#inputPesquisaCliente').on('keyup', function()
        {
            self.popularListaObjetos(self.pagina.listaObjetos);
        });

    }

    filtrar(objeto)
    {
        var textoPesquisa = $('#inputPesquisaCliente').val();

        var telefone = objeto.telefone == null || objeto.telefone == '' ? telefone = 'Nenhum tel cadastrado' : telefone = formatarTelefone(objeto.telefone);
        var cpf = objeto.cpf == null || objeto.cpf == '' ? cpf = 'Nenhum CPF cadastrado' : cpf = objeto.cpf;

        return textoPesquisa == '' ||
            objeto.nome.toLowerCase().trim().includes(textoPesquisa.toLowerCase().trim()) ||
            cpf.toLowerCase().includes(textoPesquisa.toLowerCase()) ||
            telefone.toLowerCase().includes(textoPesquisa.toLowerCase());
    }

     construirHtmlObjeto(objeto)
    {
        var html = '';

        var telefone = objeto.telefone == null || objeto.telefone == '' ? telefone = 'Nenhum tel cadastrado' : telefone = formatarTelefone(objeto.telefone);
        var cpf = objeto.cpf == null || objeto.cpf == '' ? cpf = 'Nenhum CPF cadastrado' : cpf = objeto.cpf;

        html += '<div class="list-group-item border-bottom">';
        html += '<div class="row">';
        html += '<div class="col">'+objeto.nome+'</div>';
        html += '<div class="col">'+cpf+'</div>';
        html += '<div class="col">'+telefone+'</div>';
        html += '<div class="col-auto">';
        html += '<button class="btn btn-icon-tema-primary btn-ver" data-item="'+objeto.id+'" title="Ver informações"><i class="fas fa-eye fa-lg"></i></button>';
        html += '<button class="btn btn-icon-tema-primary btn-editar" data-item="'+objeto.id+'" title="Editar"><i class="fas fa-edit fa-lg"></i></button>';
        html += '<button class="btn btn-icon-tema-primary btn-apagar" data-item="'+objeto.id+'" title="Apagar"><i class="fas fa-trash-alt fa-lg"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        return html; 
    }

    inserirEventosLista(){
        
        super.inserirEventosLista();

        this.inserirEventosBotaoVer();

        this.preencherSelectEstados();
    }

    preencherSelectEstados()
    {

        getListaEstados();
        
        function getListaEstados()
        {
            $.get('/estados/lista', function (listaEstados)
            {
                popularSelectEstados(listaEstados);
            });
        }

        function popularSelectEstados(listaEstados)
        {
            var html = '';

            html += '<option value="" disabled selected>Selecione um estado</option>'

            $.each(listaEstados, function (index, estado)
            {
                html += '<option value="'+estado.id+'" title="'+estado.nome+'">'+estado.sigla+'</option>';
            });

            $('#selectEstado').html(html);
        }

    }


    inserirEventosBotaoVer()
    {

        $('.btn-ver').click(eventoBotaoVerClicado);

        function eventoBotaoVerClicado()
        {

            var id = $(this).data('item');
            var cliente = pagina.getObjeto(id);

            abrirModalVerCliente(cliente);
        }

        function abrirModalVerCliente(cliente)
        {
            $('#modalVerCliente').modal('show');

            mudarTituloModalVer(cliente.nome);
            preencherModalVerCliente(cliente.id);
        }

        function mudarTituloModalVer(nomeCliente)
        {
            $('#modalVerCliente .modal-title').text('Informações completas de '+nomeCliente);
        }

        function preencherModalVerCliente(idCliente)
        {

            var cliente = pagina.getObjeto(idCliente);

            // tratar informações
            var telefone = cliente.telefone == null || cliente.telefone == '' ? telefone = 'Nenhum tel cadastrado' : telefone = formatarTelefone(cliente.telefone);
            var telefone2 = cliente.telefone2 == null || cliente.telefone2 == '' ? telefone2 = 'Nenhum tel cadastrado' : telefone2 = formatarTelefone(cliente.telefone2);
            var cpf = cliente.cpf == null || cliente.cpf == '' ? 'Nenhum CPF cadastrado' : cliente.cpf;
            var identidade = cliente.identidade == null || cliente.identidade == '' ? identidade = 'Nenhuma identidade cadastrada' : identidade = cliente.identidade;
            var data_nascimento = cliente.data_nascimento == null || cliente.data_nascimento == '' ? data_nascimento = 'Nenhuma data cadastrada' : data_nascimento = cliente.data_nascimento;


            $('#verNome').val(cliente.nome);
            $('#verCpf').val(cpf);
            $('#verIdentidade').val(identidade);
            $('#verTelefone').val(telefone);
            $('#verTelefone2').val(telefone2);
            $('#verDataNascimento').val(data_nascimento);
            
            $('#verEstado').val(cliente.endereco_estado);
            $('#verCidade').val(cliente.endereco_cidade);
            $('#verBairro').val(cliente.endereco_bairro);
            $('#verRua').val(cliente.endereco_rua);
            $('#verNumero').val(cliente.endereco_numero);
            $('#verReferencia').val(cliente.endereco_referencia);

            $('#spanDataCadastro').text(cliente.data_cadastro);
            $('#spanDataAlteracao').text(cliente.ultima_alteracao);
        }

    }

}