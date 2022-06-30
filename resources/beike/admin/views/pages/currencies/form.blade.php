@extends('admin::layouts.master')

@section('title', '货币管理')

@section('content')
  <div id="currency-app-form" class="card">
    <div class="card-body">
        <form action="{{ admin_route('currencies.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label class="form-label" id="name">名称</label>
                <input type="input" name="name" value="{{ old('name') }}" class="form-control" placeholder="名称">
                @error('name')
                <x-admin::form.error :message="$message" />
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" id="code">编码</label>
                <input type="input" name="code" value="{{ old('code') }}" class="form-control" placeholder="编码">
                @error('code')
                <x-admin::form.error :message="$message" />
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" id="symbol_left">左符号</label>
                <input type="input" name="symbol_left" value="{{ old('symbol_left') }}" class="form-control" placeholder="左符号">
                @error('symbol_left')
                <x-admin::form.error :message="$message" />
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" id="symbol_right">右符号</label>
                <input type="input" name="symbol_right" value="{{ old('symbol_right') }}" class="form-control" placeholder="右符号">
                @error('symbol_right')
                <x-admin::form.error :message="$message" />
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" id="decimal_place">小数位数</label>
                <input type="input" name="decimal_place" value="{{ old('decimal_place') }}" class="form-control" placeholder="小数位数">
                @error('decimal_place')
                <x-admin::form.error :message="$message" />
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" id="value">汇率值</label>
                <input type="input" name="value" value="{{ old('value') }}" class="form-control" placeholder="汇率值">
                @error('value')
                <x-admin::form.error :message="$message" />
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" id="status">状态</label>
                <input type="input" name="status" class="form-control" placeholder="状态">
                @error('status')
                <x-admin::form.error :message="$message" />
                @enderror
            </div>

            @if (session('error'))
                <div class="alert alert-success">
                    {{ session('error') }}
                </div>
            @endif

            <button type="submit" class="btn btn-primary mb-4">确定</button>
        </form>
    </div>
  </div>
@endsection
