# COX MySQL Events

@foreach($eventsByType as $type => $events)
## {{ ucfirst($type) }} Events

@foreach($events as $event)
- **{{ $event['name'] }}** - {{ $event['description'] }}
@endforeach

@endforeach
