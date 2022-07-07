@extends('layout.master')

@section('body-class', 'page-bk-stripe')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Library</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 4])</div>
        </div>

        {!! $payment !!}
    </div>
@endsection
