@if(isset($regras) && count($regras) > 0)
<div class="container-fluid">
    <div class="row"> 
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center div-indice">
            <br><b>Regras</b><br><br>
            @foreach($regras as $ind => $regra)
            <span class="free-text">{{ $ind+1 }} - {{ $regra->descricao }}
            @if(Auth::check())
                <a 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="Deletar regra" 
                    class="mini-btn" 
                    href="/deleteregra/{{ $regra->id }}"><span class="glyphicon glyphicon-remove"></span></a>
            @endif
            </span>
            <br>
            @endforeach
            <br><br>
        </div>

        <div class="col-sm-4"></div>
    </div>
</div>
@endif