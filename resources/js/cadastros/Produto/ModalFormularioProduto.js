class ModalFormularioProduto extends ModalFormulario
{
    
    inserirEventos()
    {
        super.inserirEventos();

        this.verificarCodigosDigitados();
    }

    preencher(objeto)
    {
        
        var idMarca = objeto.marca_id == '0' ? '' : objeto.marca_id;

        this.esconderErros();
        $('#inputNome').val(objeto.nome);
        $('#inputPreco').val(objeto.preco.toFixed(2));
        $('#selectUnidadeProduto').val(objeto.unidade);
        $('#selectCategorias').val(objeto.categoria_id);
        $('#selectMarcas').val(idMarca);
        $('#inputCodigoProduto').val(objeto.codigo_produto);
        $('#inputCodigoBarrasProduto').val(objeto.codigo_barras);
        $('#inputDescricaoProduto').val(objeto.descricao);

    }

    enviarForm(form, e)
    {
        this.formatarPreco();

        super.enviarForm(form, e);

    }

    formatarPreco()
    {
        var precoComVirgula = $('#inputPreco').val();
        var precoComPonto = trocarPontoPreco(precoComVirgula);
        $('#inputPrecoHidden').val(precoComPonto);
    }

    //verificar código do produto e código de barras
    verificarCodigosDigitados()
    {

        $('#inputCodigoProduto').change(function()
        {
            var valorCodigo = $(this).val();
            
            var dados = {
                'codigo-produto' : valorCodigo,
            };

            var nomeCampo = 'codigo-produto';

            $.get('produtos/verificar-codigo/', dados)
            .then(function(resposta){
                callbackCodigoProduto(nomeCampo, resposta)
            })
            .fail(function( ){
                console.log('falhou');
            });

        });

        $('#inputCodigoBarrasProduto').change(function()
        {
            var valorCodigoBarras = $(this).val();
            
            var dados = {
                'codigo_barras-produto' : valorCodigoBarras,
            };

            var nomeCampo = 'codigo_barras-produto';

            $.get('produtos/verificar-codigo-barras/', dados)
            .then(function(resposta){
                callbackCodigoProduto(nomeCampo, resposta)
            })
            .fail(function( ){
                console.log('falhou');
            });

        });

        function callbackCodigoProduto(nomeCampo, resposta)
        {
            if(resposta)
                mostrarErroProduto(nomeCampo);
            else
                esconderErrosProduto(nomeCampo);
        }

        function mostrarErroProduto(nomeCampo)
        {
            var campo = $('[name='+nomeCampo+']');
            campo.next().text('Já existe um produto cadastrado com esse código de barras');
            campo.addClass('is-invalid');

        }

        function esconderErrosProduto(nomeCampo)
        {
            var campo = $('[name='+nomeCampo+']');

            campo.removeClass('is-invalid');
        }

    }
    

}