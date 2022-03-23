@push('estilos')

<link rel="stylesheet" href="{{asset('estilo/paginas/login.css')}}">

@endpush

@extends("layout.principal")

@section('conteudo')
<section>
    
	<div class="fundo">
		<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-10 offset-sm-1">
			<div class="box-login">
				
				<div class="img-logo">
					<img class="img-fluid" src="{{asset('imagens/logo/logo_texto.png')}}" alt="HRSistemas">
				</div>
				
				<div class="text-right texto-vendas">
					<span><i class="fas fa-file-invoice-dollar"></i> VENDAS</span>
				</div>

				<div class="alert alert-danger" id="alrtErro" style="display: none">
					<i class="fa fa-exclamation-triangle"></i>
					<span id="alrtErroMensagem"> </span>
            	</div>

				<form action="/login" method="POST">
                	@csrf

					<div>
						<input type="text" name="nome-login" placeholder="Usuário" autofocus>
						<i class="fas fa-user icone-input"></i>					
					</div>
					
					<div>
						<input type="password" name="senha-login" placeholder="Senha" id="inputSenha">
						<i class="fas fa-lock icone-input"></i>					
					</div>

					<div class="form-group">
						<button class="btn-entrar col-12">
							<span><i class="fas fa-sign-in-alt text-white"></i> ENTRAR</span>
						</button>
					</div>
				
				</form>

			</div>
		</div>

	</div>
   
</section>
@endsection

@push('scripts')

<script src="{{ mix('js/compilado/login.js') }}"></script>


@endpush