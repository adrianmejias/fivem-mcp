Native Function: {{ $native['name'] }}
==================================================

Namespace: {{ $native['namespace'] }}

Description:
{{ $native['description'] }}

@if(!empty($native['parameters']))
Parameters:
@foreach($native['parameters'] as $param)
  • {{ $param['name'] }} ({{ $param['type'] }}): {{ $param['description'] }}
@endforeach

@endif
Returns: {{ $native['returns']['type'] }}
@if($native['returns']['description'] !== 'No return value')
  {{ $native['returns']['description'] }}
@endif

@php
$exampleKey = $language === 'js' ? 'js_example' : 'lua_example';
$langName = $language === 'js' ? 'JavaScript' : 'Lua';
@endphp
@if(isset($native[$exampleKey]))
{{ $langName }} Example:
```{{ $language }}
{{ $native[$exampleKey] }}
```

@endif
Documentation: https://docs.fivem.net/natives/?_{{ $native['name'] }}
