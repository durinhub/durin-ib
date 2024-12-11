@extends('main')


@section('titulo', $nomeib)

@section('stylesheets')
    <link rel="stylesheet" href="/css/style.css" >
@stop

@section('conteudo')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 text-center">
            <h2>Posts por dia</h2>
            <canvas id="canvasPpd" style="width:100%;max-width:1400px"></canvas> 
        </div>
        <div class="col-sm-2"></div>
    </div>

</div>

@endsection


@section('scripts')

<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script> 

<script>
    const xPpd = 
    [
        @foreach ($ppd as $p)
            '{{ $p->dia }}', 
        @endforeach
    ];
    const yPpd = 
    [
        @foreach ($ppd as $p)
            {{ $p->nr }}, 
        @endforeach
    ];

    new Chart("canvasPpd", {
        type: "line",
        data: {
            labels: xPpd,
            datasets: [{
            borderColor: "rgba(0,0,255,1)",
            data: yPpd
            }]
        },
        options: {
            legend: {display: false}
        }
    });

</script>

@endsection