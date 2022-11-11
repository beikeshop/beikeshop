# 数据字典
@foreach ($tables as $index => $table)

## {{$index+1}}. `{{ $table['name'] }}`

#### 描述: {{ $table['comment'] }}



#### 字段:

| 字段 | 类型 | 属性 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
@foreach ($table['columns'] as $column)
| `{{ $column->name }}` | {{ $column->type }} | {{ $column->attributes->implode(', ') }} | {{ $column->default }} | {{ $column->description }} |
@endforeach
@if (count($table['indices']))

#### 索引:

| 名字 | 字段 | 类型 | 描述 |
| --- | --- | --- | --- |
@foreach($table['indices'] as $indices)
| `{{ $indices->name }}` | {{ $indices->columns->map(function ($column) { return "`{$column}`"; })->implode(', ') }} | {{ $indices->type }} | {{ $indices->description }} |
@endforeach
@endif
@endforeach
