<!DOCTYPE html>
<html lang="en" class="h-full">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'FiveM MCP Server Documentation')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="h-full bg-gray-50 dark:bg-gray-900">
        <div class="min-h-full">
            <!-- Navigation -->
            @include('layouts.partials.navigation')

            <!-- Main Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </body>

</html>
