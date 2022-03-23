<?php

//arquivo para colocar funções personalizadas

if (!function_exists('formatarTelefone'))
{

    function formatarTelefone($telefone)
    {
        $telefoneFormatado = substr_replace($telefone, '(', 0, 0);
        $telefoneFormatado = substr_replace($telefoneFormatado, ') ', 3, 0);

        return $telefoneFormatado;
    }

}

if (!function_exists('formatarPreco'))
{

    function formatarPreco($preco)
    {
        return 'R$ '.number_format($preco, 2, ',', '.');
    }

}

if (!function_exists('formatarCnpj'))
{

    function formatarCnpj($cnpj)
    {
        $cnpjFormatado = substr_replace($cnpj, '.', 2, 0);
        $cnpjFormatado = substr_replace($cnpjFormatado, '.', 6, 0);
        $cnpjFormatado = substr_replace($cnpjFormatado, '/', 10, 0);
        $cnpjFormatado = substr_replace($cnpjFormatado, '-', 15, 0);

        return $cnpjFormatado;
    }

}

if (!function_exists('formatarQuantidade'))
{
    function formatarQuantidade($quantidade)
    {
        $quantidadeFormatada = floatval($quantidade);
        $quantidadeFormatada = str_replace('.', ',', $quantidadeFormatada);

        return $quantidadeFormatada;
    }
}

?>