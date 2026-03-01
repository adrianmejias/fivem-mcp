## {{ $resource['title'] }} (`{{ $resource['name'] }}`)

**Category:** {!! $resource['category'] !!}

{{ $resource['description'] }}

### Overview

{{ $resource['description'] }}

@if(isset($resource['features']) && !empty($resource['features']))
#### Key Features
@foreach($resource['features'] as $feature)
- {{ $feature }}
@endforeach
@endif

@if(isset($resource['commands']) && !empty($resource['commands']))
### Commands

@foreach($resource['commands'] as $command => $description)
#### `{{ $command }}`
{{ $description }}

@endforeach
@endif

@if(isset($resource['events']) && !empty($resource['events']))
### Events

@foreach($resource['events'] as $event => $description)
#### `{{ $event }}`
{{ $description }}

@endforeach
@endif

@if(isset($resource['callbacks']) && !empty($resource['callbacks']))
### Callbacks

@foreach($resource['callbacks'] as $callback => $description)
#### `{{ $callback }}`
{{ $description }}

@endforeach
@endif

@if(isset($resource['items']) && !empty($resource['items']))
### Items

| Item | Description |
|------|-------------|
@foreach($resource['items'] as $item => $description)
| `{{ $item }}` | {{ $description }} |
@endforeach
@endif

### Documentation
- [Resource Documentation]({{ $resource['url'] }})
- [QBCore Framework](https://docs.qbcore.org)
- [GitHub Repository](https://github.com/qbcore-framework)

**Learn More:**
To get detailed configuration options and advanced usage, visit the [full documentation]({{ $resource['url'] }}) for this resource.
