<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\ActivityLogHistory;
use App\Helpers\Funcoes;

class ActivityLogHistoryController extends Controller
{
    public static function store(ActivityLog $log)
    {
        try{
            $hist = new ActivityLogHistory;
            $hist->id = $log->id;
            $hist->autor_id = $log->autor_id;
            $hist->message = $log->message;
            $hist->class = $log->class;
            $hist->created_at = $log->created_at;
            $hist->save();
            return true;
        }catch(Exception $e)
        {
            Funcoes::consolelog('ActivityLogHistoryController::store error: ' . $e->getMessage());
            return false;
        }
    }
    public static function getAll($request){
        $ret = ActivityLogHistory::orderBy('created_at','desc');

        if($request->autor_id){
            $ret->where('autor_id', '=', $request->autor_id);
        }

        if($request->dataini)
            $ret->whereDate('created_at', '>=', $request->dataini);

        if($request->datafim)
            $ret->whereDate('created_at', '<=', $request->datafim);
        
        return $ret->simplePaginate(20);
    }
}
