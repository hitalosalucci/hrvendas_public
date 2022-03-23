<div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow">
            <form id="formCadastro" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCadastroTitulo">Cadastrar Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    @csrf
                    <div class="form-row">
                        
                        <!-- inicio primeiro bloco -->
                        <div class="form-group col-lg-6">
                            <label for="inputNome">Nome</label>
                            <input type="text" id="inputNome" name="nome-produto" class="form-control" placeholder="Nome">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="inputPreco">Preço<small>(R$)</small></label>
                            <input type="number" id="inputPreco" min="0.00" step="0.01" class="form-control" placeholder="Preço">
                            <input type="hidden" id="inputPrecoHidden" name="preco-produto">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="inputPreco">Unidade</label>
                            <select name="unidade-produto" id="selectUnidadeProduto" class="form-control">
                                <option value="UN" title="Unidade" selected>UN</option>
                                <option value="Kg" title="Kilograma">Kg</option>
                            </select>

                        </div>

                        
                        <div class="form-group col-lg-2">
                            <label for="inputCodigoProduto">Código</label>
                            <input type="text" id="inputCodigoProduto" name="codigo-produto" class="form-control" placeholder="Código">
                            <div class="invalid-feedback"></div>
                        </div>
                        <!-- fim primeiro bloco -->

                        <!-- inicio segundo bloco -->
                        <div class="form-group col-lg-6">
                            <label for="selectCategorias">Categoria</label>
                            <select id="selectCategorias" name="categoria-produto" class="form-control"></select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="selectMarcas">Marca</label>

                            <select id="selectMarcas" name="marca-produto" class="form-control">
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>
                        <!-- fim segundo bloco -->

                        <!-- inicio terceiro bloco -->
                        <div class="form-group col-lg-12">
                            <label for="inputCodigoBarrasProduto">Código de barras</label>
                            <input type="text" id="inputCodigoBarrasProduto" name="codigo_barras-produto" class="form-control" placeholder="Código de barras">
                            <div class="invalid-feedback"></div>
                        </div>
                        <!-- fim terceiro bloco -->

                        <!-- inicio quarto bloco -->
                        <div class="form-group col-lg-12">
                            <label for="inputDescricaoProduto">Descrição</label>
                            <input type="text" id="inputDescricaoProduto" name="descricao-produto" class="form-control" placeholder="Descrição">
                            <div class="invalid-feedback"></div>
                        </div>
                        <!-- fim quarto bloco -->

                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-tema-primary" type="submit" id="modalCadastroSalvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>