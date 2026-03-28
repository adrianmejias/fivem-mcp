# prp-bridge Exports

@foreach($exportsByCategory as $category => $exports)
## {{ $category }}

@foreach($exports as $export)
- **{{ $export['name'] }}** ({{ ucfirst($export['side']) }}) - {{ $export['description'] }}
@endforeach

@endforeach
