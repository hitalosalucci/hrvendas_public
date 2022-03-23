@extends('layout.principal')

@section('conteudo')

@include('layout.navbar')

<div class="container mt-4">

    <div class="row my-4 col shadow rounded bg-white p-3">
        <div class="col">
            <div class="row">
                <div class="col">
                    <button class="btn btn-tema-primary shadow" data-toggle="modal" data-target="#modalCadastro"><i class="fas fa-plus"></i> Cadastrar Produto</button>
                </div>
            </div>

            <div class="row my-3">

                <div class="col left-icon">
                    <i class="fas fa-search icon" aria-hidden="true"></i>
                    <input type="text" class="form-control" id="inputPesquisaProduto" placeholder="Pesquisar código ou produto">
                </div>

                <div class="col left-icon">
                    <i class="fas fa-filter icon" aria-hidden="true"></i>
                    <select class="form-control" id="selectFiltroCategoria">
                    </select>
                </div>

                <div class="col left-icon">
                    <i class="fas fa-filter icon" aria-hidden="true"></i>
                    <select class="form-control" id="selectFiltroMarca">
                    </select>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col">
                    <ul class="list-group list-group-flush">
                        <div class="list-group-item bg-tema-primary rounded-top">
                            <div class="row font-weight-bold">
                                
                                <div class="col-2">
                                    Cód
                                </div>

                                <div class="col">
                                    Nome
                                </div>

                                <div class="col-2">
                                    Preço
                                </div>

                                <div class="col-2">
                                    Categoria
                                </div>

                                <div class="col-2">
                                    Marca
                                </div>

                                <div class="col-auto invisible">
                                    <button class="btn btn-icon-tema-primary" title="Editar"><i class="fas fa-edit fa-lg"></i></button>
                                    <button class="btn btn-icon-tema-primary" title="Apagar"><i class="fas fa-trash-alt fa-lg"></i></button>
                                </div>
                            </div>
                        </div>

                        <div id="listaObjetos">
                        </div>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.modal_confirmacao_apagar')
@include('produto.modal_cadastro')

@endsection

@push('scripts')
    <script src="{{ mix('js/compilado/lista.js') }}"></script>]

    
    <script>
        var pagina = new Pagina('/produtos', 'Produto');
        pagina.lista = new ListaProduto(pagina);
        pagina.modalApagar = new ModalApagar(pagina, 'Produto');
        pagina.modalFormulario = new ModalFormularioProduto(pagina);
        pagina.carregarObjetos();
    </script>
@endpush