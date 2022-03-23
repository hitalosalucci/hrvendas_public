<?php

namespace App\Transactions\Traits;

use Illuminate\Support\Facades\Gate;

trait VerificaEmpresa
{
    protected function autorizarMesmaEmpresa($objeto)
    {
        Gate::authorize('mesma-empresa', $objeto);
    }
}

?>