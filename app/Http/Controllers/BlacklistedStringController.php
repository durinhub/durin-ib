<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlacklistedString;

use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;
use App\Helpers\Funcoes;
use Auth;
use Purifier;

class BlacklistedStringController extends Controller
{
    public function store(Request $request) {

        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::CreateBlacklistedStrings)){

            $this->validate($request, [
                'conteudo' => 'required|max:512'
            ]);

            $novaString = new BlacklistedString;
            $novaString->conteudo = strip_tags(Purifier::clean($request->conteudo));
            $novaString->save();

            Funcoes::consolelog('BoardController::store salvando nova blacklist string: ' . $request->conteudo);
            $this->logAuthActivity("Criou nova blacklist string " . $request->conteudo, ActivityLogClass::Info);
            return $this->redirecionaComMsg('sucesso_admin', "Nova blacklisted string criada", $request->headers->get('referer'));
        }
        
        return $this->redirecionaComMsg('erro_admin', "Requisição inválida", $request->headers->get('referer'));
    }

}
