@extends('layout.master')

@section('body-class', 'page-bk-stripe')

@section('content')
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 4])</div>
        </div>


        <div class="col-12 col-md-4 right-column">
            <div class="card total-wrap">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">订单结账</h5>
                    <span class="rounded-circle bg-primary"></span>
                </div>
                <div class="card-body">
                    <ul class="totals">
                        <li><span>订单号</span><span>@{{ source.order.number }}</span></li>
                        <li><span>运费</span><span>15</span></li>
                        <li><span>应付总金额</span><span v-text="source.order.total"></span></li>
                    </ul>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn btn-primary" type="button" @click="checkedBtnCheckoutConfirm('form')">支付</button>
                    </div>
                </div>
            </div>
        </div>

        {!! $payment !!}
    </div>
@endsection
