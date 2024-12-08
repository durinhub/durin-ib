
@if (Session::has('post_criado'))

    <div class="alert alert-success" role="alert">
        <p>{!! Session::get('post_criado') !!}</p>
    </div>

@endif
@if (Session::has('post_deletado'))

<div class="alert alert-warning" role="alert">
    <p>{!! Session::get('post_deletado') !!}</p>
</div>

@endif
@if (Session::has('sucesso_admin'))

    <div class="alert alert-success" role="alert">
        <p><strong class="msg-strong">Sucesso:</strong> {!! Session::get('sucesso_admin') !!}</p>
    </div>

@endif
@if (Session::has('erro_admin'))

    <div class="alert alert-danger" role="alert">
        <p><strong class="msg-strong">Erro:</strong> {!! Session::get('erro_admin') !!}</p>
    </div>

@endif
@if (Session::has('erro_upload'))

    <div class="alert alert-warning" role="alert">
        <p><strong class="msg-strong">Post não criado:</strong> {{ Session::get('erro_upload') }}</p>
    </div>

@endif
@if (Session::has('ban'))

    <div class="alert alert-danger" role="alert">
        <p><strong class="msg-strong">Erro:</strong> {{ Session::get('ban') }}</p>
    </div>

@endif

@if( isset($errors) && count($errors) > 0)

<div class="alert alert-danger" role="alert">
    <p><strong class="msg-strong">Erro ao validar postagem:</strong></p>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif