<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class GithubServiceFacade extends Facade
{
    
    protected static function getFacadeAccessor()
    {
        return 'api-github';
    }
}
