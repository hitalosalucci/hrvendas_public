<?php

namespace Tests;

use App\Exceptions\TransactionException;
use App\Model\CategoriaProduto;
use App\Model\Cliente;
use App\Model\Endereco\Estado;
use App\Model\Endereco\Cidade;
use App\Model\Endereco\Endereco;
use App\Model\Empresa;
use App\Model\Fornecedor;
use App\Model\FuncionalidadeSistema;
use App\Model\Marca;
use App\Model\MetodoPagamento;
use App\Model\Permissao;
use App\Model\Produto;
use App\Model\Usuario\Usuario;
use App\Transactions\CategoriaProduto\AddCategoriaProdutoTransaction;
use App\Transactions\Cidade\AddCidadeTransaction;
use App\Transactions\Cliente\AddClienteTransaction;
use App\Transactions\Empresa\AddEmpresaTransaction;
use App\Transactions\Endereco\AddEnderecoTransaction;
use App\Transactions\Estado\AddEstadoTransaction;
use App\Transactions\Fornecedor\AddFornecedorTransaction;
use App\Transactions\FuncionalidadeSistema\AddFuncionalidadeSistemaTransaction;
use App\Transactions\Marca\AddMarcaTransaction;
use App\Transactions\MetodoPagamento\AddMetodoPagamentoTransaction;
use App\Transactions\PermissaoUsuario\AddPermissaoTransaction;
use App\Transactions\Produto\AddProdutoTransaction;
use App\Transactions\Usuario\AddUsuarioTransaction;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $faker;

    protected function setUp() : void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    protected function assertErroTransacao(TransactionException $excecao, string $campo, string $erroEsperado)
    {
        $erros = $excecao->getErros();
        $this->assertTrue(isset($erros[$campo]), 'Faltando campo "'.$campo.'" em exceção');
        $this->assertEquals($erroEsperado, $erros[$campo], 'Erro do campo "'.$campo.'" incorreto');
    }

    protected function criarEstado() : Estado
    {
        $nome = $this->faker->unique()->state;
        $sigla =$this->faker->unique()->stateAbbr;

        $transacao = new AddEstadoTransaction($nome, $sigla);
        $transacao->execute();

        return Estado::where('nome', $nome)->first();
    }

    protected function criarCidade(Estado $estado) : Cidade
    {
        $nome = $this->faker->unique()->city;

        $transacao = new AddCidadeTransaction($nome, $estado->id);
        $transacao->execute();
        
        return Cidade::where('nome', $nome)->first();
    }

    protected function criarEndereco() : Endereco
    {
        $this->estado = $this->criarEstado();
        $this->cidade = $this->criarCidade($this->estado);

        $bairro = $this->faker->unique()->citySuffix;
        $rua = $this->faker->unique()->streetName;
        $referencia = $this->faker->unique()->secondaryAddress;
        $numero = $this->faker->unique()->randomNumber(3);
        
        $transacao = new AddEnderecoTransaction($bairro, $rua, $numero, $this->cidade->id, $referencia);
        $transacao->execute();

        return Endereco::where('bairro', $bairro)->where('rua', $rua)->where('numero', $numero)->first();
    }

    protected function criarEmpresa() : Empresa
    {
        $this->estado = $this->criarEstado();
        $this->cidade = $this->criarCidade($this->estado);
        $this->enderecoEmpresa = $this->criarEndereco($this->cidade);

        $nome = $this->faker->unique()->companySuffix;
        $nome_empresarial = $this->faker->unique()->company;
        $cnpj =  $this->faker->unique()->randomNumber(8);
        $telefone = $this->faker->unique()->phoneNumber;
        $telefone2 = $this->faker->unique()->phoneNumber;

        $transacao = new AddEmpresaTransaction($nome, $nome_empresarial, $cnpj, $telefone, $this->enderecoEmpresa->id, $telefone2);
        $transacao->execute();

        return Empresa::where('nome', $nome)->where('nome_empresarial', $nome_empresarial)->where('cnpj', $cnpj)->first();
    }

    protected function criarUsuario(Empresa $empresa) : Usuario
    {
        $nome = $this->faker->unique()->name;
        $login = $this->faker->unique()->userName;
        $senha = $this->faker->unique()->password;
        $cpf = $this->faker->unique()->randomNumber(8);

        $transacao = new AddUsuarioTransaction($nome, $login, $senha, $empresa->id, $cpf);
        $transacao->execute();

        return Usuario::where('nome', $nome)->where('login', $login)->first();
    }

    protected function criarFuncionalidadeSistema() : FuncionalidadeSistema
    {
        $nome = $this->faker->unique()->word;
        $codigo = $this->faker->unique()->word;

        $transacao = new AddFuncionalidadeSistemaTransaction($nome, $codigo);
        $transacao->execute();

        return FuncionalidadeSistema::where('nome', $nome)->first();
    }

    protected function criarPermissao() : Permissao
    {
        $nome = $this->faker->unique()->word;
        $codigo = $this->faker->unique()->word;

        $transacao = new AddPermissaoTransaction($nome, $codigo);
        $transacao->execute();

        return Permissao::where('nome', $nome)->first();
    }

    protected function criarCliente(Empresa $empresa, Endereco $enderecoCliente) : Cliente
    {
        $nome = $this->faker->unique()->name; 
        $telefone = $this->faker->unique()->phoneNumber; 
        $telefone2 = $this->faker->unique()->phoneNumber; 
        $dataNascimento = $this->faker->unique()->date($format = 'Y-m-d', $max = 'now'); 
        $cpf = $this->faker->unique()->randomNumber(8); 
        $identidade = $this->faker->unique()->randomNumber(8);

        $transacao = new AddClienteTransaction($nome, $empresa->id, $telefone, $telefone2, $dataNascimento, $cpf, $identidade, $enderecoCliente->id);
        $transacao->execute();

        return Cliente::where('nome', $nome)->where('cpf', $cpf)->where('identidade', $identidade)->first();
    }

    protected function criarCategoriaProduto(Empresa $empresa) : CategoriaProduto
    {
        $nome = $this->faker->unique()->word;

        $transacao = new AddCategoriaProdutoTransaction($nome, $empresa->id);
        $transacao->execute();

        return CategoriaProduto::where('nome', $nome)->first();
    }

    protected function criarMarca(Empresa $empresa) : Marca
    {
        $nome = $this->faker->unique()->word;
        $codigo = $this->faker->unique()->word;

        $transacao = new AddMarcaTransaction($nome, $empresa->id, $codigo);
        $transacao->execute();

        return Marca::where('codigo', $codigo)->first();
    }

    protected function criarProduto(CategoriaProduto $categoriaProduto, Marca $marca) : Produto
    {
        // $categoriaProduto = $this->criarCategoriaProduto($empresa);
        // $marca = $this->criarMarca($empresa);

        $nome = $this->faker->unique()->word;
        $preco = $this->faker->unique()->randomFloat(2, 1, 100000);
        $unidade = 'UN';
        $codigoProduto = $this->faker->unique()->randomNumber(4);
        $codigoBarras = $this->faker->unique()->randomNumber(8);
        $descricao = $this->faker->unique()->text;

        $transacao = new AddProdutoTransaction($nome, $preco, $unidade, $categoriaProduto->id, $codigoProduto, $codigoBarras, $descricao, $marca->id);
        $transacao->execute();

        return Produto::where('nome', $nome)->where('preco', $preco)->first();   
    }

    protected function criarFornecedor(Empresa $empresa) : Fornecedor
    {
        $estado = $this->criarEstado();
        $cidade = $this->criarCidade($estado);
        $enderecoFornecedor = $this->criarEndereco($cidade);
        
        $nome = $this->faker->unique()->word;
        $cnpj =  $this->faker->unique()->randomNumber(8);
        $telefone = $this->faker->unique()->phoneNumber;
        $telefone2 = $this->faker->unique()->phoneNumber;
        $descricao = $this->faker->unique()->text;

        $transacao = new AddFornecedorTransaction($nome, $empresa->id, $cnpj, $telefone, $telefone2, $descricao, $enderecoFornecedor->id);
        $transacao->execute();

        return Fornecedor::where('nome', $nome)->where('cnpj', $cnpj)->first();
    }

    protected function crarMetodoDePagamento() : MetodoPagamento
    {
        $nome = $this->faker->unique()->word;
        
        $transacao = new AddMetodoPagamentoTransaction($nome);
        $transacao->execute();

        return MetodoPagamento::where('nome', $nome)->first();
    }
}