# QBCore Events

@foreach($eventsByCategory as $category => $events)
## {{ ucfirst($category) }} Events

@foreach($events as $event)
- **{{ $event['name'] }}** ({{ ucfirst($event['side']) }}) - {{ $event['description'] }}
@endforeach

@endforeach
