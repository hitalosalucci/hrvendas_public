class ModalFormularioCliente extends ModalFormulario
{
    inserirEventos()
    {
        
        //super.inserirEventos();
        
        var self = this;

        $('#formCadastro').submit(function (e)
        {
            e.preventDefault();

            if (self.verificarCamposEndereco())
                self.enviarForm(this, e);

        });

        $('#modalCadastro').on('hidden.bs.modal', function ()
        {
            pagina.modalFormularioFechado();
        });

        
        this.inserirEventoCamposEndereco();
    }

    preencher(objeto)
    {
        this.esconderErros();

        $('#inputNome').val(objeto.nome);
        $('#inputCpf').val(objeto.cpf);
        $('#inputIdentidade').val(objeto.identidade);
        $('#inputTelefone').val(objeto.telefone);
        $('#inputTelefone2').val(objeto.telefone2);
        $('#inputDataNascimento').val(objeto.data_nascimento);


        $('#selectEstado').val(objeto.endereco_estado_id);
        $('#inputCidade').val(tratarValorRecebido(objeto.endereco_cidade));
        $('#inputBairro').val(tratarValorRecebido(objeto.endereco_bairro));
        $('#inputRua').val(tratarValorRecebido(objeto.endereco_rua));
        $('#inputNumero').val(tratarValorRecebido(objeto.endereco_numero));
        $('#inputReferencia').val(tratarValorRecebido(objeto.endereco_referencia));

        function tratarValorRecebido(valor)
        {

            if (valor == 'Sem cidade' ||
                valor == 'Sem bairro' ||
                valor == 'Sem rua' ||
                valor == 'Sem referência' ||
                valor == 'Sem estado' ||
                valor == 'S/N°'
            )
                return '';
            else
                return valor;
        }
        
    }

    enviarForm(form, e)
    {
        super.enviarForm(form, e);
    }

    inserirEventoCamposEndereco()
    {
        $('.obrigatorio').change(this.verificarCamposEndereco);
    }

    verificarCamposEndereco()
    {

        var contadorCamposVazios = 0;

        $('.obrigatorio').each(function(index, campo){
            var valorCampo = $('#'+campo.id).val();
            
            if(valorCampo == '' || valorCampo == null)
                contadorCamposVazios ++;
        });

        if(contadorCamposVazios == 0 || contadorCamposVazios == 5){
            removerAlertaEnderecoIncompleto();
            var resposta = true;
        }
        else{
            mostrarAlertaEnderecoIncompleto();
            var resposta = false;
        }

        return resposta;


            function mostrarAlertaEnderecoIncompleto()
            {
                $('#alertaEndereco').removeClass('invisible');
            }

            function removerAlertaEnderecoIncompleto()
            {
                $('#alertaEndereco').addClass('invisible');
            }
            
    }
}