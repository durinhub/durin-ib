<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyHeaders;
use App\Http\Middleware\VerificaCookieArquivo;
use App\Http\Middleware\XFrameOptionsHeader;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegraController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\ActivityLogController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([VerificaCookieArquivo::class])->group(function(){
    Route::get('/storage/{filename}', [PagesController::class, 'getArquivo']);
    Route::get('/storage/ads/{filename}', [AdsController::class, 'getArquivo']);
});

Route::middleware([xFrameOptionsHeader::class,VerifyHeaders::class])->group(function(){
    Route::get('/', [PagesController::class, 'getIndex'])
        ->middleware('throttle:45,1,directory');
    
    Route::get('/boards/{siglaBoard}', [PagesController::class, 'getBoard'])
        ->where('siglaBoard', '[a-zA-Zç]{1,10}')
        ->middleware('throttle:45,1,directory');
        
    Route::get('/boards/{siglaBoard}/{thread}', [PagesController::class, 'getThread'])
        ->where('siglaBoard', '[a-zA-Zç]{1,10}')
        ->where('thread', '[0-9]+')
        ->name('post.single')
        ->middleware('throttle:45,1,directory');
    
    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    Route::post('/report', [PostController::class, 'report'])
        ->name('posts.report');

    Route::get('/post/{siglaBoard}/{postId}', [PostController::class, 'single'])
        ->where('siglaBoard', '[a-zA-Zç]{1,10}')
        ->where('postId', '[0-9]+')
        ->name('post.single');

    Route::get('/catalogo', [PagesController::class, 'getCatalogo'])
        ->middleware('throttle:45,1,directory');
    
    Route::post('/boards/deletepost', [PostController::class, 'deletaPost'])
        ->name('posts.destroy')
        ->middleware('throttle:45,1,directory');
        
    Route::get('/login', [PagesController::class, 'getLogin'])
        ->middleware('throttle:6,1,directory');
});

Route::group(['middleware'=>['auth']], function(){
    
    Route::get('/logout', [PagesController::class, 'logout']);
    
    Route::get('/boards/pinarpost/{siglaBoard}/{post_id}/{val}', [PostController::class, 'pinarPost'])
        ->where('post_id', '[0-9]+')
        ->where('siglaBoard', '[a-zA-Zç]{1,10}')
        ->where('val', '(1|0)');
        
    Route::get('/boards/trancarpost/{siglaBoard}/{post_id}/{val}', [PostController::class, 'trancarPost'])
        ->where('post_id', '[0-9]+')
        ->where('siglaBoard', '[a-zA-Zç]{1,10}')
        ->where('val', '(1|0)');
    
    Route::get('/boards/deleteimg/{siglaBoard}/{filename}', [PostController::class, 'destroyArqDb'])
        ->where('filename', '[0-9\-]+\.[a-zA-Z0-9]+')
        ->where('siglaBoard', '[a-zA-Zç]{1,10}');
        
    Route::get('/deleteregra/{id}', [RegraController::class, 'destroy'])
        ->where('id', '[0-9]+');
        
    Route::get('/boards/deleteboard/{id}', [BoardController::class, 'destroy'])
        ->where('id', '[a-zA-Zç]{1,10}');
        
    Route::get('/deletenoticia/{id}', [NoticiaController::class, 'destroy'])
        ->where('id', '[0-9]+');
        
    Route::get('/editnoticia/{id}', [NoticiaController::class, 'edit'])
        ->where('id', '[0-9]+');
        
    Route::get('/admin', [PagesController::class, 'getAdmPage']);
    Route::post('/userban', [Controller::class, 'banirUsuario'])
        ->name('bans.userban');
    
    Route::post('/nova_noticia', [NoticiaController::class, 'store'])
        ->name('noticias.nova_noticia');
    Route::post('/update_noticia', [NoticiaController::class, 'update'])
        ->name('noticias.update_noticia');
    
    Route::post('/update_password', [UserController::class, 'updatePassword'])
        ->name('users.update_password');
    
    Route::post('/updatedireitos', [UserController::class, 'updateDireitos'])
        ->name('users.updatedireitos');

    Route::get('/getdireitos/{id}', [UserController::class, 'getAdminRights'])
        ->where('id', '[0-9]+');

    Route::get('/userlocked/{id}', [UserController::class, 'getIsUserLocked'])
        ->where('id', '[0-9]+');

    Route::post('/togglelockuser', [UserController::class, 'toggleLockUser'])
        ->name('users.togglelockuser');
        
    Route::post('/regra', [RegraController::class, 'store'])
        ->name('regras.regra');
    Route::get('/migrate', [HomeController::class, 'migrate']);
    Route::get('/seedar', [HomeController::class, 'seedar']);
    Route::get('/limparcache', [HomeController::class, 'limparCache']);
    //Route::get('/migrate/refresh', [HomeController::class, 'migrateRefresh']);
    Route::get('/togglecaptcha/{val}', [ConfiguracaoController::class, 'toggleCaptcha'])
        ->where('val', '(1|0)');
        
    Route::get('/adm_cookie_onoff/{val}', [ConfiguracaoController::class, 'toggleAdmCookie'])
        ->where('val', '(1|0)');
        
    Route::get('/togglepostsblock/{val}', [ConfiguracaoController::class, 'togglePostsBlock'])
        ->where('val', '(1|0)');
    
    Route::post('/boards/new', [BoardController::class, 'store'])
        ->name('boards.store');
    
    Route::get('/deletereport/{id}', [PostController::class, 'destroyReport'])
        ->where('id', '[0-9]+');
    
    Route::post('/movepost', [PostController::class, 'movePost'])
        ->name('posts.mover');
    
    Route::get('/gerenciaads', [AdsController::class, 'getIndex'])
        ->where('id', '[0-9]+');
        
    Route::post('/ads', [AdsController::class, 'store'])
        ->name('ads.ad');
        
    Route::get('/destroyad/{id}', [AdsController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->name('ads.destroy');

    Route::post('/ad_update', [AdsController::class, 'update'])
        ->name('ads.ad_update');

    Route::get('/activitylogs', [ActivityLogController::class, 'getActivityLogsPage'])
        ->name('activitylogs.list');

    Route::post('/aplica_filtros_posts', [PostController::class, 'aplicaFiltros'])
        ->name('posts.filters');
});

Auth::routes();
