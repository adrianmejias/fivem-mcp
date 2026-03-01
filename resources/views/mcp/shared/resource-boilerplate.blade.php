FiveM Resource Boilerplate: {{ $resourceName }}
==================================================

Generated files:

@foreach($files as $filename => $content)
📄 {{ $filename }}
--------------------------------------------------
{{ $content }}

@endforeach
