Event: {{ $event['name'] }}
==================================================

Type: {{ $event['type'] }}
Side: {{ $event['side'] }}

Description:
{{ $event['description'] }}

@if(!empty($event['parameters']))
Parameters:
@foreach($event['parameters'] as $param)
  • {{ $param['name'] }} ({{ $param['type'] }}): {{ $param['description'] }}
@endforeach

@endif
@php
$exampleKey = $language === 'js' ? 'js_example' : 'lua_example';
$langName = $language === 'js' ? 'JavaScript' : 'Lua';
@endphp
@if(isset($event[$exampleKey]))
{{ $langName }} Example:
```{{ $language }}
{{ $event[$exampleKey] }}
```

@endif
@if(isset($event['documentation']))
Documentation: {{ $event['documentation'] }}
@endif
