## {{ $config['category'] }} Configuration

**Location:** `{{ $config['location'] }}`

{{ $config['description'] }}

### Configuration Options
| Option | Type | Description |
|--------|------|-------------|
@foreach($config['config_options'] as $option)
| `{{ $option['name'] }}` | {{ $option['type'] }} | {{ $option['description'] }} |
@endforeach

@if($language === 'lua')
### Lua Example
```lua
{{ $config['lua_example'] }}
```
@else
### JavaScript Example
```javascript
{{ $config['js_example'] }}
```
@endif

### Learn More
- [QBCore Configuration Guide](https://docs.qbcore.org/qbcore-documentation/qb-core/configuration)
- [QBCore Repository](https://github.com/qbcore-framework/qb-core)
