@extends('admin::layouts.master')

@section('title', '后台管理')

@section('content')
    <div class="card mb-3">
        <div class="card-header">产品统计</div>
        <div class="card-body">
            <div><ul>
                    <li>产品总数</li>
                    <li>查看次数排序</li>
                    <li>卖出总额排序</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">客户统计</div>
        <div class="card-body">
            <div><ul>
                    <li>新注册</li>
                    <li>最近访问</li>
                    <li>活跃用户</li>
                    <li>近期下单</li>
                    <li>订单总额排序</li>
                    <li>订单总数排序</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">订单统计</div>
        <div class="card-body">
            <div><ul>
                    <li>新订单</li>
                    <li>已付款</li>
                    <li>已发货</li>
                    <li>已完成</li>
                </ul>
            </div>
        </div>
    </div>

@endsection
