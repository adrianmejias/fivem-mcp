# COX Event Reference

## {{ $event['name'] }}

**Type:** {{ $event['type'] }}

### Description
{{ $event['description'] }}

### Parameters
@if(count($event['parameters']) > 0)
@foreach($event['parameters'] as $param)
- **{{ $param['name'] }}** ({{ $param['type'] }}): {{ $param['description'] }}
@endforeach
@else
This event takes no parameters.
@endif

### Code Examples

**Lua:**
```lua
{{ $event['lua_example'] }}
```

**JavaScript:**
```javascript
{{ $event['js_example'] }}
```
