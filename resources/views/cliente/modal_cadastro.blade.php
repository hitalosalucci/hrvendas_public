<div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow">
            <form id="formCadastro" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCadastroTitulo">Cadastrar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    @csrf
                    <div class="form-row">
                        
                        <!-- inicio primeiro bloco -->
                        <div class="form-group col-lg-12">
                            <label for="inputNome">Nome completo*</label>
                            <input type="text" id="inputNome" name="nome-cliente" class="form-control" placeholder="Nome completo">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- fim primeiro bloco -->

                        <!-- inicio segundo bloco -->

                        <div class="form-group col-lg-6">
                            <label for="inputCpf">CPF</label>
                            <input type="text" id="inputCpf" name="cpf-cliente" class="form-control" placeholder="CPF">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="inputIdentidade">Identidade(RG)</label>
                            <input type="text" id="inputIdentidade" name="identidade-cliente" class="form-control" placeholder="Identidade(RG)">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- fim segundo bloco -->

                        
                        <!-- inicio terceiro bloco -->
                        <div class="form-group col-lg-4">
                            <label for="inputTelefone">Telefone</label>
                            <input type="text" id="inputTelefone" name="telefone-cliente" class="form-control" placeholder="Telefone 1">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="inputTelefone2">Telefone 2</label>
                            <input type="text" id="inputTelefone2" name="telefone2-cliente" class="form-control" placeholder="Telefone 2">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="inputDataNascimento">Data de nascimento</label>
                            <input type="date" id="inputDataNascimento" name="data_nascimento-cliente" class="form-control" placeholder="Data de nascimento">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <!-- fim terceiro bloco -->

                        <!-- inicio quarto bloco -->

                        <div class="alert alert-warning col-12 invisible" id="alertaEndereco" role="alert">
                            Ao começar preencher o endereço, preencha-o completamente
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="selectEstado">Estado</label>
                                <select name="estado-endereco" id="selectEstado" class="form-control obrigatorio">
                                    <option value="" disabled selected>Selecione um estado</option>
                                </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="form-group col-lg-5">
                            <label for="inputCidade">Cidade</label>
                            <input type="text" id="inputCidade" name="cidade-endereco" class="form-control obrigatorio" placeholder="Cidade">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-5">
                            <label for="inputBairro">Bairro</label>
                            <input type="text" id="inputBairro" name="bairro-endereco" class="form-control obrigatorio" placeholder="Bairro">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <!-- fim quarto bloco -->

                        <!-- inicio quinto bloco -->

                        <div class="form-group col-lg-4">
                            <label for="inputRua">Rua</label>
                            <input type="text" id="inputRua" name="rua-endereco" class="form-control obrigatorio" placeholder="Rua">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-1">
                            <label for="inputNumero">Nº</label>
                            <input type="text" id="inputNumero" name="numero-endereco" class="form-control obrigatorio" placeholder="Nº">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-lg-7">
                            <label for="inputReferencia">Referência</label>
                            <input type="text" id="inputReferencia" name="referencia-endereco" class="form-control" placeholder="Referência">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- fim quinto bloco -->

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