@extends('layout.principal')

@section('conteudo')

@include('layout.navbar')

<style>

    .fundo{
        position: fixed;
        opacity: 0.2;
    }

    .erro-1{
        font-size: 30px;  
        text-shadow: 3px 3px 0px #eee, 2px 2px 0px #707070;  
    }
    .erro-2{
        font-size: 190px;
        line-height: 190px;
        text-shadow: 0px 2px 3px #666;
    }
    .erro-2:hover{
        text-shadow: 0 0 10px #fff, 0 0 10px #fff, 0 0 20px #fff, 0 0 20px #174578, 0 0 50px #174578, 0 0 60px #174578, 0 0 100px #174578, 0 0 100px #174578;
    }

    .btn-voltar{
        border-radius: 20px;
    }
</style>

<div class="container text-center mt-lg-5 mt-md-3 mt-sm-3">

    <div class="fundo center">
        <img style="height: 70%;" src="{{url('imagens/logo/logo_transparente.png')}}" alt="HRsistemas" class="img-fluid">
    </div>

    <div class="row">
        <div class="col text-tema-primary" style="margin-top:10rem">
            <h3>Erro 403</h3>
            <h1><strong>Você não tem permissão para acessar essa página</strong></h1>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <a href="{{url('')}}" class="btn btn-tema-primary">Voltar para o início</a>
        </div>
    </div>
    
</div>

@endsection