@extends("layout.principal")

@section('conteudo')

@include('layout.navbar')

<section>

<!-- token laravel -->
@csrf

<div class="container-fluid">
    <div class="row mx-lg-1 my-lg-3 align-items-stretch">

        <!-- grid direita inicio -->
        <div class="col-7">

            <!-- card cliente  -->
            <div class="bg-tema-secondary shadow rounded px-4 py-2 text-uppercase text-white mb-4">
                <div class="icon-card-cliente"><span class="fas fa-users" aria-hidden="true"></span></div> 
                <div>
                    <span>Cliente: </span> <a id="linkSelecionarCliente" href="#" class="text-white font-weight-bold"><u id="textoClienteSelecionado">CONSUMIDOR NÃO IDENTIFICADO</u></a>
                </div>

                <div>
                    <small>    
                        <span>Vendedor: </span> <span id="spanNomeVendedor">Nome Vendedor</span>
                    </small>
                </div>
            </div>

            <!-- card adicionar produto -->
            <div class="bg-white rounded shadow px-4 py-3">
                
                <div class="text-uppercase">
                    <label class="font-weight-bold" for="selectAddProduto">LOCALIZE UM <u>P</u>RODUTO ABAIXO</label>
                    <select class="form-control custom-select custom-select-lg" id="selectAddProduto" title="Escolher produto" autofocus>

                    </select>
                </div>
                
                <hr>               
                
                <div class="text-uppercase">
                    
                    <div class="row"> 

                        <div class="col-12 mb-3"></div>

                        <div class="form-group col-4">
                            <label for="inputCodigoProduto">Código do Produto</label>
                            <input type="text" id="inputCodigoProduto" class="form-control" disabled>
                        </div>

                        <div class="form-group col-8">
                            <label for="inputCodigoBarrasProduto">Código de Barras</label>
                            <input type="text" id="inputCodigoBarrasProduto" class="form-control" disabled>
                        </div>
                        
                        <div class="col-12 mb-3"></div>

                        <div class="form-group col-3"> 
                            <label for="inputValorUnitarioProduto">Valor Unitário</label>
                            <input type="number" id="inputValorUnitarioProduto" class="form-control" disabled>
                        </div>

                        <div class="form-group col-3">
                            <label for="inputQuantidadeProduto"><u>Q</u>uantidade</label>
                            <input type="number" id="inputQuantidadeProduto" class="form-control" min="1" value="1">
                        </div> 

                        <div class="form-group col-3">
                            <label for="inputDescontoProduto"><u>D</u>esconto</label>
                            <input type="number" id="inputDescontoProduto" class="form-control" disabled>
                        </div> 
                        
                        <div class="col-3">
                        <label for="inputValorTotalProduto">Valor total</label>
                            <input type="number" id="inputValorTotalProduto" class="form-control" disabled>
                        </div>

                    </div>                        

                    <hr>

                    <div class="form-row mt-2">
                        <button class="btn btn-md btn-dark offset-5 col" id="btnAdicionarProduto"><i class="fa fa-plus"></i> <u>A</u>DICIONAR PRODUTO</button>
                        <button class="btn btn-sm btn-outline-danger col-2 ml-3" id="btnLimparProduto"><i class="fa fa-eraser"></i> <u>L</u>IMPAR </button>
                    </div>
                </div>

            </div>
            <!-- card adicionar produto fim -->
            
        </div>
        <!-- grid direita fim -->

        <!-- grid esquerda inicio-->
        <div class="col-5">

            <!-- card produtos adicionados -->
            <div class="mb-3">

                <!-- title produtos adicionados  -->
                <ul class="list-group list-group-flush bg-white rounded shadow" style="height: 19.8rem">
                    <div class="list-group-item bg-tema-light py-2">
                        <div class="row text-left font-weight-bold">
                            <div class="col-1">
                                Qnt.
                            </div>

                            <div class="col">
                                Produto
                            </div>

                            <div class="col-2">
                                <span>Desc<small>(R$)</small></span>
                            </div>

                            <div class="col-3">
                                Preço<small>(R$)</small>
                            </div>

                            <div class="col-auto invisible">
                                <button class="btn btn-sm btn-dark btn-editar-item p-1" title="Editar item"><i class="fas fa-edit fa-fw"></i></button>
                                <button class="btn btn-sm btn-danger btn-remover-item p-1 ml-1" title="Excluir item"><i class="fas fa-times fa-fw"></i></button>
                            </div>

                        </div>
                    </div>

                    <div id="listaItensVenda" class="overflow-auto">
                    
                        <!-- exemplo de item adicionado com js 
                        <div class="list-group-item py-2 produto-adicionado">
                        <div class="row">
                        <div class="col-1">
                        <span>2x</span>
                        </div>
                        <div class="col text-truncate">
                        <span title="">Nome do produto de teste</span>
                        </div>
                        <div class="col-2">
                        <span>230,50</span>
                        </div>
                        <div class="col-2" data-item="'+numItem+'">
                        <input type="hidden" class="valor-item" value="'+produto.valorTotalProduto+'">
                        <span>230,00</span>
                        </div>
                        <div class="col-auto">
                        <button class="btn btn-sm btn-dark btn-editar-item p-1" title="Editar item"><i class="fas fa-edit fa-fw"></i></button>
                        <button class="btn btn-sm btn-danger btn-remover-item p-1 ml-1" title="Excluir item"><i class="fas fa-times fa-fw"></i></button>
                        </div>
                        </div>
                        </div> -->

                    </div>
                </ul>

                <!-- <div class="bg-white rounded shadow px-0 py-0 overflow-auto" style="height: 17.3rem">
    
                    

                </div> -->
            </div>
            
            <!-- card pagamento -->
            <div>
                <div class="bg-white rounded shadow px-3 py-3">
                <div class="icon-card-pagamento"><span class="fas fa-money-bill-alt" aria-hidden="true"></span></div> 
                    
                    <div class="row">
                        <div class="col-lg-6 font-weight-bold">
                            <span class="text-uppercase">Valor Total</span>
                            <h4 id="valorTotalVenda">R$ 0,00</h4>
                        </div>

                        <!-- <div class="col-lg-4">
                            <span class="text-uppercase">Troco</span>
                            <h5 id="valorTroco">R$ 0,00</h5>
                        </div> -->
                    </div>

                    <hr>

                    <div class="row px-3">
                        <button id="btnContinuarVenda" class="btn btn-md btn-tema-primary text-uppercase col-8 mr-3"><i class="fa fa-check"></i> <u>C</u>ontinuar Venda</button>
                        <button id="btnLimparVenda" class="btn btn-md btn-tema-secondary text-uppercase col"><i class="fa fa-eraser"></i> Limpar</button>
                    </div>

                </div>
            </div>

        </div>
        <!-- grid esquerda fim -->

    </div>
</div>

</section>


<section>

<!-- modal cliente -->

<div class="modal fade" id="modalClientes" tabindex="-1" data-backdrop="static" data-keyboard="true" aria-labelledby="modalClientesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-tema-primary">
                <h4 class="modal-title text-uppercase" id="modalClientesLabel"><i class="fas fa-user-alt"></i> CLIENTE</h4>

                <div class="text-right align-text-bottom">
                    
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times-circle"></i>
                    </button>

                </div>

            </div>

            <div class="modal-body">

                <div class="form-group left-icon">
                    <i class="fas fa-search icon" aria-hidden="true"></i>
                    <input type="text" class="form-control" id="inputPesquisarClientes" placeholder="Pesquisar" style="width: auto">
                </div>

                <div class="overflow-auto" style="height: 21rem;">
                <ul class="list-group list-group-flush">
                    <div class="list-group-item bg-tema-secondary rounded-top">
                        <div class="row font-weight-bold">
                            <div class="col">
                                Nome
                            </div>

                            <div class="col">
                                CPF
                            </div>

                            <div class="col">
                                Telefone
                            </div>

                            <div class="col-2">
                            </div>
                        </div>
                    </div>

                    <!-- lista dinâmica de clientes -->
                    <div id="listaClientes">

                    </div>

                </ul>
                </div>

                <input type="hidden" id="inputHiddenClienteSelecionado">
            </div>

            <div class="modal-footer">
                <button id="btnNovoCliente" class="btn btn-md btn-dark text-uppercase col-3"><i class="fas fa-user-plus"></i> Novo cliente</button>
                <button id="btnLimparCliente" class="btn btn-md btn-tema-secondary text-uppercase col-2"><i class="fas fa-user-times"></i> Limpar cliente</button>
                <button class="btn btn-md btn-danger text-uppercase col-2" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i> Cancelar <small>(ESC)</small></button>
            </div>
        </div>
    </div>
</div>

</section>

<section>

<!-- modal cadastro de cliente -->
<section>

@include('cliente.modal_cadastro')

</section>

<!-- Modal Pagamento-->
<div class="modal fade" id="modalPagamento" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="modalPagamentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-tema-primary">
                <h4 class="modal-title text-uppercase" id="modalPagamentoLabel"><i class="fas fa-cash-register"></i> PAGAMENTO</h4>

                <div class="text-right align-text-bottom">
                    
                    <button type="button" class="close text-white btnCancelarPagamento">
                        <i class="fas fa-times-circle"></i>
                    </button>

                    <button type="button" class="btn-sm close text-white" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-minus-circle" style="opacity: 0.5; font-size: 1.1rem; margin-top:0.35rem"></i>
                    </button>

                </div>

            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-6">                        

                        <h5> <i class="fas fa-money-bill-alt"></i> Método de pagamento</h5>
                        <select class="form-control custom-select custom-select-lg" id="selectMetodoPagamento" title="Escolher método pagamento">
                                
                                <option value="" selected disabled>Selecione o método</option>

                                @foreach ($metodosPagamento as $metodo)
                                    <option value="{{ $metodo->id }}" data-nome="{{ $metodo->nome }}">{{ $metodo->nome }}</option>
                                @endforeach

                        </select>
                        
                        <hr>

                        <div class="row text-uppercase">

                            <div class="form-group col-3" id="divValorPagamento">
                                <label for="inputValorPagamento">Valor Pago</label>
                                <input type="number" min="0.01" step="0.01" id="inputValorPagamento" class="form-control" disabled>
                            </div>

                            <div class="form-group col-3" id="divTrocoPara">
                                <label for="inputTrocoPara">Troco Para</label>
                                <input type="number" id="inputTrocoPara" class="form-control" disabled>
                            </div>

                            <div class="form-group col-3" id="divDescontoPagamento">
                                <label for="inputDescontoPagamento">Desconto</label>
                                <input type="number" id="inputDescontoPagamento" class="form-control" disabled>
                            </div>

                            <div class="form-group col-3" id="divTrocoPagamento">
                                <span class="text-uppercase">Troco</span>
                                <h5 id="valorTrocoMetodo" class="text-center mt-1">R$ 0,00</h5>
                            </div>

                            <!-- divider -->
                            <div class="col-12"></div>

                            <div class="form-group offset-6 col-6 mt-2">
                                <button class="btn btn-dark text-uppercase col" id="btnAdicionarMetodo"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i> Adicionar</button>
                            </div>
                            
                            <div class="col-12 mt-3 text">
                                <div class="alert invisible" role="alert" id="divAvisoFaltandoPagamento">
                                    <span id="textoAvisoPagamento"></span>
                                </div>
                            </div>
                            
                        </div>

                    </div>

                    <div class="col-6">

                        <div id="divPagamentosAdicionados">
                            <h5> <i class="fas fa-donate"></i> Pagamentos adicionados</h5>

                            <div class="text-danger text-center invisible vertical-middle mt-2" id="mensagemMetodoPagamento"> <h6> Nenhum pagamento adicionado </h6></div>

                            <!-- title produtos adicionados  -->
                            <ul class="list-group list-group-flush bg-white rounded shadow invisible" id="listaPagamentosAdicionados">

                                <div class="list-group-item bg-tema-light py-2">
                                    <div class="row text-left font-weight-bold">

                                        <div class="col">
                                            <span>Método</span>
                                        </div>

                                        <div class="col-3">
                                            <span>Valor</span>
                                        </div>

                                        <div class="col-2">
                                            <span>Troco</span>
                                        </div>

                                        <div class="col-2">
                                            <span>Desconto</span>
                                        </div>

                                        <div class="col-auto invisible">
                                            <button class="btn btn-sm btn-danger btn-remover-pagamento p-1 ml-1" title="Excluir item" data-pagamento="'+numPagamento+'"><i class="fas fa-times fa-fw"></i></button>
                                        </div>

                                    </div>
                                </div>

                                <!-- div metodos adicionados com js-->
                                <div id="listaMetodosPagamentoVenda">
                                    
                                    <!-- pagamento adcionado com js 
                                    <div class="list-group-item py-2 pagamento-adicionado">
                                    <div class="row" data-pagamento="'+numPagamento+'">
                                    <div class="col text-truncate">
                                    <span>Dinheiro</span>
                                    </div>
                                    <input class="pagamento-id" type="hidden" value="'+id+'" disabled>
                                    <div class="col-2">
                                    <span>250,00</span>
                                    </div>
                                    <input class="pagamento-valorPagamento" type="hidden" value="'+valorPagamento+'" disabled>
                                    <div class="col-2">
                                    <span>100,00</span>
                                    </div>
                                    <input class="pagamento-valorTroco" type="hidden" value="'+valorTroco+'" disabled>
                                    <div class="col-2">
                                    <span class="text-danger">50,00</span>
                                    </div>
                                    <input class="pagamento-valorDesconto" type="hidden" value="'+valorDesonto+'" disabled>
                                    <div class="col-auto">
                                    <button class="btn btn-sm btn-danger btn-remover-pagamento p-1 ml-1" title="Excluir item" data-pagamento="'+numPagamento+'"><i class="fas fa-times fa-fw"></i></button>
                                    </div>
                                    </div>
                                    </div> -->
                                    
                                </div>

                            </ul>
                            
                        </div>

                        <!-- div totais pagamento -->
                        <div id="divTotaisPagamento" class="mt-3">
                            <h5> <i class="fas fa-comments-dollar"></i> Total</h5>

                            <ul class="list-group list-group-flush">
                                <div class="py-2">

                                    <div class="row text-left text-uppercase py-2 px-1 border-bottom">
                                        <div class="col-6">
                                            <span>Subtotal</span>
                                        </div>

                                        <div class="col text-right">
                                            <span class="font-weight-bolder" id="valorSubtotalPagamento">R$ 0,00</span>
                                        </div>
                                    </div>

                                    <div class="row text-left text-uppercase py-2 px-1 border-bottom">
                                        <div class="col-6">
                                            <span>Pagamentos Adicionados</span>
                                        </div>

                                        <div class="col text-right">
                                            <span id="valorPagamentos">R$ 0,00</span>
                                        </div>
                                    </div>

                                    <div class="row text-left text-uppercase py-2 px-1 border-bottom">
                                        <div class="col-6">
                                            <span>Descontos</span>
                                        </div>

                                        <div class="col text-right">
                                            <span id="valorDescontoPagamento">R$ 0,00</span>
                                        </div>
                                    </div>

                                    <div class="row text-left text-uppercase py-2 px-1 border-bottom">
                                        <div class="col-6">
                                            <span>Troco</span>
                                        </div>

                                        <div class="col text-right">
                                            <span id="valorTrocoPagamento">R$ 0,00</span>
                                        </div>
                                    </div>

                                    <div class="row text-left text-uppercase py-2 px-1 border-bottom">
                                        <div class="col-6">
                                            <span>Total a pagar</span>
                                        </div>

                                        <div class="col text-right">
                                            <span class="font-weight-bolder text-tema-primary" id="valorTotalPagarLiquido">R$ 0,00</span>                                           
                                        </div>
                                    </div>

                                    <!-- inputs hidden -->
                                    <input type="hidden" id="inputValorTotal">
                                    <input type="hidden" id="inputValorDescontos">
                                    <input type="hidden" id="inputValorTrocos">


                                </div>
                            </ul>

                        </div>

                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button id="btnFinalizarVenda" class="btn btn-md btn-tema-primary text-uppercase col-3"><i class="fas fa-check-double"></i> Finalizar Venda</button>
                <button class="btnCancelarPagamento btn btn-md btn-danger text-uppercase col-2"><i class="fa fa-times"></i> Cancelar <small>(ESC)</small></button>
            </div>
        </div>
    </div>
</div>

</section>

<!-- footer inicio -->

<!-- <footer class="page-footer mt-auto py-2 text-center bg-tema-light">
  <div class="container">
    <span class=""><strong>ALT+P</strong>=Escolher produto | <strong>ALT+A</strong>=Adicionar produto | <strong>ALT+Q</strong>=Ajustar quantidade | <strong>ALT+D</strong>=Ajustar Desconto | <strong>ALT+F</strong>=Finalizar Venda</span>
  </div>
</footer> -->

<!-- footer fim -->

@isset ($informacoesAlteracao)

    <script>
        var informacoesAlteracao = @json($informacoesAlteracao);
    </script>

@endisset



@endsection

@push('scripts')

    <script src="{{ mix('js/compilado/vendas.js') }}"></script>

    <!-- classes para o modal de cadastro e lista de clientes -->
    <script src="{{ mix('js/compilado/lista.js') }}"></script>

    <script>
        var pagina = new Pagina('/clientes', 'Cliente');
        pagina.modalFormulario = new ModalFormularioCliente(pagina);
        pagina.lista = new ListaCliente(pagina);
    </script>

@endpush