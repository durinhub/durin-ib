@extends('boardbase')


@section('titulo', $nomeBoard)

@section('adstop')
@include('partials._genericad', ['ad' => $ad1])
@endsection

@section('conteudo')

@include('partials._boardpostheader')
<hr>
@include('partials._postposts')

@if(Auth::check())
@include('partials._modalban')
@include('partials._modaldeleteboard')
@include('partials._modalmoverpost')
@endif

@include('partials._modalreport')
@endsection

@section('adsbottom')
@include('partials._genericad', ['ad' => $ad2])
@endsection
