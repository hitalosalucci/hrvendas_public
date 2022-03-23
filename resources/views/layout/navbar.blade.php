<?php
    $endereco = url()->current();
?>

<section>
    <nav class="navbar navbar-expand-lg navbar-dark bg-tema-primary">
        <a class="navbar-brand" href="#">HR Vendas</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <li class="nav-item {{ $endereco == url('vendas/realizar_venda') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('vendas/realizar_venda')}}"><i class="fas fa-file-invoice-dollar"></i> Realizar vendas<span class="sr-only"></span></a>
                </li>

                <li class="nav-item {{ $endereco == url('vendas') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('vendas')}}"><i class="fas fa-receipt"></i> Vendas<span class="sr-only"></span></a>
                </li>

                <li class="nav-item {{ $endereco == url('clientes') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('clientes')}}"><i class="fas fa-users"></i> Clientes<span class="sr-only"></span></a>
                </li>

                <li class="nav-item dropdown {{ $endereco == url('categorias_produtos') || $endereco == url('produtos') || $endereco == url('marcas') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-clipboard-list"></i> Produtos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{url('marcas')}}">Gerenciar Marcas</a>
                        <a class="dropdown-item" href="{{url('categorias_produtos')}}">Gerenciar Categorias</a>
                        <a class="dropdown-item" href="{{url('produtos')}}">Gerenciar Produtos</a>
                    </div>
                </li>

                <li class="nav-item {{ $endereco == url('relatorios') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('relatorios')}}"><i class="fas fa-chart-bar"></i> Relatórios<span class="sr-only"></span></a>
                </li>

            </ul>

            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown">
                    
                    @if(Auth::user() != null)
                    <a class="nav-link text-light" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i> <span>{{ Auth::user()->nome ?? ''}}</span>
                    </a>
                    @endif
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{url('usuarios/alterar_senha')}}">Alterar senha</a>
                    </div>
                </li>
                <li class="nav-item ml-2"><a href="/sair" class="nav-link text-light"> <i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>

        </div>
    </nav>
</section>