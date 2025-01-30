@extends('boardbase')


@section('titulo', $nomeBoard)

@section('adstop')
@include('partials._genericad', ['ad' => $ad1])
@endsection

@section('conteudo')

@include('partials._boardpostheader')
<hr>
<div id="isPostShow" hidden></div>
@include('partials._postposts')

@if(Auth::check())
@include('partials._modalban')
@include('partials._modaldeleteboard')
@include('partials._modalmoverpost')
@endif

@include('partials._modalreport')
@include('partials._modaldeletepost')
@endsection

@section('adsbottom')
@include('partials._genericad', ['ad' => $ad2])
@endsection
