function formatarTelefone(numTelefone)
{
    var stringTelefone = '(' + numTelefone.substr(0, 2) + ')';

    if (numTelefone.length == 11)
        stringTelefone += numTelefone.substr(2, 5) + '-' + numTelefone.substr(7, 4);
    else
        stringTelefone += numTelefone.substr(2, 4) + '-' + numTelefone.substr(6, 4);

    return stringTelefone;
}

function formatarPreco(valor)
{
    var valor_formatado = parseFloat(valor);

    valor_formatado = valor_formatado.toLocaleString("pt-BR", {style: "currency" , currency:"BRL"});

    return valor_formatado;   
}

function formatarQuantidade(valor)
{
    var valorFormatado = parseFloat(valor);

    valorFormatado = valorFormatado + ''; //converter para string

    valorFormatado = valorFormatado.replace('.', ',');

    return valorFormatado;
}

function verificarCpfValido(valor)
{
    valor = valor.trim().toString();
    
    valor = valor.replace(/[^0-9]/g, '');

    if (valor.length == 11)
        realizarVerificacaoCpf(valor);
    else if (valor.length < 11)
        return 'CPF incompleto';
    else if (valor.length > 11)
        return false;
    
}

function realizarVerificacaoCpf(cpf)
{

}

function trocarPontoPreco(valor)
{
    return valor.replace(',', '.');
}

function mostrarAlertaToast(icone, titulo)
{
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      
      Toast.fire({
        icon: icone,
        title: titulo
      })
}

function mostrarAlertaCentro(icone, titulo)
{
    Swal.fire({
        position: 'center',
        icon: icone,
        title: titulo,
        showConfirmButton: false,
        timer: 2000
      })
}