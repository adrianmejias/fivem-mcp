FiveM Documentation Results for: {{ $query }}

@foreach($results as $result)
📚 {{ $result['title'] }}
   URL: {{ $result['url'] }}
   {{ $result['description'] }}

@endforeach
