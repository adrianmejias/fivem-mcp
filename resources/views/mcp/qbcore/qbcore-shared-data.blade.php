## {{ $data['type'] }} - QBCore Shared Data

**Location:** `{{ $data['location'] }}`

{{ $data['description'] }}

### Properties
@if(isset($data['properties']))
| Property | Type | Description |
|----------|------|-------------|
@foreach($data['properties'] as $prop)
| `{{ $prop['name'] }}` | {{ $prop['type'] }} | {{ $prop['description'] }} |
@endforeach
@endif

@if(isset($data['methods']))
### Available Methods
@foreach($data['methods'] as $method)
- **{{ $method['name'] }}** - {{ $method['description'] }}
@endforeach
@endif

### Lua Example
```lua
{{ $data['lua_example'] }}
```

### JavaScript Example
```javascript
{{ $data['js_example'] }}
```

### Learn More
- [QBCore Shared Documentation](https://docs.qbcore.org/qbcore-documentation/qb-core/shared)
- [QBCore Repository](https://github.com/qbcore-framework/qb-core)
