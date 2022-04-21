@extends('demo2::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from plugin: {!! config('demo2.name') !!}
    </p>
@endsection
