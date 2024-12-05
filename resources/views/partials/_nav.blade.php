<div class="container-fluid ibnav">
@if(isset($boards))
    <div class="ibnavl">
        [
        @foreach($boards as $board)
            @if(!$board->secreta)
            <a href="/boards/{{ $board->sigla }}">{{ $board->sigla }}</a> /
            @endif
        @endforeach
        ]
    </div>

    <div class="ibnavr">
        [
        <a data-toggle="modal" data-target="#modalConf" id="toggleConf">Conf</a>
        / <a href="/">Home</a>
        / <a href="/catalogo{{ ( isset($secretas) && $secretas ? '?secretas=1': '') }}">Catalogo</a>
        @if(Auth::check()) 
        / <a href="/admin">Admin</a>
        @endif
        @if(!App::isProduction() && !Auth::check()) 
        / <a href="/login">Login</a>
        @elseif(!App::isProduction() && Auth::check()) 
        / <a href="/logout">Logout</a>
        @endif
        ]
    </div>
    <hr>
    
@endif
</div>