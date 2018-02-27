<?php 

namespace Mannysoft\ApiAuth;

class Facade extends \Illuminate\Support\Facades\Facade {
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api-auth';
    }
}