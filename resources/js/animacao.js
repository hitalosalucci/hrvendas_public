var iconesAnteriores = {};

function mostrarAnimacaoEspera(botao)
{
    botao.addClass('disabled');
    botao.attr('disabled', true);

    var childrenBotao = botao.children('i');

    if (childrenBotao.length > 0)
        substituirIcone(botao);
    else
        adicionarIconeAnimacao(botao);
}

function substituirIcone(botao)
{
    icone = botao.children('i').first();
    iconesAnteriores[botao.attr('id')] = icone.attr('class');
    icone.removeClass();
    icone.addClass('fas fa-spinner fa-pulse');
}

function adicionarIconeAnimacao(botao)
{
    botao.prepend('<i class="fas fa-spinner fa-pulse"></i>');
}

function pararAnimacaoEspera(botao)
{
    botao.removeClass('disabled');
    botao.attr('disabled', false);

    var idBotao = botao.attr('id');

    var icone = botao.children('i').first();
    var classAnterior = iconesAnteriores[idBotao];

    if (classAnterior != undefined)
    {
        delete iconesAnteriores[idBotao]
        icone.removeClass();

        icone.addClass(classAnterior);
    }
    else
    {
        icone.remove();
    }
}