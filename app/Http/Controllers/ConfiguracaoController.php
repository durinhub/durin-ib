<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use Illuminate\Http\Request;
use Cache;
use Auth;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;
use Redirect;

class ConfiguracaoController extends Controller
{
    public static function getAll(){
        $configs = Configuracao::orderBy('id')->first();
                
        Cache::forever('configs', $configs);
        return $configs;
    }
    
    public function toggleCaptcha($val){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ToggleCaptcha)){
            $config = Configuracao::find(1);
            if($val === "1" || $val === "0"){
                $logmsg = "" . ($val === "1" ? "Ativou ": "Desativou ") . "o captcha";
                $this->logAuthActivity($logmsg, ActivityLogClass::Info);
                $config->captcha_ativado = $val;
                $config->save();
                Cache::forget('configs');
                return Redirect::to('/admin');
            } else {
                return 'input invalido';
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function toggleAdmCookie($val){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ToggleAdmCookie)){
            $config = Configuracao::find(1);
            if($val === "1" || $val === "0"){
                $logmsg = "" . ($val === "1" ? "Desativou ": "Ativou ") . "o cookie de admin";
                $this->logAuthActivity($logmsg, ActivityLogClass::Info);
                $config->biscoito_admin_off = $val;
                $config->save();
                Cache::forget('configs');
                return Redirect::to('/admin');
            } else {
                return 'input invalido';
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function togglePostsBlock($val){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::BlockNewPosts)){
            $config = Configuracao::find(1);
            if($val === "1" || $val === "0"){
                $logmsg = "" . ($val === "1" ? "Bloqueou ": "Desbloqueou ") . "a criação de novos posts";
                $this->logAuthActivity($logmsg, ActivityLogClass::Info);
                $config->posts_block = $val;
                $config->save();
                Cache::forget('configs');
                return Redirect::to('/admin');
            } else {
                return 'input invalido';
            }
        } else {
            return Redirect::to('/');
        }
    }

}
