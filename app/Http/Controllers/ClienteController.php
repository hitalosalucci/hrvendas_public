<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Model\Endereco\Cidade;
use App\Model\Endereco\Endereco;
use App\Transactions\Cidade\AddCidadeTransaction;
use App\Transactions\Cliente\AddClienteTransaction;
use App\Transactions\Cliente\AlterarClienteTransaction;
use App\Transactions\Endereco\AddEnderecoTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{

    use FormataErrosTransacoes;

    protected function getNomeCampo() : string
    {
        return 'cliente';
    }

    public function index()
    {
        return view('cliente.cliente', ['titulo' => 'Gerenciar Clientes']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $cidadeInformada = $request->input('cidade-endereco');
        $estadoInformado = $request->input('estado-endereco');

        if($cidadeInformada != null && $estadoInformado != null)
            $endereco = $this->verificarEndereco($request);
        else 
            $endereco = null;

        try
        {
            $resposta = $this->salvarCliente($request, $endereco);
        }
        catch(TransactionException $ex)
        {
            $resposta = [
                'status' => 0,
                'erros' => $this->formatarErrosTransacao($ex->getErros()),
            ];
        }

        return $resposta;
    }

    private function verificarEndereco($request)
    {

        $cidade = $this->verificarCidade($request->input('cidade-endereco'), $request->input('estado-endereco'));

        $enderecoDigitadoArray = [
            'cidade' => $cidade,
            'bairro' => $request->input('bairro-endereco'),
            'rua' => $request->input('rua-endereco'),
            'numero' => $request->input('numero-endereco'),
            'referencia' => $request->input('referencia-endereco'),
        ];

        $enderecoExiste = Endereco::verificarSeEnderecoExiste($enderecoDigitadoArray);

        if($enderecoExiste == false)
            $resposta = EnderecoController::salvarEndereco($enderecoDigitadoArray);
        else
            $resposta = $enderecoExiste;

        return $resposta;

    }

    private function verificarCidade($cidadeInformada, $estadoInformado)
    {
        $cidadeExiste = Cidade::verificarSeCidadeExiste($cidadeInformada, $estadoInformado);

        //se cidade não existir
        if($cidadeExiste == false){   
            $addCidade = new AddCidadeTransaction($cidadeInformada, $estadoInformado);
            $addCidade->execute();

            $cidadeCriada = Cidade::where('nome', $cidadeInformada)->first();

            $resposta = ['idCidade' => $cidadeCriada->id];
        }
        else
            $resposta = $cidadeExiste;

        return $resposta;
    }

    private function salvarCliente(Request $request, $endereco)
    {
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $nome = $request->input('nome-cliente', '');
        $telefone = $request->input('telefone-cliente', '');
        $telefone2 = $request->input('telefone2-cliente', '');
        $dataNascimento = $request->input('data_nascimento-cliente');
        $cpf = $request->input('cpf-cliente', '');
        $identidade = $request->input('identidade-cliente', '');
        
        //$idEndereco = $request->input('', '');

        //tratamento das informações
        $nome == null ? $nome = '' : $nome = $nome;
        $endereco == null ? $endereco = null : $endereco = $endereco['idEndereco'];

        $addCliente = new AddClienteTransaction($nome, $empresa->id, $telefone, $telefone2, $dataNascimento, $cpf, $identidade, $endereco);
        $addCliente->execute();

        return ['status' => 1];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $cidadeInformada = $request->input('cidade-endereco');
        $estadoInformado = $request->input('estado-endereco');

        if($cidadeInformada != null && $estadoInformado != null)
            $endereco = $this->verificarEndereco($request);
        else 
            $endereco = null;

        try
        {
            $resposta = $this->atualizarCliente($request, $endereco, $id);
        }
        catch(TransactionException $ex)
        {
            $resposta = [
                'status' => 0,
                'erros' => $this->formatarErrosTransacao($ex->getErros()),
            ];
        }

        return $resposta;

    }

    private function atualizarCliente(Request $request, $endereco, $id)
    {
        $nome = $request->input('nome-cliente', '');
        $telefone = $request->input('telefone-cliente', '');
        $telefone2 = $request->input('telefone2-cliente', '');
        $dataNascimento = $request->input('data_nascimento-cliente');
        $cpf = $request->input('cpf-cliente', '');
        $identidade = $request->input('identidade-cliente', '');
        
        //$idEndereco = $request->input('', '');

        //tratamento das informações
        $nome == null ? $nome = '' : $nome = $nome;
        $endereco == null ? $endereco = null : $endereco = $endereco['idEndereco'];

        $addCliente = new AlterarClienteTransaction($id, $nome, $telefone, $telefone2, $dataNascimento, $cpf, $identidade, $endereco);
        $addCliente->execute();

        return ['status' => 1];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getListaJson()
    {
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        $clientes = $empresa->clientes()->get();

        $clientesArray = [];

        foreach($clientes as $cliente)
        {
            $clientesArray[] = [
                'id' => $cliente->id,
                'nome' => $cliente->nome,
                'cpf' => $cliente->cpf,
                'telefone' => $cliente->telefone,
                'telefone2' => $cliente->telefone2,
                'data_nascimento' => $cliente->data_nascimento,
                'identidade' => $cliente->identidade,
                'ultima_alteracao' => $cliente->updated_at->format('d/m/Y H:i'),
                'data_cadastro' => $cliente->created_at->format('d/m/Y H:i'),
                'endereco_bairro' => $cliente->endereco->bairro,
                'endereco_rua' => $cliente->endereco->rua,
                'endereco_numero' => $cliente->endereco->numero,
                'endereco_referencia' => $cliente->endereco->referencia,
                'endereco_cidade' => $cliente->endereco->cidade->nome,
                'endereco_estado' => $cliente->endereco->cidade->estado->sigla,
                'endereco_estado_id' => $cliente->endereco->cidade->estado->id,
            ];
        }

        return response()->json($clientesArray);
    }

}
