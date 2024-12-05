<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anao;
use App\Models\AnaoIp;
use App\Helpers\Funcoes;
use Config;
use Purifier;

class AnaoController extends Controller
{
    // obtem código do país baseado no IP
    protected function obtemCountryCode($ip) {
        Funcoes::consolelog('AnaoController::obtemCountryCode: ip: ' . $ip);
        if(preg_match('/^127\..+$/', $ip) 
        || preg_match('/^192\.168\..+$/', $ip)
        || preg_match('/^10\..+$/', $ip)
        ) return array('br','mg'); // se teste em rede local/loopback, retorna brasil

        $url = "http://ip-api.com/json/{$ip}";
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
    
        curl_close($ch);
    
        $data = json_decode($response, true);
    
        if (isset($data['proxy']) && $data['proxy']) {
            return array('on',null);
        }
    
        if (isset($data['countryCode'])) {
            $ccode = strtolower(strip_tags(Purifier::clean($data['countryCode'])));
            $regcode = ($ccode === "br" ? strtolower(strip_tags(Purifier::clean($data['region']))) : null);
            return array($ccode, $regcode);
        }
    
        return array('on',null);
    }
    
    public function salvaAnao($biscoito){
        $request = Request();

        $userAgent = strip_tags(Purifier::clean($request->server('HTTP_USER_AGENT')));
        $ip = strip_tags(Purifier::clean($request->server('REMOTE_ADDR')));
        $hostname = gethostbyaddr($ip);
        $accept_encoding = strip_tags(Purifier::clean($request->server('HTTP_ACCEPT_ENCODING')));
        $accept_language = strip_tags(Purifier::clean($request->server('HTTP_ACCEPT_LANGUAGE')));

        Funcoes::consolelog('AnaoController::salvaAnao: ' . $biscoito . ' userAgent: ' . $userAgent . ' ip: ' . $ip);
        $anao = new Anao;

        $anaoIp = new AnaoIp;
        $anaoIp->ip = $ip;
        $anaoIp->biscoito = $biscoito;

        $anao->biscoito = $biscoito;

        $anao->hostname = $hostname;
        $anao->http_acccept_encoding = $accept_encoding;
        $anao->http_acccept_language = $accept_language;
        list($countrycode, $regioncode) = $this->obtemCountryCode($ip);
        $anao->countrycode =  $countrycode;// country code do IP é armazenado para não ter que ficar recalculando em tempo de execução
        $anao->regioncode =  $regioncode;// country code do IP é armazenado para não ter que ficar recalculando em tempo de execução
        $anao->user_agent = $userAgent;
        try{
            $anao->save();
        }catch(\Illuminate\Database\QueryException $e)
        {
            Funcoes::consolelog('AnaoController::salvaAnao erro: ', $e->getMessage());
        }
        try{
            $anao->ips()->save($anaoIp); // ip do postador
        }catch(\Illuminate\Database\QueryException $e)
        {
            Funcoes::consolelog('AnaoController::salvaAnao erro: ', $e->getMessage());
        }
    }

    public function existeAnaoDb($biscoito){
        return Anao::find($biscoito);
    }
}
