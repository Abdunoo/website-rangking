<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;
    //
}
