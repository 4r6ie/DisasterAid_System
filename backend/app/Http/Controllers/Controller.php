<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Clamp a user-supplied page size so a client cannot ask for an
     * unbounded number of records in a single request.
     */
    protected function perPage(int $default = 15, int $max = 100): int
    {
        $requested = (int) request()->query('per_page', $default);

        return max(1, min($requested, $max));
    }
}
