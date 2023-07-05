@extends('layouts.app')

@section('titulo')
    Página principal
@endsection

@auth
    @section('subtitulo')
        Siguiendo
    @endsection

    @section('contenido')
        <x-listar-post :posts="$posts" />
    @endsection
@endauth

@section('subtitulo_2')
    Últimos Posts
@endsection

@section('contenido_2')
    <x-listar-post :posts="$postsAll" />
@endsection