<div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">
            <form id="formCadastro" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCadastroTitulo">Cadastrar Categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    @csrf
                    <div class="form-group">
                        <label for="inputNome">Nome</label>
                        <input type="text" id="inputNome" name="nome-categoria" class="form-control" placeholder="Nome">
                        <div class="invalid-feedback"></div>
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