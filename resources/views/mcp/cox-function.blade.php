# COX Function: {{ $function['name'] }}

**Namespace:** {{ $function['namespace'] }}
**Side:** {{ ucfirst($function['side']) }}

### Description
{{ $function['description'] }}

### Parameters
@if(count($function['parameters']) > 0)
@foreach($function['parameters'] as $param)
- **{{ $param['name'] }}** ({{ $param['type'] }}): {{ $param['description'] }}
@endforeach
@else
This function takes no parameters.
@endif

### Returns
**Type:** {{ $function['returns']['type'] }}
{{ $function['returns']['description'] }}

### Code Examples

@if($language === 'js')
**JavaScript:**
```javascript
{{ $function['js_example'] }}
```
@else
**Lua:**
```lua
{{ $function['lua_example'] }}
```
@endif

### Alternative Example

@if($language === 'js')
**Lua (alternative):**
```lua
{{ $function['lua_example'] }}
```
@else
**JavaScript (alternative):**
```javascript
{{ $function['js_example'] }}
```
@endif
