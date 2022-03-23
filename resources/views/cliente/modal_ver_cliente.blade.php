<div class="modal fade" id="modalVerCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow">
            
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerClienteTitulo"><i class="far fa-eye"></i> Ver Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <small class="text-muted">Cadastrado em: <span id="spanDataCadastro"> </span> </small>
                    <small class="text-muted">| Última alteração em: <span id="spanDataAlteracao"> </span> </small>

                    @csrf
                    <div class="form-row">
                        
                        <!-- inicio primeiro bloco -->
                        <div class="form-group col-lg-12">
                            <label for="verNome">Nome completo*</label>
                            <input type="text" id="verNome" class="form-control" readonly>
                        </div>

                        <!-- fim primeiro bloco -->

                        <!-- inicio segundo bloco -->

                        <div class="form-group col-lg-6">
                            <label for="verCpf">CPF</label>
                            <input type="text" id="verCpf" class="form-control" readonly>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="verIdentidade">Identidade(RG)</label>
                            <input type="text" id="verIdentidade" class="form-control" readonly>
                        </div>

                        <!-- fim segundo bloco -->

                        
                        <!-- inicio terceiro bloco -->
                        <div class="form-group col-lg-4">
                            <label for="verTelefone">Telefone</label>
                            <input type="text" id="verTelefone" class="form-control" readonly>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="verTelefone2">Telefone 2</label>
                            <input type="text" id="verTelefone2" class="form-control" readonly>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="verDataNascimento">Data de nascimento</label>
                            <input type="text" id="verDataNascimento" class="form-control" readonly>
                        </div>
                        
                        <!-- fim terceiro bloco -->

                        <!-- divisor -->
                        <div class="col-12 mt-2 mb-2"><hr></div>

                        <!-- inicio quarto bloco -->

                        <div class="form-group col-lg-2">
                            <label for="verEstado">Estado</label>
                            <input type="text" id="verEstado" class="form-control" readonly>

                        </div>
                        
                        <div class="form-group col-lg-5">
                            <label for="verCidade">Cidade</label>
                            <input type="text" id="verCidade" class="form-control" readonly>
                        </div>

                        <div class="form-group col-lg-5">
                            <label for="verBairro">Bairro</label>
                            <input type="text" id="verBairro" class="form-control" readonly>
                        </div>
                        
                        <!-- fim quarto bloco -->

                        <!-- inicio quinto bloco -->

                        <div class="form-group col-lg-4">
                            <label for="verRua">Rua</label>
                            <input type="text" id="verRua" class="form-control" readonly>
                        </div>

                        <div class="form-group col-lg-1">
                            <label for="verNumero">Nº</label>
                            <input type="text" id="verNumero" class="form-control" readonly>
                        </div>

                        <div class="form-group col-lg-7">
                            <label for="verReferencia">Referência</label>
                            <input type="text" id="verReferencia" class="form-control" readonly>
                        </div>

                        <!-- fim quinto bloco -->

                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Fechar</button>
                </div>
            
        </div>
    </div>
</div>