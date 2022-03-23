<?php

$cliente = $venda->cliente;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('font_awesome/css/all.min.css') }}">


    <title>Impressão de cupom da venda</title>

    <link rel="stylesheet" href="{{ asset('estilo/impressao.css') }}">
</head>

<body onload="fecharAposImpressao()">

    <script>

        function fecharAposImpressao()
        {
            window.print();
            window.addEventListener("afterprint", function(event) { window.close(); });
        }

    </script>

    <div class="centralizado nome-empresa">
        {{ $empresa->nome }}
    </div>
    
    @section ('cabecalho')
        
        <div class="dados-empresa">CNPJ: {{ formatarCnpj($empresa->cnpj) }}</div>
        
        <?php $enderecoEmpresa = $empresa->endereco ?>

        <div>
            <small style="font-size: 8pt">
                {{$enderecoEmpresa->rua}},

                @if($enderecoEmpresa->numero != 0)
                {{$enderecoEmpresa->numero}},
                @endif 

                {{$enderecoEmpresa->bairro}} <br>
                {{$enderecoEmpresa->cidade->nome}} - {{$enderecoEmpresa->estado->sigla}}
            </small>    
        </div>
        
        <div>
            <small><i class="fas fa-phone-alt"></i></small>
            <small>{{ isset($empresa->telefone) ? formatarTelefone($empresa->telefone) : null}}</small> 
            <small>{{ isset($empresa->telefone2) ? ' / '.formatarTelefone($empresa->telefone2) : null}}</small> 
        </div>

    @show

    <hr class="tracejada">

    <!-- fim header impressao -->

    <br>
    
    <div>
        <div class="coluna-1">ITENS</div>
        <div class="coluna-2">VALOR</div>
    </div>

    <?php $quantidadeDeItens = 0; ?>
    @foreach ($venda->itens as $item)
        
        <?php

            if ( $item->unidade_preco == 'UN' )
                $quantidadeDeItens += $item->quantidade;
            else
                $quantidadeDeItens++; 
        ?>
    
        <div class="margem-y">
            <div class="descricao-item-venda">
                <span>
        
                        @if ( $item->unidade_preco == 'UN' )
                            <span>{{formatarQuantidade($item->quantidade)}}x</span>
                        @else
                            {{formatarQuantidade($item->quantidade)}}<small>{{ $item->unidade_preco }}</small> 
                        @endif
                        
                    {{$item->produto->nome}}
                </span>
                <span class="observacao">
                    {{$item->observacao}}
                </span>
            </div>

            <div>
                <div class="coluna-1">
                    <span class="margem">
                        @if($item->preco != 0)

                        {{ formatarPreco($item->preco) }}<small>({{$item->unidade_preco}})</small>

                        @endif

                    </span>
                </div>
                
                @if($item->preco != 0 || $item->preco != 0.00)
                    <div class="coluna-2">{{ formatarPreco($item->quantidade * $item->preco) }}</div>
                @else
                    <div class="coluna-2">{{ formatarPreco($item->quantidade * $item->valor_pago) }}</div>
                @endif

                @if(isset($item->desconto))

                    @if($item->preco != 0)
                        <br>
                        <div class="coluna-right"> <small> DESC: {{formatarPreco($item->desconto) }} </small> </div><br>
                        <div class="coluna-right">{{formatarPreco($item->valor_pago) }}</div><br>
                    @endif

                @endif


            </div>
        </div>

    @endforeach

    <hr>

    <div class="margem-y">
        <div class="coluna-1">QTDE DE ITENS</div>
        <div class="coluna-2 negrito">{{ $quantidadeDeItens }}</div>
    </div>

    <div class="margem-y">
        <div class="coluna-1">VALOR TOTAL</div>
        <div class="coluna-2 negrito">{{ formatarPreco($venda->valor_total) }}</div>
    </div>


    @if (count($venda->pagamentos) == 1)

        <?php
            $pagamento = $venda->pagamentos[0];
            $troco = $pagamento->troco_para - $pagamento->valor_pago ;
        ?>

        <div class="margem-y">
            <div class="coluna-1">PAGAMENTO</div>
            <div class="coluna-2 negrito">{{$pagamento->metodo->nome}}</div>

            @if(isset($pagamento->desconto))
                <br>
                <div class="coluna-right"> <small> DESC: {{formatarPreco($pagamento->desconto) }} </small> </div><br>
                <div class="coluna-right">{{formatarPreco($pagamento->valor_pago) }}</div><br>
            @endif
        </div>
        
        @if($troco > 0)
            <div class="margem-y">
                <div class="coluna-1">TROCO PARA</div>
                <div class="coluna-2 negrito">{{ formatarPreco($pagamento->troco_para) }}</div>
            </div>
            
            <div class="">
                <div class="coluna-1">TROCO</div>
                <div class="coluna-2 negrito">{{ formatarPreco($troco) }}</div>
            </div>
        @endif

    @else

        <div class="pagamento">
            <div class="coluna-pagamento-1">PAGAMENTO</div>
            <div class="coluna-pagamento-2">VALOR <small style="font-size: 9px;">(C/ DESC)</small>  </div>
            <!-- <div class="coluna-pagamento-3">TROCO</div> -->
        </div>

        @foreach ($venda->pagamentos as $index => $pagamento)

            <?php
                //$troco = $pagamento->troco_para - $pagamento->valor_pago ;
            ?>

            <div class="margem-y negrito">
                <div class="coluna-pagamento-1">{{ $pagamento->metodo->nome }}</div>
                
                    @if(isset($pagamento->desconto))
                        <br>
                        <div class="coluna-pagamento-1"><small>DESC: {{formatarPreco($pagamento->desconto) }} </small></div>
                    @endif

                <div class="coluna-pagamento-2">{{ formatarPreco($pagamento->valor_pago) }}</div>

            </div>

        @endforeach

    @endif
    
    <hr class="tracejada">

    <div class="centralizado">

        <div>
            <div class="negrito">CONSUMIDOR</div>
            <div>
                
                
                <span class="maiusculo">
                    <small>
                        @if (isset($cliente->nome) )
                            {{$cliente->nome}}
                        
                        @else
                            CONSUMIDOR NÃO IDENTIFICADO
                        
                        @endif
                    </small>
                </span>
                <br>
                <span>
                @if (isset($cliente->cpf) )
                    CPF: {{$cliente->cpf}}
                @endif
                </span>

            </div>
        </div>

    <hr class="tracejada">
    
    <div class="centralizado">
        {{ $venda->created_at->format('d/m/Y H:i:s') }}
        @section ('informacao_nao_documento_fiscal')
            <div class="margem-y"><small style="font-size: 8pt;">NÃO É DOCUMENTO FISCAL</small> </div>
        @show
    </div>
    
    <hr class="tracejada">
    

        <div class="creditos">
            <div class="margem-y-g">
            @section ('rodape')

                Agradecemos a preferência! 

            @show
            </div>

            <div style="margin-top: 2em">
                <div>Sistema HR Vendas</div>
                <div>www.hrsis.com.br</div>
            </div>
        </div>
    </div>

</body>
</html>