@extends('boardbase')


@section('titulo', 'Anuncios')

@section('conteudo')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h2 class="text-center">Criar novo anuncio</h2>
            <form action="{{ route('ads.ad') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                
                <label to="nome">Nome:</label>
                <input type="text" name="nome" class="form-control" required><br>

                <label to="url">URL:</label>
                <input type="url" name="url" class="form-control" required><br>

                <label to="adcreative">Arte:</label>
                <input type="file" name="adcreative" id="adcreative" class="form-control" required><br>

                <label to="dataexp">Data de expiração:</label>
                <input type="date" name="dataexp" class="form-control" required><br>

                <input type="submit" value="Criar novo ad" class="mini-btn form-control">
            </form>
        </div>
        <div class="col-sm-2"></div>
    </div>

</div>
<br><hr>
<div class="container-fluid">
<h2 class="text-center">Editar anuncios</h2>
@foreach($adlist as $ad)
<div class="row">
    <div class="col-sm-8">

        <form action="{{ route('ads.ad_update') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <label to="nome">Nome:</label>
            <input type="hidden" name="id" class="form-control" value="{{ $ad->id }}" required><br>
            <input type="text" name="nome" class="form-control" value="{{ $ad->nome }}"><br>

            <label to="url">URL:</label>
            <input type="url" name="url" class="form-control" value="{{ $ad->url }}"><br>

            <label to="adcreative">Arte:</label>
            <input type="file" name="adcreative" id="adcreative" class="form-control"><br>

            <label to="dataexp">Data de expiração:</label>
            <input type="date" name="dataexp" class="form-control"  value="{{ $ad->dataexp }}"><br>

            <div class="col-sm-8">
            <input type="submit" value="Editar anuncio" class="btn btn-success form-control">
            </div>
        </form>
            <div class="col-sm-4">
            <a href="{{ route('ads.destroy',$ad->id) }}"><button class="btn btn-danger form-control">Deletar anuncio</button></a>
            </div>

    </div>
    <div class="col-sm-4">
        <img class="text-center" width="300px" height="300px" src="{{ \Storage::url($ad->resource) }}" alt="Imagem inexistente">
    </div>
</div>
<hr>
@endforeach
</div>


@endsection