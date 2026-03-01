## QBCore Resources Directory

Complete list of all {{ count($resources) }} official QBCore resources. Use `GetQBCoreResourceReference` to get detailed information about specific resources.

@php
    $grouped = collect($resources)->groupBy('category');
@endphp

@foreach($grouped as $category => $categoryResources)
### {{ $category }}

| Resource | Description | Documentation |
|----------|-------------|-----------------|
@foreach($categoryResources as $resource)
| **{{ $resource['title'] }}** (`{{ $resource['name'] }}`) | {{ $resource['description'] }} | [View Docs]({{ $resource['url'] }}) |
@endforeach

@endforeach

### Quick Links
- [QBCore Framework GitHub](https://github.com/qbcore-framework)
- [QBCore Documentation](https://docs.qbcore.org)
- [QBCore Discord Community](https://discord.gg/qbcore)

**Total Resources:** {{ count($resources) }}
