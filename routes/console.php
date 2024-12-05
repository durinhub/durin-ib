<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Schedule;
use App\Helpers\Funcoes;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Funcoes::consolelog('Iniciando rotina de limpeza de logs antigos');
    ActivityLogController::storeHistory();
})->dailyAt('06:00');