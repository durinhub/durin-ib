<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminRight;
use Auth;
use Redirect;
use Purifier;
use Hash;
use Cache;
use App\Helpers\Funcoes;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;

class UserController extends Controller
{
    public static function getAll(){
        if(Cache::has('users'))
            return Cache::get('users');
        
        $users = User::with(['rights'])->get();
        
        Cache::forever('users', $users);
        return $users;
    }
    
    public function getIsUserLocked($id){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ToggleLockUsers)){
            $id = strip_tags(Purifier::clean($id));
            $user = User::find($id);
            if(!$user) return response()->json();

            return response()->json($user->locked);
        }
        return response()->json();
    }

    public function getAdminRights($id){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ChangeRights)){
            $id = strip_tags(Purifier::clean($id));
            $user = User::find($id);
            $rights = array();
            $ret = array();
            if($user) $rights = $user->rights;
            else response()->json();
            foreach($rights as $r)
                array_push($ret, $r->right);

            return response()->json($ret);
        } else return response()->json();
    }

    public function defineArrayValidacao(){
        $regras = array();
        $regras['password'] = 'required|max:25';
        $regras['confirm_password'] = 'required|max:25';
        $regras['old_password'] = 'required|max:25';
        
        return $regras;
    }
    
    public function updatePassword(Request $request){
        if(Auth::check()){
            $this->logAuthActivity("Atualizou senha", ActivityLogClass::Info);
            Funcoes::consolelog('UserController::updatePassword');
            $user = Auth::user();
            $regras = $this->defineArrayValidacao();
            $this->validate($request, $regras);
            if(!$user)
            {
                return $this->redirecionaComMsg('erro_admin', 'Erro inesperado', '/admin');
            }
            if($request->password !== $request->confirm_password)
            {
                return $this->redirecionaComMsg('erro_admin', 'A nova senha e sua confirmação não batem', '/admin');
            }
            if(!Hash::check($request->old_password, $user->password))
            {
                return $this->redirecionaComMsg('erro_admin', 'Erro ao validar senha antiga', '/admin');
            }
            $user->password = Hash::make($request->password);
                
            $user->save();
            return $this->redirecionaComMsg('sucesso_admin', 'Senha alterada com sucesso', '/admin');
            
        }
        return Redirect('/');
    }

    public function updateDireitos(Request $request){
        Funcoes::consolelog('UserController::updateDireitos');
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ChangeRights)){
            $userToUpdate = strip_tags(Purifier::clean($request->admin));
            $userModel = User::find($userToUpdate);
            if($userModel && $userModel->canDo()){
                return $this->redirecionaComMsg('erro_admin', 'Este usuário não pode ser alterado', '/admin');
            }
            $params = $request->all();
            AdminRight::where('user_id', '=', $userToUpdate)->delete();
            foreach($params as $key => $param){
                if(str_contains(strip_tags(Purifier::clean($key)), 'checkbox-right-')){
                    $newRight = new AdminRight;
                    $newRight->user_id = $userToUpdate;
                    $newRight->right = strip_tags(Purifier::clean($param));
                    $newRight->save();
                }
            }

            $this->logAuthActivity("Atualizou direitos de admin de: " . $userModel->name, ActivityLogClass::Info);
            return Redirect('/admin');
        }
        abort(400);

    }

    public function toggleLockUser(Request $request){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ToggleLockUsers)){
            $userToUpdate = User::find(strip_tags(Purifier::clean($request->admin)));
            $val = strip_tags(Purifier::clean($request->val));
            $logmsg = ($val === "1" ? "Bloqueou ": "Desbloqueou ") . "o admin " . $userToUpdate->name . ' id ' . $userToUpdate->id;
            $this->logAuthActivity($logmsg, ActivityLogClass::Info);
            if($userToUpdate->canDo()){
                return $this->redirecionaComMsg('erro_admin', 'Este usuário não pode ser bloqueado', '/admin');
            }
            if($userToUpdate && ($val === "1" || $val === "0")){
                $userToUpdate->locked = $val;
                $userToUpdate->save();
                Cache::forget('users');
                return Redirect::to('/admin');
            } else {
                return 'input invalido';
            }
        } else {
            return Redirect::to('/');
        }
    }
}
