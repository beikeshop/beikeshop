@extends('admin::layouts.master')
@section('title', '税率设置')

@section('content')
    @foreach($tax_rates as $tax_rate)
        @dump($tax_rate->name)
    @endforeach
@endsection
