<!doctype html5>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ asset('estilo/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('font_awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('estilo/bootstrap-select.min.css') }}">

     <!-- tema estilo css -->
     <link rel="stylesheet" href="{{ asset('estilo/cores.css') }}">

     <!-- tema estilo css -->
     <link rel="stylesheet" href="{{ asset('estilo/form.css') }}">     

     @stack('estilos')
     
    <title>HR Vendas @isset($titulo) {{' - '.$titulo}} @endisset</title>
  </head>
  <body class="tema-bg-light">
    
    @yield("conteudo")
    
    <script src="{{ mix('js/compilado/principal.js') }}"></script>

    @stack('scripts')
  </body>
  
</html>