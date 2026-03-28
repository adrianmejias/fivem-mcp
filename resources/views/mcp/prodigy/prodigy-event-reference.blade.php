# prp-bridge Event: {{ $event['name'] }}

**Side:** {{ ucfirst($event['side']) }}

### Description
{{ $event['description'] }}

### Parameters
@if(count($event['parameters']) > 0)
@foreach($event['parameters'] as $name => $description)
- `{{ $name }}`: {{ $description }}
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

### Documentation
[View at docs.prodigyrp.net](https://docs.prodigyrp.net/prp-bridge/events.html)
