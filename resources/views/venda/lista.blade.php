@extends('layout.principal')

@section('conteudo')

@include('layout.navbar')

<div class="container my-4">

    @csrf

    <div class="row my-2 shadow rounded bg-white p-3">
        <div class="col">
            <div class="row">
                
                <div class="col-md-12 col-lg-3 col-xg-3 col-sm-12 col-xs-12">                    
                    <div class="row pl-3 pr-3">
                        <div class="col-2 bg-tema-primary text-light rounded-left d-flex align-items-center text-center"> 
                            <div class="mx-auto">
                                <i class="fa-fw fas fa-shopping-cart"></i>  
                            </div>
                        </div>
                        <div class="col-10 bg-white p-2 border rounded-right text-nowrap">
                            <span class="text-uppercase text-secondary mb-0">Qnt de vendas: </span>
                            <span class="font-weight-bold mb-0"><span id="spanQuantidadeVendas"></span></span> 
                        </div>
                    </div>                      
                </div>

                <div class="col-md-12 col-lg-3 col-xg-3 col-sm-12 col-xs-12 mt-sm-2 mt-lg-0">                    
                    <div class="row pl-3 pr-3">
                        <div class="col-2 bg-tema-primary text-light rounded-left d-flex align-items-center"> 
                            <div class="mx-auto">
                                <i class="fa-fw fas fa-tags"></i>
                            </div>
                        </div>
                        <div class="col-10 bg-white p-2 border rounded-right">
                            <span class="text-uppercase text-secondary mb-0">Descontos: </span>
                            <span class="font-weight-bold mb-0"><span id="spanValorTotalDesconto"></span></span> 
                        </div>
                    </div>                      
                </div>

                <div class="col-md-12 col-lg-3 col-xg-3 col-sm-12 col-xs-12 mt-sm-2 mt-lg-0">                    
                    <div class="row pl-3 pr-3">
                        <div class="col-2 bg-tema-primary text-light text-center rounded-left d-flex align-items-center"> 
                            <div class="mx-auto">
                                <i class="fa-fw fa fa-hand-holding-usd"></i>
                            </div>
                        </div>
                        <div class="col-10 bg-white p-2 border rounded-right">
                            <span class="text-uppercase text-secondary mb-0">Trocos: </span>
                            <span class="font-weight-bold mb-0"><span id="spanValorTotalTroco"></span></span> 
                        </div>
                    </div>                      
                </div>

                <div class="col-md-12 col-lg-3 col-xg-3 col-sm-12 col-xs-12 mt-sm-2 mt-lg-0">                    
                    <div class="row pl-3 pr-3">
                        <div class="col-2 bg-tema-primary text-light text-center rounded-left d-flex align-items-center"> 
                            <div class="mx-auto">
                                <i class="fa-fw fa fa-dollar-sign"></i> 
                            </div>
                        </div>
                        <div class="col-10 bg-white p-2 border rounded-right">
                            <span class="text-uppercase text-secondary mb-0">Vendas: </span>
                            <span class="font-weight-bold mb-0"><span id="spanValorTotalVendas"></span></span> 
                        </div>
                    </div>                      
                </div>


            </div>    
        
            <hr>

            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-lg-4 left-icon">
                            <i class="fas fa-search icon" aria-hidden="true"></i>
                            <input type="text" class="form-control" id="inputPesquisaVendaPorCliente" placeholder="Pesquisar nome do cliente">
                        </div>

                        <div class="col-lg-4 mt-lg-0 mt-md-2 mt-sm-2 left-icon">
                            <i class="fas fa-user-tag icon" aria-hidden="true"></i>
                            <select class="form-control" id="selectPesquisaVendaPorVendedor"></select>
                        </div>

                        <div class="col-lg-4 mt-lg-0 mt-md-2 mt-sm-2 left-icon">
                            <i class="fas fa-calendar-alt icon" aria-hidden="true"></i>
                            <input type="date" id="inputDataVenda" class="form-control" value="{{ $dataUltimaVenda }}">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <ul class="list-group">
                                <div class="list-group-item bg-tema-primary rounded-top">
                                    <div class="row font-weight-bold">
                                        
                                        <div class="col-2">
                                            Valor
                                        </div>

                                        <div class="col">
                                            Pagamento(s)
                                        </div>

                                        <div class="col">
                                            Vendedor
                                        </div>

                                        <div class="col">
                                            Cliente
                                        </div>

                                        <div class="col-2">
                                            Horário
                                        </div>

                                        <div class="col-auto invisible">
                                            <button class="btn btn-icon-tema-primary" title="Ver"><i class="fas fa-print fa-lg"></i></button>
                                            <button class="btn btn-icon-tema-primary" title="Imprimir"><i class="fas fa-print fa-lg"></i></button>
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
    </div>
</div>

<section>
    @include('layout.modal_confirmacao_apagar')
</section>

</section>
    @include('venda.modal_ver_venda')
</section>

@endsection

@push('estilos')

<link rel="stylesheet" href="{{asset('estilo/listas.css')}}">

@endpush

@push('scripts')
    <script src="{{ mix('js/compilado/lista.js') }}"></script>
    
    <script>
        var pagina = new PaginaVenda('/vendas', 'Venda');
        pagina.lista = new ListaVenda(pagina);
        pagina.modalApagar = new ModalApagar(pagina, 'Venda');
        pagina.carregarObjetos();
    </script>
@endpush