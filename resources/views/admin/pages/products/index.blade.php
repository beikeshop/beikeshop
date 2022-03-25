@extends('admin.layouts.master')

@section('content')
  <div class="card">
    <div class="card-body">
      <x-filter :url="route('admin.products.index')" />

      <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create</a>
      <table class="table">
        @foreach ($products as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->description->name ?? '--' }}</td>
            <td>{{ $product->variables ? '多规格' : '单规格' }}</td>
            <td>
              <a href="{{ route('admin.products.edit', $product) }}">编辑</a>
            </td>
          </tr>
        @endforeach
      </table>
    </div>
  </div>

@endsection

