# prp-bridge Events

@foreach($eventsByCategory as $category => $events)
## {{ $category }}

@foreach($events as $event)
- **{{ $event['name'] }}** ({{ ucfirst($event['side']) }}) - {{ $event['description'] }}
@endforeach

@endforeach
