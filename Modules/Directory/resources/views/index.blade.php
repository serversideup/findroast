@extends('directory::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('directory.name') !!}</p>
@endsection
