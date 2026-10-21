@extends('boardbase')


@section('titulo', 'Posts recentes')

@section('conteudo')

<div class="row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <h1 class="text-center">20 posts mais recentes</h1>
    <h2 class="text-center">Mais recente primeiro</h2>
</div>
<div class="col-sm-1"></div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <table>
            <tr>
                <th>Board</th>
                <th>Nro</th>
                <th>Assunto</th>
                <th>Conteúdo</th>
                <th>Data</th>
            </tr>
        @foreach($posts as $post)
        <tr>
            <td><a href="/boards/{{ $post->board }}">/{{ $post->board }}/</a></td>
            <td><a href="/boards/{{ $post->board }}/{{ ( $post->lead_id ? $post->lead_id . "#" . $post->id: $post->id ) }}" target="_blank">{{ $post->id }}</a></td>
            <td>{{ $post->assunto }}</td>
            <td>{{ strip_tags(substr($post->conteudo, 0, 120)) }}</td>
            <td>{{ $post->created_at }}</td>
        </tr>
        @endforeach
        </table>
    </div>
</div>
<br>
<div class="text-center">
    <a href="/admin"><button type="button" class="btn btn-primary">Voltar para o admin</button></a>
</div>
@endsection

@section('styles')
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: center;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
@endsection
