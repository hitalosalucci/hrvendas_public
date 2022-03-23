function verificarCaractereEspecial(caractere)
{
    return caractere == '(' ||
        caractere == ')' ||
        caractere == '-';
}

function mascaraTelefone(texto)
{
    if (texto.length < 11)
        return '(00)0000-0000';
    else
        return '(00)00000-0000'
}

jQuery.fn.extend({
    mask: function (mascara, textoInicial = '')
    {
        var regExNumero = /[0-9]/;
        var charPlaceholder = '_';

        return this.each(function ()
        {
            var texto = textoInicial;

            atualizarTexto(this);

            $(this).on('keydown', function (e)
            {
                var tecla = e.key;

                var teclaCapturada = true;

                if (tecla.length == 1 && regExNumero.test(tecla))
                    adicionarCaractere(this, tecla);
                else if (tecla == 'Backspace')
                    apagarCaractere(this);
                else if (tecla == 'Delete')
                    apagarCaractere(this, true);
                else
                    teclaCapturada = false;

                if (teclaCapturada)
                    e.preventDefault();
            });

            function adicionarCaractere(input, caractere)
            {
                var mascaraString = getMascara();
                var cursorMascara = input.selectionStart;
                
                var cursorTexto = encontrarPosicaoCursorTexto(mascaraString, cursorMascara);

                if (cursorTexto > texto.length)
                    cursorMascara = null;
                else
                {
                    do 
                    {
                        cursorMascara++;
                    } while (verificarCaractereEspecial(mascaraString.charAt(cursorMascara)));
                }                    

                texto = texto.slice(0, cursorTexto) + caractere + texto.slice(cursorTexto);

                atualizarTexto(input, cursorMascara);
            }

            function apagarCaractere(input, paraFrente = false)
            {
                var mascaraString = getMascara();
                var posicaoCursorMascara = input.selectionStart;

                if (posicaoCursorMascara == 0)
                    return;

                var posicaoCursorTexto = encontrarPosicaoCursorTexto(mascaraString, posicaoCursorMascara);

                if (!paraFrente)
                {
                    texto = texto.slice(0, posicaoCursorTexto - 1) + texto.slice(posicaoCursorTexto);

                    do
                    {
                        posicaoCursorMascara--;
                    } while (verificarCaractereEspecial(mascaraString.charAt(posicaoCursorMascara)) && posicaoCursorMascara > 0);
                }
                else
                    texto = texto.slice(0, posicaoCursorTexto) + texto.slice(posicaoCursorTexto + 1);

                atualizarTexto(input, posicaoCursorMascara);
            }

            function encontrarPosicaoCursorTexto(mascara, posicaoCursorMascara)
            {
                var poiscaoCursorTexto = 0;

                for (var i = 0; i < posicaoCursorMascara; i++)
                {
                    var charMascara = mascara.charAt(i);

                    if (!verificarCaractereEspecial(charMascara))
                    poiscaoCursorTexto++;
                }

                return poiscaoCursorTexto;
            }

            $(this).focus(function ()
            {
                var input = this;
                setTimeout(function ()
                {
                    atualizarTexto(input);
                }, 0);
            });

            function atualizarTexto(input, posicaoCursor = null)
            {
                var mascaraString = getMascara();

                var contadorTexto = 0;
                var textoComMascara = '';

                for (var i = 0; i < mascaraString.length; i++)
                {
                    var charMascara = mascaraString.charAt(i);

                    if (verificarCaractereEspecial(charMascara))
                        textoComMascara += charMascara;
                    else if (contadorTexto >= texto.length)
                        textoComMascara += charPlaceholder;
                    else
                        textoComMascara += texto.charAt(contadorTexto++);

                    if (posicaoCursor == null && contadorTexto == texto.length)
                        posicaoCursor = i + 1;
                }

                input.value = textoComMascara;

                texto = trimTextoMascara(texto, mascaraString);

                input.setSelectionRange(posicaoCursor, posicaoCursor);
            }

            function trimTextoMascara(texto, mascara)
            {
                var capacidadeCaracteres = 0;

                for (var i = 0; i < mascara.length; i++)
                    if (mascara.charAt(i) == '0')
                        capacidadeCaracteres++;

                if (texto.length <= capacidadeCaracteres)
                    return texto;
                else
                    return texto.slice(0, capacidadeCaracteres);
            }

            function getMascara()
            {
                return typeof mascara == 'function' ? mascara(texto) : mascara;
            }
        });
    },

    maskVal: function ()
    {
        var valueComMascara = this[0].value;
        var valueSemMascara = '';

        for (var i = 0; i < valueComMascara.length; i++)
        {
            caractere = valueComMascara.charAt(i);
            if (!verificarCaractereEspecial(caractere))
                valueSemMascara += caractere;
        }

        return valueSemMascara;
    }
});