# prp-bridge Export: {{ $export['name'] }}

**Category:** {{ $export['category'] }}
**Side:** {{ ucfirst($export['side']) }}

### Description
{{ $export['description'] }}

### Parameters
@if(count($export['parameters']) > 0)
@foreach($export['parameters'] as $param)
- **{{ $param['name'] }}** ({{ $param['type'] }}): {{ $param['description'] }}
@endforeach
@else
This export takes no parameters.
@endif

### Returns
**Type:** {{ $export['returns']['type'] }}
{{ $export['returns']['description'] }}

### Usage Example
```lua
{{ $export['lua_example'] }}
```

### Documentation
[View at docs.prodigyrp.net](https://docs.prodigyrp.net/prp-bridge/exports.html)
