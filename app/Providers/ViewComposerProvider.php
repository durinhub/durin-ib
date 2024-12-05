<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ConfiguracaoController;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view)
        {
            $view->with([
                'boards' => BoardController::getAll(),
                'configuracaos' => ConfiguracaoController::getAll()
            ]);
        });
        
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
