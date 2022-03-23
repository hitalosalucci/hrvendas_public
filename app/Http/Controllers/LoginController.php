<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.login', ['titulo' => 'Login']);
    }

    /*Função de login */
    public function autenticar(Request $request)
    {
        $resposta = $this->fazerAutenticacao($request, 'web');

        if ($resposta['status'] == 1)
        {
            $usuario = Auth::user();
            $empresa = $usuario->empresa;

            // if (!$empresa->ativa)
            // {
            //     Auth::logout();
            //     $resposta['status'] = 2;
            // }
        }

        return $resposta;
    }

    private function fazerAutenticacao(Request $request, string $guard)
    {
        $credenciais = [
            'login'=>$request->input('nome-login'),
            'password'=>$request->input('senha-login')
        ];

        if (Auth::guard($guard)->attempt($credenciais))
            $resposta = ['status' => 1];
        else
            $resposta = ['status' => 0];

        return $resposta;
    }

        /* Função de logout*/
        public function sair()
        {
            Auth::logout();
            return redirect('/login');
        }

}
