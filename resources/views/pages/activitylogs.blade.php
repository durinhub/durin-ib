@extends('boardbase')


@section('titulo', 'Admin')

@section('conteudo')

<div class="row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    @if(isset($history))
        <h1 class="text-center">Histórico de logs</h1>
    @else
        <h1 class="text-center">Log de hoje</h1>
    @endif
    <form action="{{ route('activitylogs.list')}}" method="get">
    <h2 class="text-center">Filtros:</h2>
    <label for="autor_id">Id do admin</label>
    <input  class="form-control" type="text" name="autor_id" value="{{ isset($filterAutorId) ? $filterAutorId : '' }}" id="input_autor_id"/>

    <label for="dataini">Data Inicial</label>
    <input  class="form-control" type="date" name="dataini" value="{{ isset($filterDataIni) ? $filterDataIni : '' }}" id="input_dataini"/>

    <label for="datafim">Data Final</label>
    <input  class="form-control" type="date" name="datafim" value="{{ isset($filterDataFim) ? $filterDataFim : '' }}" id="input_datafim"/>

    <input type="hidden" name="history" value="{{ isset($history) ? 1 : 0 }}">
    <input type="submit" value="Filtrar" class="mini-btn form-control">
    </form>


</div>
<div class="col-sm-1"></div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <table>
            <tr>
                <th>Id admin</th>
                <th>Mensagem</th>
                <th>Classe</th>
                <th>Data</th>
            </tr>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->autor_id }}</td>
            <td>{{ $log->message }}</td>
            <td>{{ App\Enums\ActivityLogClass::from($log->class)->name }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
        @endforeach
        </table>
        <div class="text-center">
        {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
<br>
<div class="text-center">
    @if(isset($history))
        <a href="{{ route('activitylogs.list') . '?history=0' }}"><button type="button" class="btn btn-primary">Ver logs de hoje</button></a>
    @else
        <a href="{{ route('activitylogs.list') . '?history=1' }}"><button type="button" class="btn btn-primary">Ver histórico</button></a>
    @endif
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