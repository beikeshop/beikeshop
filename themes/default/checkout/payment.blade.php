@extends('layout.master')

@section('body-class', 'page-bk-stripe')

@section('content')
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 4])</div>
        </div>

        {!! $payment !!}
    </div>
@endsection
