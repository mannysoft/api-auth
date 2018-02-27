<?php 

namespace Mannysoft\ApiAuth;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Passport\Passport;

class ServiceProvider extends BaseServiceProvider {
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__.'/config/api-auth.php' => config_path('api-auth.php'),
            ], 'config');


        Passport::routes();

        Passport::tokensCan([
            'show' => 'Show',
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        include __DIR__.'/routes/web.php';
    }  

}
