$(document).ready(function ()
{
    $('form').submit(function (e)
    {
        e.preventDefault();

        mostrarAnimacaoEspera($('#btnSalvar'));

        if (verificarCamposPreenchidos() && verificarCampoRepeticaoValido())
            enviarFormulario(this.action, $(this).serialize());
        else
            pararAnimacaoEspera($('#btnSalvar'));
    });

    function verificarCamposPreenchidos()
    {
        var tudoPreenchido = true;

        $('input').each(function ()
        {
            if (this.value == '')
                tudoPreenchido = false;
        });

        if (! tudoPreenchido)
            mostrarAviso('Todos os campos precisam ser preenchidos.');

        return tudoPreenchido;
    }

    function verificarCampoRepeticaoValido()
    {
        var novaSenha = $('#inputNovaSenha').val();
        var repetirSenha = $('#inputRepetirNovaSenha').val();

        if (novaSenha == repetirSenha)
            return true;
        else
        {
            mostrarAviso('Senha digitada no campo de repetição da senha está diferente da nova senha.');
            return false;
        }
    }

    function mostrarAviso(mensagem)
    {
        $('#txtAviso').text(mensagem);
        $('#divAviso').slideDown();
    }

    function enviarFormulario(url, dados)
    {
        $.post(url, dados, callbackEnvio);
    }

    function callbackEnvio(resposta)
    {
        pararAnimacaoEspera($('#btnSalvar'));

        if (resposta.status == 1)
            mostrarSucesso();
        else
            mostrarErro(resposta.erro);
    }

    function mostrarSucesso()
    {
        $('#divSucesso').slideDown();

        limparInputs();
    }

    function limparInputs()
    {
        $('input').each(function ()
        {
            $(this).val('');
        });
    }

    function mostrarErro(mensagem)
    {
        $('#txtErro').text(mensagem+'.');
        $('#divErro').slideDown();
    }

    $('input').change(esconderMensagens);

    function esconderMensagens()
    {
        $('.alert').slideUp();
    }
});