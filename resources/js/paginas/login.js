$(document).ready(function ()
{
    var esperandoResposta = false;
    var timeout = null;

    $('form').on('submit', function (e)
    {
        e.preventDefault();

        esconderMensagemErro()

        mostrarAnimacaoEspera($('#btnEntrar'));

        var url = this.action;
        var dados = $(this).serialize();

        ativarTimeout();
        $.post(url, dados, respostaRecebida);
    });

    function ativarTimeout()
    {
        esperandoResposta = true;
        timeout = setTimeout(respostaNaoRecebida, 15000);        
    }

    function desativarTimeout()
    {
        clearTimeout(timeout);
        timeout = null;
        esperandoResposta = false;
    }

    function respostaRecebida(resposta)
    {
        if (esperandoResposta)
        {
            if (resposta.status == 1)
                window.location.href = '/';
            else
            {
                var mensagem;

                if (resposta.status == 0)
                    mensagem = 'Usuário ou senha incorretos';
                else
                    mensagem = 'Seu acesso está bloqueado, verifique com o suporte';

                mostrarMensagemErro(mensagem);
                pararAnimacaoEspera($('#btnEntrar'));
            }

            desativarTimeout();
        }
    }

    function respostaNaoRecebida()
    {
        mostrarMensagemErro('Servidor não respondeu à requisição de login');
        pararAnimacaoEspera($('#btnEntrar'));

        esperandoResposta = false;
        timeout = null;
    }

    function mostrarMensagemErro(mensagem)
    {
        $('#alrtErroMensagem').text(mensagem)
        $('#alrtErro').slideDown('fast');
    }

    function esconderMensagemErro()
    {
        $('#alrtErro').hide();
    }
});