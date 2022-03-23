@extends('layout.principal')

@section('conteudo')

@include('layout.navbar')

<div class="container my-4">

    <div class="row mt-4 col shadow rounded bg-white p-3">
        <div class="col">
            <form action="/usuarios/alterar_senha" method="post">
                @csrf
                <div class="form-group">
                    <label for="inputSenhaAtual">Senha atual</label>
                    <input type="password" class="form-control" id="inputSenhaAtual" name="senha">
                </div>
                <div class="form-group">
                    <label for="inputNovaSenha">Nova senha</label>
                    <input type="password" class="form-control" id="inputNovaSenha" name="nova-senha">
                </div>
                <div class="form-group">
                    <label for="inputRepetirNovaSenha">Repetir a nova senha</label>
                    <input type="password" class="form-control" id="inputRepetirNovaSenha">
                </div>
                
                <button class="btn btn-tema-primary mb-3" id="btnSalvar">Salvar</button>

                <div class="alert alert-warning" id="divAviso" style="display: none">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="txtAviso"></span>
                </div>

                <div class="alert alert-danger" id="divErro" style="display: none">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="txtErro"></span>
                </div>

                <div class="alert alert-success" id="divSucesso" style="display: none">
                    <i class="fas fa-check-circle"></i>
                    <span>Senha alterada</span>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ mix('js/compilado/alteracao-senha.js') }}"></script>
@endpush