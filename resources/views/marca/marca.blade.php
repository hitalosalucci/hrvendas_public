@extends('layout.principal')

@section('conteudo')

@include('layout.navbar')

<div class="container mt-4">

    <div class="row my-4 col shadow rounded bg-white p-3">
        <div class="col">
            <div class="row">
                <div class="col">
                    <button class="btn btn-tema-primary shadow" data-toggle="modal" data-target="#modalCadastro"><i class="fas fa-plus"></i> Cadastrar Marca</button>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-6 left-icon">
                    <i class="fas fa-search icon" aria-hidden="true"></i>
                    <input type="text" class="form-control" id="inputPesquisaMarca" placeholder="Pesquisar marca">
                </div>

            </div>

            <div class="row mt-2">
                <div class="col">
                    <ul class="list-group list-group-flush">
                        <div class="list-group-item bg-tema-primary rounded-top">
                            <div class="row font-weight-bold">
                                <div class="col">
                                    Nome
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
@include('marca.modal_cadastro')

@endsection

@push('scripts')
    <script src="{{ mix('js/compilado/lista.js') }}"></script>]

    <script>
        var pagina = new Pagina('/marcas', 'Marca');
        pagina.lista = new ListaMarca(pagina);
        pagina.modalApagar = new ModalApagar(pagina, 'Marca');
        pagina.modalFormulario = new ModalFormularioMarca(pagina);
        pagina.carregarObjetos();
    </script>
@endpush