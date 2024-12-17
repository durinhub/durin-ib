@foreach ($arquivos->where('thumb', '=', false) as $arq)
    <div class="fio-img-div">
        @php
            $divId = preg_replace("/[-.]/i", "", $arq->filename); 
        @endphp
        <a class="sign-btn" onclick="mostraEscondeDivArquivo('{{ $divId }}')">
            <span id="{{ $divId }}-ctrl" class="mini-btn glyphicon glyphicon-minus"></span>
        </a>
        <span data-toggle="tooltip" 
              data-placement="top" 
              title="@if($arq->filesize)Tamanho: {{ App\Helpers\Funcoes::trataFilesize($arq->filesize) }}  @endif{{ $arq->original_filename }}">
            {{ substr($arq->original_filename,0,30) }}
        </span>
        <br>
    <div id="{{$divId}}">
    @if($arq->mime === 'image/jpeg' || $arq->mime === 'image/png' || $arq->mime === 'image/gif' )
        <a href="/storage/{{ $arq->filename }}" target="_blank" onclick="expandirImg(this)">
        <img class="img-responsive img-thumbnail" 
            @if($arq->spoiler) src="{{ \Storage::url('res/spoiler.png') }}" data-spoiler="1" data-spoiler-src="{{ \Storage::url($arq->filename) }}" 
            @else src="{{ \Storage::url($arq->filename) . ".thumb.jpg" }}" data-spoiler="0" 
            @endif
            width="200px" height="200px" ></a>
    @elseif($arq->mime === 'video/mp4' || $arq->mime === 'video/webm')
        <img class="img-responsive vid-thumbnail"  onclick="expandirVid(this,'{{$divId}}')"
        @if($arq->spoiler) src="{{ \Storage::url('res/spoiler.png') }}" data-spoiler="1" data-spoiler-src="{{ \Storage::url($arq->filename) }}" 
        @else src="{{ \Storage::url($arq->filename) . ".thumb.png" }}" data-spoiler="0" 
        @endif
        data-type="{{ $arq->mime }}" width="200px" height="200px" >
    @elseif($arq->mime === 'audio/mpeg')
     <audio controls>
        <source src="/storage/{{ $arq->filename }}" type="audio/mpeg">
     </audio>
    @endif
    </div>
    
    @if(Auth::check()) 
    <a data-toggle="tooltip" data-placement="top" title="Deletar arquivo" href="/boards/deleteimg/{{ $siglaBoard }}/{{ $arq->filename }}" class="mini-btn"><span class="glyphicon glyphicon-trash"></span></a>
    <a data-toggle="tooltip" data-placement="top" title="Colocar spoiler" href="/boards/spoilimg/{{ $siglaBoard }}/{{ $arq->filename }}" class="mini-btn"><span class="glyphicon glyphicon-eye-close"></span></a><br><br>
    @endif
    </div>
@endforeach