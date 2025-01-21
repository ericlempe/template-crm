<?php

namespace App\Audit;

use OwenIt\Auditing\Contracts\{Auditable, Resolver};

class ImpersonateResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {
        return session()->get('impersonator');
    }
}
