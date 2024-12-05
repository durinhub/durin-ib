<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ads;
use App\Helpers\Funcoes;
use App\Enums\ActivityLogClass;
use App\Enums\AdminRightsEnum;
use Illuminate\Support\Facades\Validator;

use Purifier;
use Auth;
use Cache;
use Storage;
use Carbon\Carbon;

class AdsController extends Controller
{
    public static function getAll(){
        if(Cache::has('ads'))
            return Cache::get('ads');

        $ads = Ads::all();
        Cache::forever('ads', $ads);
        return $ads;
    }

    public static function getRandom(){
        return Ads::inRandomOrder()->first();
    }
    
    public function getIndex(){
        Funcoes::consolelog('AdsController::getIndex');
        
        if(!(Auth::check()) || !$this->temBiscoitoAdmin()){ 
            Funcoes::consolelog('AdsController::getIndex erro: não autenticado ou não tem biscoito admin');
            abort(404);
        }
        if(!Auth::user()->canDo(AdminRightsEnum::ManageAds)){
            $this->logAuthActivity("Tentou visitar gerencia ads sem permissão", ActivityLogClass::Info);
            abort(404);
        }
        
        $this->logAuthActivity("Visitou gerencia ads", ActivityLogClass::Info);
        $adlist = AdsController::getAll();
        return view('pages.ads')
                ->with('adlist', $adlist)
                ->withSecretas(true);
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nome' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'dataexp' => ['required', 'date'],
            'adcreative' => ['required', 'file', 'mimes:jpeg,png,gif'],
        ], $messages = [
            'nome.required' => 'É necessário um nome',
            'nome.string' => 'Nome inválido',
            'nome.max' => 'Tamanho máximo 255 caracteres',

            'url.required' => 'É necessário uma URL',
            'url.string' => 'URL inválida',
            'url.max' => 'Tamanho máximo 255 caracteres',

            'dataexp.required' => 'É necessário uma data de expiração do anuncio',
            'dataexp.date' => 'Data inválida de expiração',

            'adcreative.required' => 'É necessário uma imagem para o anuncio',
            'adcreative.file' => 'A arte deve ser uma imagem',
            'adcreative.mimes' => 'Tipos de arquivos permitidos: gif, jpg, gif',

        ]);
    }
    
    private function setaArteAd(Request $request, $ad){
        if($ad->resource){
            Storage::disk('public')->delete($ad->resource);
        }
        $ad->resource = '';
        $ad->save();

        $ad->resource = 'ads/' . $ad->id . '.' . $request->file('adcreative')->extension();

        Storage::disk('public')->putFileAs('', $request->file('adcreative'), $ad->resource);
        $ad->save();

    }

    public function store(Request $request){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ManageAds)){
            $this->validator($request->all())->validate();
            $this->logAuthActivity("Criou novo ad", ActivityLogClass::Info);
            $ad = new Ads;
            
            $ad->nome = strip_tags(Purifier::clean($request->nome)); 
            $ad->url = strip_tags(Purifier::clean($request->url)); 
            $ad->dataexp = new Carbon(strip_tags(Purifier::clean($request->dataexp)));
            $this->setaArteAd($request, $ad);

            Cache::forget('ads');
            return Redirect('/gerenciaads');
            
        }
        return Redirect('/');
    }


    public function destroy($id){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ManageAds)){
            $id = strip_tags(Purifier::clean($id));
            $ad = Ads::find($id);
            if($ad){
                Storage::disk('public')->delete($ad->resource);
                $ad->delete();
                $this->logAuthActivity("Deletou a ad de id " . $id, ActivityLogClass::Info);
                Cache::forget('ads');
                return Redirect('/gerenciaads');
            }
        }
        return Redirect('/');
    }
        
    public function update(Request $request){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ManageAds)){
            $this->logAuthActivity("Editou uma ad e atualizou o cache", ActivityLogClass::Info);
            $ad = Ads::find($request->id);
            if($ad){
                if($request->url)
                    $ad->url = strip_tags(Purifier::clean($request->url)); 
                if($request->nome)
                    $ad->nome = strip_tags(Purifier::clean($request->nome));
                if($request->dataexp)
                    $ad->dataexp = new Carbon(strip_tags(Purifier::clean($request->dataexp)));
                if($request->adcreative)
                    $this->setaArteAd($request, $ad);
                
                $ad->update();
                Cache::forget('ads');
                return Redirect('/gerenciaads');
            }
        }
        return Redirect('/');
    }
    
    public function getArquivo($filename){
        Funcoes::consolelog('AdsController::getArquivo ' . $filename);
        $filename = strip_tags(Purifier::clean($filename));
        $filename = 'ads/' . $filename;
        $arq = Ads::where('resource', '=', $filename)->get()->count();
        if(Storage::disk('public')->has($filename) && $arq){
            $fullpath = "app/public/" . $filename;
            return response()->download(storage_path($fullpath), null, [], null);
        } else 
        {
            Funcoes::consolelog('AdsController::getArquivo Arquivo não encontrado');
            abort(404);
        }
    }
}
