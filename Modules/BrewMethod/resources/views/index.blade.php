@extends('brewmethod::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('brewmethod.name') !!}</p>
@endsection
