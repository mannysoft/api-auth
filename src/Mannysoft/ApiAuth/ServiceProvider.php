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

        $timestamp = date('Y_m_d_His', time());
        $this->publishes([
            __DIR__.'/database/migrations/update_users_tables.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_update_users_tables.php",
        ], 'migrations');

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
