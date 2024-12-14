@extends('main')


@section('titulo', $nomeib)

@section('stylesheets')
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="/css/mapa.css" >
@stop

@section('conteudo')
<div class="container-fluid snow-container">

    <div class="row">
        <div class="col-sm-12">
            <div class="text-center">
                <h2>Mapa dos posts</h2>
                <small><i>Inclui apenas posts com countryflag</i></small>
            </div><br>
            <div id="map"></div>
        </div>
    </div>

</div>

@endsection


@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>

let mapOptions = {
    center:[-10.92333, -37.02659],
    zoom: 3
};

let map = new L.map('map', mapOptions);
let layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);

@foreach ($dadosMapaDePosts as $ind=>$d)
    
    let customIcon{{$ind}} = {
        iconUrl: "/storage/res/flags/{{$d->countryregioncode}}.png",
        iconSize: [16,11]
    };
    let icon{{$ind}} = new L.icon(customIcon{{$ind}});

    let iconOptions{{$ind}} = {
        draggable: false,
        icon: icon{{$ind}}
    };

    let marker{{$ind}} = new L.Marker([{{$d->latitude}}, {{$d->longitude}},], iconOptions{{$ind}});
    marker{{$ind}}.addTo(map);

@endforeach

</script>

@endsection