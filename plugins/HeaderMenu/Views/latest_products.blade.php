@extends('layout.master')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Library</li>
            </ol>
        </nav>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-6 col-md-3">@include('shared.product')</div>
            @endforeach
        </div>

        {{ $products->links('shared/pagination/bootstrap-4') }}

    </div>

@endsection
