<div class="modal fade" id="modalVerVenda" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow">
            
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerVendaTitulo"><i class="far fa-eye"></i> Detalhes da venda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <small class="text-muted">Qnt de itens: <span id="spanQntItensTotais"> </span> </small>
                    <small class="text-muted">| Atualizada em: <span id="spanDataUpdate"> </span> </small>
                    <!-- <small class="text-muted">| Última alteração na venda: <span id="spanDataAlteracao"> </span> </small> -->

                    @csrf
                    <div class="form-row">
                        
                        <!-- inicio primeiro bloco -->
                        <div class="form-group col-lg-5">
                            <label for="verValorVenda">Valor da venda: <small>(Com desconto)</small></label>
                            <input type="text" id="verValorVenda" class="form-control" readonly>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="verDataVenda">Data da venda: </label>
                            <input type="text" id="verDataVenda" class="form-control" readonly>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="verDescontos">Descontos: </label>
                            <input type="text" id="verDescontos" class="form-control" readonly>
                        </div>
                        <!-- fim primeiro bloco -->

                        <!-- inicio segundo bloco -->
                        <div class="form-group col-lg-6">
                            <label for="verVendedor">Vendedor: </label>
                            <input type="text" id="verVendedor" class="form-control" readonly>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="verCliente">Cliente: </label>
                            <input type="text" id="verCliente" class="form-control" readonly>
                        </div>
                        <!-- fim segundo bloco -->
                        
                        <!-- divisor -->
                        <div class="col-12"> <hr> </div>

                        <!-- inicio terceiro bloco preencher métodos de pagamento-->
                        <div class="form-group col-lg-12">
                            <label>Itens: </label>

                            <div class="form-row">
                                <div class="col-2"> <small>Qnt </small> </div>
                                <div class="col-6"> <small>Produto  </small> </div>
                                <div class="col-4"> <small>Valor de venda</small> </div>
                            </div>
                            
                            <div class="form-row" id="divItensVenda">
                                <!-- <input type="text" id="verQntItens" class="form-control col-2" readonly>
                                <input type="text" id="verProdutoItens" class="form-control col" readonly>
                                <input type="text" id="verValorPagoItens" class="form-control col-4" readonly> -->
                            </div>
                        </div>
                        <!-- fim terceiro bloco -->

                        <!-- inicio quarto bloco preencher métodos de pagamento-->
                        <div class="form-group col-lg-12">
                            <label>Métodos de pagamento: </label>
                            
                            <div class="form-row">
                                <div class="col-6"> <small>Método</small> </div>
                                <div class="col-3"> <small>Valor Pago </small> </div>
                                <div class="col-3"> <small>Desconto</small> </div>
                            </div>

                            <div class="form-row" id="divItensPagamento">
                                <!-- <input type="text" id="verMetodosPagamento" class="form-control" readonly> -->
                            </div>
                        </div>
                        <!-- fim quarto bloco -->

                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Fechar</button>
                </div>
            
        </div>
    </div>
</div>