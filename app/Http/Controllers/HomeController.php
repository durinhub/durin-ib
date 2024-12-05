<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Cache;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function seedar(){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::SeedDb)){
            try{
                $this->logAuthActivity("Seedou o banco de dados", ActivityLogClass::Info);
                if(App::isProduction()){
                    Artisan::call('app:seed-prod');
                } else {
                    Artisan::call('db:seed');
                }
                $this->limparCache();
            }catch(\Illuminate\Database\QueryException $e){
                    
            }
        }
        return Redirect::to('/admin');
    }
    
    public function migrate(){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::MigrateDb)){
            $this->logAuthActivity("Migrou o banco de dados", ActivityLogClass::Info);
            Artisan::call('migrate');
            $this->limparCache();
        }
        return Redirect::to('/');
    }
    
    public function migrateRefresh(){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::MigrateRefreshDb)){
            $this->logAuthActivity("Reiniciou o banco de dados (migrate refresh + seed)", ActivityLogClass::Info);
            Artisan::call('migrate:refresh');
            if(App::isProduction()){
                Artisan::call('app:seed-prod');
            } else {
                Artisan::call('db:seed');
            }
            Artisan::call('cache:clear');
            $this->limparCache();
        }
        return Redirect::to('/login');
    }
    
    public function limparCache()
    {
        if(Auth::check() &&  Auth::user()->canDo(AdminRightsEnum::LimpaCache)){
            $this->logAuthActivity("Limpou o cache do servidor", ActivityLogClass::Info);
            Cache::flush();
            return Redirect::to('/admin');
        }
    }
    
}
