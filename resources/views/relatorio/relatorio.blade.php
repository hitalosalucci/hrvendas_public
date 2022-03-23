@extends('layout.principal')

@section('conteudo')

@include('layout.navbar')


<section>

<div class="container my-4">

    <div class="row shadow rounded bg-white p-3">

            <div class="col-12 mb-3">

                <div>
                    <label for="inputDataInicial">Selecione o período: </label>
                </div>

                <div class="form-row">
                    <div class="left-icon">               
                        <i class="fas fa-calendar-alt icon" aria-hidden="true"></i>
                        <input type="date" id="inputDataInicial" class="form-control inputs-data" value="{{ $data7DiasAtras }}">
                    </div>

                    <span class="mt-2 ml-1 mr-1">até</span>

                    <div class="left-icon">
                        <i class="fas fa-calendar-alt icon" aria-hidden="true"></i>
                        <input type="date" id="inputDataFinal" class="form-control inputs-data" value="{{ $dataAtual }}">
                    </div>
                </div>

                <div class="mt-2">
                    <small>
                        <span class="text-muted">Dias completos no intervalo selecionado: <span id="spanDiasIntervalo">0</span></span>
                    </small>
                </div>
            </div>

            <!-- divisor -->
            <div class="col-12 mb-2"></div>

            <div class="col-lg-3 mx-auto pl-3 pr-3">
                <div class="bg-verde-tema text-right text-white font-weight-bold p-3 rounded-lg shadow p-3">

                    <div class="icone-box-relatorio icone-verde-tema">
                        <i class="fa-4x far fa-money-bill-alt"></i>
                    </div>

                    <div class="informacoes-box-relatorio">
                        <span class="resultado-box-relatorio" id="spanTotalVendas">
                            0,00
                        </span>

                        <br>

                        <span>Total de vendas</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mx-auto pl-3 pr-3">
                <div class="bg-amarelo-tema text-right text-white font-weight-bold p-3 rounded-lg shadow">
                    
                    <div class="icone-box-relatorio icone-amarelo-tema">
                        <i class="fa-4x fas fa-shopping-cart"></i>
                    </div>

                    <div class="informacoes-box-relatorio">
                        <span class="resultado-box-relatorio" id="spanQuantidadeVendas">
                            00
                        </span>

                        <br>

                        <span>Qnt de vendas</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mx-auto pl-3 pr-3">
                <div class="bg-ciano-tema text-right text-white font-weight-bold p-3 rounded-lg shadow">
                    
                    <div class="icone-box-relatorio icone-ciano-tema">
                        <i class="fa-4x far fa-credit-card"></i>
                    </div>

                    <div class="informacoes-box-relatorio">
                        <span class="resultado-box-relatorio" id="spanTicketMedio">
                            0,00
                        </span>
                        
                        <br>

                        <span>Ticket médio</span>
                    </div>

                </div>
            </div>

            <div class="col-lg-3 mx-auto pl-3 pr-3">
                <div class="bg-vermelho-tema text-right text-white font-weight-bold p-3 rounded-lg shadow">
                    
                    <div class="icone-box-relatorio icone-vermelho-tema">
                        <i class="fa-4x fas fa-barcode"></i>
                    </div>

                    <div class="informacoes-box-relatorio">
                        <span class="resultado-box-relatorio" id="spanQuantidadeItens">
                            0
                        </span>

                        <br>
                        
                        <span>Produtos vendidos</span>
                    </div>

                </div>
            </div>
            
            <!-- divisor -->
            <div class="col-12 mt-2 mb-2"> <hr> </div>

            <div class="col-12 mx-auto">

                <div>

                    <canvas id="graficoValorVendasPorDia">
                    </canvas>

                </div>

            </div>

            <!-- divisor -->
            <div class="col-12 mt-2 mb-2"> <hr> </div>

            <div class="col-12 mx-auto">

                <div>

                    <canvas id="graficoQntProdutosVendidosPorDia">
                    </canvas>

                </div>

            </div>

    </div>

</div>

</section>

@endsection

@push('estilos')
<style>
    .bg-verde-tema{
        background-color: #278F48!important;
    }

    .bg-amarelo-tema{
        background-color: #E8C75F!important;
    }

    .bg-ciano-tema{
        background-color: #6EBEC7!important;

    }

    .bg-vermelho-tema{
        background-color: #B35958!important;

    }

    .informacoes-box-relatorio{
        position: relative;
        z-index: 2;
    }

    .icone-box-relatorio{
        position: absolute;
        z-index: 1;
    }

    .icone-verde-tema{
        color: #66c985;
    }

    .icone-amarelo-tema{
        color: #fce6a6;
    }

    .icone-ciano-tema{
        color: #baecf2;
    }

    .icone-vermelho-tema{
        color: #ea9a9a;
    }

    .resultado-box-relatorio{
        font-size: 1.5rem;
    }

</style>
@endpush

@push('scripts')

<script src="{{ mix('js/compilado/relatorios.js') }}"></script>

@endpush