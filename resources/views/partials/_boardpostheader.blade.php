<div class="container-fluid">
    <div class="row" id="div-form"> 
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center">
        @include('partials._postform',  [ 'configuracaos' => $configuracaos ])
        <a href="{{ Request::url() }}#div-footer">[Ir para o final da página]</a>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>