@extends('layouts.app')

@section('title', 'Full Documentation - FiveM MCP Server')

@section('content')
        <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-6">FiveM Development MCP Server</h1>

                <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                    A comprehensive Model Context Protocol (MCP) server for FiveM (GTA5) development, built with Laravel MCP.
                    This server provides AI assistants with powerful tools to help you develop FiveM resources more efficiently.
                </p>

                <!-- Installation -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Installation & Connection</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">Choose how you want to connect to the FiveM MCP server:</p>

                    <!-- Tab Buttons -->
                    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <button data-tab="remote"
                                class="transition-all duration-200 ease-in-out active border-b-2 border-gta-orange py-4 px-1 text-center text-sm font-medium text-gta-orange">
                                <span class="flex items-center">
                                    <span class="mr-2">🌐</span>
                                    Remote Server
                                    <span
                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 border border-gta-orange text-gta-orange">
                                        Recommended
                                    </span>
                                </span>
                            </button>
                            <button data-tab="local"
                                class="transition-all duration-200 ease-in-out border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600">
                                <span class="flex items-center">
                                    <span class="mr-2">💻</span>
                                    Local Installation
                                </span>
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content Container -->
                    <div class="tab-content-container">
                        <!-- Remote Server Option -->
                        <div data-tab-content="remote" class="animate-fade-in active">
                            <div class="bg-gray-100 dark:bg-gray-700/30 border-l-4 border-gta-orange p-6">
                                <p class="text-gray-700 dark:text-gray-300 mb-4">
                                    <strong>Easy setup!</strong> Connect to a hosted MCP server. This is the easiest option and
                                    works from anywhere without any local setup.
                                </p>

                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Claude Desktop Configuration:</h4>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">Edit your configuration file:</p>
                                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-3 ml-4">
                                    <li><strong>macOS:</strong> <code
                                            class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 rounded text-sm">~/Library/Application Support/Claude/claude_desktop_config.json</code>
                                    </li>
                                    <li><strong>Windows:</strong> <code
                                            class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 rounded text-sm">%APPDATA%\Claude\claude_desktop_config.json</code>
                                    </li>
                                </ul>

                                <p class="text-gray-700 dark:text-gray-300 mb-3">Add this configuration (replace with your
                                    actual
                                    server URL):</p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>@verbatim{
        "mcpServers": {
            "fivem": {
                "url": "https://your-domain.com/fivem"
            }
        }
    }@endverbatim</code></pre>
                            </div>

                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">VSCode Configuration (Cline
                                    Extension):
                                </h4>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">If using VSCode with the Cline extension, edit
                                    your
                                    settings:</p>
                                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-3 ml-4">
                                    <li>Open VSCode Settings (JSON)</li>
                                    <li>Add to your global or workspace <code
                                            class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 rounded text-sm">settings.json</code>
                                    </li>
                                </ul>

                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>@verbatim{
        "mcp": {
            "servers": {
                "fivem": {
                    "url": "https://your-domain.com/fivem"
                }
            }
        }
    }@endverbatim</code></pre>
                            </div>
                        </div>

                        <!-- Local Installation Option -->
                        <div data-tab-content="local" class="animate-fade-in hidden">
                            <div class="bg-gray-50 dark:bg-gray-700/20 border-l-4 border-gray-400 dark:border-gray-500 p-6">
                                <p class="text-gray-700 dark:text-gray-300 mb-4">
                                    <strong>Advanced setup.</strong> Run the MCP server on your own machine. Requires PHP and
                                    Laravel setup.
                                </p>

                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Claude Desktop Configuration:</h4>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">Edit your configuration file and add:</p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>@verbatim{
        "mcpServers": {
            "fivem": {
                "command": "/path/to/php",
                "args": [
                "/path/to/fivem-mcp/artisan",
                "mcp:start",
                "fivem"
                ]
            }
        }
    }@endverbatim</code></pre>

                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Command Line Usage:</h4>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">For any MCP-compatible client:</p>
                                <pre
                                    class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>php artisan mcp:start fivem</code></pre>

                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2 mt-6">VSCode Configuration (Cline
                                    Extension):</h4>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">If using VSCode with the Cline extension, add
                                    to
                                    <code
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 rounded text-sm">settings.json</code>:
                                </p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>@verbatim{
        "mcp": {
            "servers": {
                "fivem": {
                    "command": "php",
                    "args": ["artisan", "mcp:start", "fivem"],
                    "cwd": "/path/to/fivem-mcp"
                }
            }
        }
    }@endverbatim</code></pre>

                                <p class="text-gray-700 dark:text-gray-300 mb-3 mt-4"><strong>Or use the full PHP path:</strong>
                                </p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>@verbatim{
        "mcp": {
            "servers": {
                "fivem": {
                    "command": "/path/to/php",
                    "args": ["/path/to/fivem-mcp/artisan", "mcp:start", "fivem"]
                }
            }
        }
    }@endverbatim</code></pre>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mt-6">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>💡 Tip:</strong> After configuring Claude Desktop, completely quit and restart the
                            application
                            for
                            changes to take effect.
                        </p>
                    </div>
                </section>

                <!-- Available Tools -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Available Tools</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">
                        The FiveM MCP Server provides {{ count($tools) }} tools organized by category.
                        These tools allow AI assistants to search documentation, generate code, and lookup framework functions.
                    </p>

                    @php
    $groupedTools = collect($tools)->groupBy('category');
                    @endphp

                    @foreach ($groupedTools as $category => $categoryTools)
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <span class="inline-block w-1 h-6 bg-gta-orange mr-3"></span>
                                {{ $category }} Tools
                                <span
                                    class="ml-3 text-sm font-normal text-gray-500 dark:text-gray-400">({{ count($categoryTools) }})</span>
                            </h3>

                            @foreach ($categoryTools as $index => $tool)
                                <div class="mb-6 border-l-4 border-gta-orange pl-6">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $tool['name'] }}</h4>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $tool['description'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </section>

                <!-- Available Resources -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Available Resources</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">
                        MCP Resources provide read-only reference documentation that AI assistants can access.
                        Currently available: {{ count($resources) }} resources.
                    </p>

                    @forelse ($resources as $index => $resource)
                        <div class="mb-6 border-l-4 border-green-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $index + 1 }}.
                                {{ $resource['name'] }}
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $resource['description'] }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 italic">No resources configured.</p>
                    @endforelse
                </section>

                <!-- Available Prompts -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Available Prompts</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">
                        MCP Prompts are pre-built prompt templates for common tasks.
                        Currently available: {{ count($prompts) }} prompts.
                    </p>

                    @forelse ($prompts as $index => $prompt)
                        <div class="mb-6 border-l-4 border-purple-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $index + 1 }}.
                                {{ $prompt['name'] }}
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $prompt['description'] }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 italic">No prompts configured.</p>
                    @endforelse
                </section>

                <!-- Testing -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Testing the Server</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">You can test the MCP server locally using the inspector:
                    </p>
                    <pre
                        class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>php artisan mcp:inspector fivem</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">This will open the MCP Inspector in your browser
                        where
                        you
                        can test all the tools interactively.</p>
                </section>

                <!-- Development -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Development</h2>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Adding New Tools</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">1. Create a new tool class in <code
                            class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 rounded text-sm">app/Mcp/Tools/</code>:
                    </p>

                    <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>@verbatim&lt;?php

    namespace App\Mcp\Tools;

    use Illuminate\Contracts\JsonSchema\JsonSchema;
    use Laravel\Mcp\Request;
    use Laravel\Mcp\Response;
    use Laravel\Mcp\Server\Attributes\Description;
    use Laravel\Mcp\Server\Tool;

    #[Description('Your tool description')]
    class YourTool extends Tool
    {
        public function handle(Request $request): Response
        {
            // Tool logic here
            return Response::text('Result');
        }

        public function schema(JsonSchema $schema): array
        {
            return [
                'param' => $schema->string()->required(),
            ];
        }
    }
    @endverbatim</code></pre>

                    <p class="text-gray-700 dark:text-gray-300 mb-4">2. Register the tool in <code
                            class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-1 rounded text-sm">app/Mcp/Servers/FiveMServer.php</code>:
                    </p>

                    <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>@verbatimprotected array $tools = [
        // ... existing tools
        \App\Mcp\Tools\YourTool::class,
    ];@endverbatim</code></pre>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 mt-6">Code Formatting</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Format code with Laravel Pint:</p>
                    <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>vendor/bin/pint</code></pre>
                </section>

                <!-- Frameworks & Languages -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Supported Frameworks</h2>
                    <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                        <li><strong>Standalone:</strong> Basic FiveM resources without framework dependencies</li>
                        <li><strong>ESX:</strong> ESX Framework (es_extended)</li>
                        <li><strong>QBCore:</strong> QBCore Framework (qb-core / qbx_core)</li>
                        <li><strong>Prodigy Studios (prp-bridge):</strong> Unified bridge supporting QBox, QB-Core, ESX, and ND Core with
                            events, exports, UniQueue, groups, allowlists, sell shops, cases, and ped interactions</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 mt-6">Scripting Languages</h2>
                        <p class="text-gray-700 dark:text-gray-300">Both Lua and JavaScript are fully supported across all tools and
                            generators.</p>
                        </section>

                        <!-- Resources -->
                        <section class="mb-12">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Resources</h2>
                            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                                <li><a href="https://docs.fivem.net/docs/" class="link-gta">FiveM
                                        Documentation</a></li>
                                <li><a href="https://docs.fivem.net/natives/" class="link-gta">FiveM
                                        Natives Reference</a></li>
                                <li><a href="https://coxdocs.dev/" class="link-gta">COX
                                        MySQL
                                        Documentation</a> - Required
                                    for database operations</li>
                                <li><a href="https://docs.qbcore.org/" class="link-gta">QBCore
                                        Documentation</a></li>
                                <li><a href="https://docs.prodigyrp.net/" class="link-gta">Prodigy
                                        Studios Documentation</a></li>
                        <li><a href="https://laravel.com/docs/mcp" class="link-gta">Laravel
                                MCP Documentation</a></li>
                        <li><a href="https://modelcontextprotocol.io/" class="link-gta">Model
                                Context Protocol</a></li>
                    </ul>
                </section>

                <!-- Contributing -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Contributing</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Contributions are welcome! Areas for expansion:</p>
                    <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                        <li>More native functions in the database</li>
                        <li>Additional framework support (vRP, etc.)</li>
                        <li>More event types</li>
                        <li>Advanced code generators</li>
                        <li>Database query builders</li>
                    </ul>
                </section>

                <!-- License -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">License</h2>
                    <p class="text-gray-700 dark:text-gray-300">MIT License</p>
                </section>
            </div>
        </div>
@endsection
