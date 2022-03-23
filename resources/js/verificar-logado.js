
var url = (document.location.href).toLowerCase();

if (!url.match(/login/))
    loopVerificacaoLogin = setInterval(verificarSeEstaLogado, 600000);

function verificarSeEstaLogado()
{
    $.get('/usuarios/verificar_usuario_logado', function(resposta){
        var logado = resposta;

        if (logado != 1)
            avisarDesconexao();
    });

    console.log('tesdas')
}

function avisarDesconexao()
{
    mostrarAlertaToast('warning', 'Você foi desconectado, faça o login novamente')
    
    // setTimeout(mostrarMensagemDesconexaoLogin, 6000);
    setTimeout(refreshPagina, 5000);
}

function refreshPagina()
{
    document.location.reload();
}

function mostrarMensagemDesconexaoLogin()
{

    var mensagem = 'Você foi desconectado, faça o login novamente'

    $('#alrtErroMensagem').text(mensagem)
    $('#alrtErro').slideDown('fast');
}