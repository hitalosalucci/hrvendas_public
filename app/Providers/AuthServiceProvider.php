<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerPolicies();

        Gate::define('mesma-empresa', function ($usuario, $objeto)
        {
            return $usuario->empresa->id == $objeto->empresa->id;
        });

        //adaptação tecnica temporaria até implementar as permissões de usuário
        Gate::define('autorizacao-relatorios', function ($usuario)
        {
            return $usuario->id == 1;
        });
    }
}
