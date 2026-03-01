## {{ $info['title'] }}

{{ $info['description'] }}

@if(isset($info['properties']))
### Properties
| Property | Type | Description |
|----------|------|-------------|
@foreach($info['properties'] as $prop)
| `{{ $prop['name'] }}` | {{ $prop['type'] }} | {{ $prop['description'] }} |
@endforeach
@endif

@if(isset($info['methods']))
### Available Methods
| Method | Description |
|--------|-------------|
@foreach($info['methods'] as $method)
| `{{ $method['name'] }}` | {{ $method['description'] }} |
@endforeach
@endif

@if(isset($info['events']))
### Events
| Event | Description |
|-------|-------------|
@foreach($info['events'] as $event)
| `{{ $event['name'] }}` | {{ $event['description'] }} |
@endforeach
@endif

@if($language === 'lua')
### Lua Example
```lua
{{ $info['lua_example'] }}
```
@else
### JavaScript Example
```javascript
{{ $info['js_example'] }}
```
@endif

### Learn More
- [QBCore Player Data Documentation](https://docs.qbcore.org/qbcore-documentation/qb-core/player-data)
- [QBCore Repository](https://github.com/qbcore-framework/qb-core)
