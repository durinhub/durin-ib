<div class="text-center ibad">
    @if($ad)
        <a href="{{$ad->url}}" target="_blank"><img class="ad-img" src="{{ \Storage::url($ad->resource) }}"/></a>
    @else
        <span class="free-text">Anuncie aqui</span>
    @endif
</div>