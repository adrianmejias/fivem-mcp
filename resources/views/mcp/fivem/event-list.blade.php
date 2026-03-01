FiveM Events Reference
==================================================

@foreach($eventsByType as $type => $events)
{{ strtoupper($type) }} EVENTS:
@foreach($events as $event)
  • {{ $event['name'] }} - {{ $event['description'] }}
@endforeach

@endforeach
