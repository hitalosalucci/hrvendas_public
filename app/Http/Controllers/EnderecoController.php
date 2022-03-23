<?php

namespace App\Http\Controllers;

use App\Model\Endereco\Endereco;
use App\Transactions\Endereco\AddEnderecoTransaction;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public static function salvarEndereco($enderecoArray)
    {
        $bairro = $enderecoArray['bairro'];
        $rua = $enderecoArray['rua'];
        $referencia = $enderecoArray['referencia'];
        $numero = $enderecoArray['numero'];
        $idCidade = $enderecoArray['cidade']['idCidade'];
        
        $addEndereco = new AddEnderecoTransaction($bairro, $rua, $numero, $idCidade, $referencia);
        $addEndereco->execute();

        $enderecoCriado = Endereco::select('*')->where('bairro', $bairro)->where('referencia', $referencia)->orderBy('id', 'desc')->first();

        return ['idEndereco' => $enderecoCriado->id];
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
        //
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
}
