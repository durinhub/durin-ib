<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ActivityLog;
use Carbon\Carbon;
use App\Helpers\Funcoes;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;
use Purifier;
use Auth;


class ActivityLogController extends Controller
{
    public static function store($autor_id, $message, $class)
    {
        $log = new ActivityLog;
        $log->autor_id = $autor_id;
        $log->message = $message;
        $log->class = $class->value;
        $log->save();
    }

    public static function storeHistory()
    {
        $logs = ActivityLog::whereDay('created_at', '<', Carbon::today())->get();
        foreach($logs as $log)
        {
            if(ActivityLogHistoryController::store($log))
            {
                $log->delete();
            }
        }
    }

    private function getAll($request){
        if($request->history){
            return ActivityLogHistoryController::getAll($request);
        }
        $ret =  ActivityLog::orderBy('created_at','desc');

        if($request->autor_id){
            $ret->where('autor_id', '=', $request->autor_id);
        }

        if($request->dataini)
            $ret->whereDate('created_at', '>=', $request->dataini);

        if($request->datafim)
            $ret->whereDate('created_at', '<=', $request->datafim);
        
        return $ret->simplePaginate(20);
    }
        
    public function getActivityLogsPage(Request $request){
        $request = $this->purifyAllParams($request);
        Funcoes::consolelog('ActivityLogController::getActivityLogsPage');
        if(Auth::user()->canDo(AdminRightsEnum::SeeActivityLogs)){
            $this->logAuthActivity("Acessou a página de logs de atividade", ActivityLogClass::Info);
            if(!$this->temBiscoitoAdmin()){ 
                Funcoes::consolelog('ActivityLogController::getActivityLogsPage erro: não autenticado ou não tem biscoito admin');
                abort(404);
            }
            
            $logs = $this->getAll($request);

            $ret = view('pages.activitylogs')
                ->withLogs($logs)
                ->withSecretas(true);

            if($request->autor_id)
                $ret->withFilterAutorId($request->autor_id);

            if($request->dataini)
                $ret->withFilterDataIni($request->dataini);

            if($request->datafim)
                $ret->withFilterDataFim($request->datafim);

            if($request->history)
                $ret->withHistory($request->history);

            return $ret;
        }
        return Redirect('/admin');
    }
}
