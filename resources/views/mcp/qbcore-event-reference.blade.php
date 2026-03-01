# QBCore Event Reference

## {{ $event['name'] }}

**Category:** {{ $event['category'] }}
**Side:** {{ ucfirst($event['side']) }}

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

@if(isset($event['documentation']))
### Documentation
[View at docs.qbcore.org]({{ $event['documentation'] }})
@endif
