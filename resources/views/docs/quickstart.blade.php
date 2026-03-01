@extends('docs.layout')

@section('title', 'Quick Start Guide - FiveM MCP Server')

@section('content')
<style>
.tab-button {
    transition: all 0.2s ease-in-out;
}

.tab-content {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">FiveM MCP Quick Start Guide</h1>

        <!-- What is this -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">What is this?</h2>
            <p class="text-gray-700 mb-4">
                This MCP server gives AI assistants (like Claude) access to FiveM development tools including:
            </p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Documentation search</li>
                <li>Native function lookups</li>
                <li>Manifest generation</li>
                <li>Event references</li>
                <li>Resource boilerplate generation</li>
            </ul>
        </section>

        <!-- Choose Setup Type -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Choose Your Setup</h2>

            <!-- Tab Buttons -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button
                        data-tab="remote"
                        class="tab-button active border-b-2 border-indigo-500 py-4 px-1 text-center text-sm font-medium text-indigo-600"
                    >
                        <span class="flex items-center">
                            <span class="mr-2">🌐</span>
                            Remote Server
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                Recommended
                            </span>
                        </span>
                    </button>
                    <button
                        data-tab="local"
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                    >
                        <span class="flex items-center">
                            <span class="mr-2">💻</span>
                            Local Installation
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Tab Content Container -->
            <div class="tab-content-container">
                <!-- Remote Setup Tab -->
                <div data-tab-content="remote" class="tab-content active">
                    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-6">
                        <p class="text-sm text-indigo-800">
                            <strong>Easy setup!</strong> Connect to a hosted MCP server. Works from anywhere without any local installation.
                        </p>
                    </div>

                    <p class="text-gray-700 mb-6">
                        If someone is hosting the FiveM MCP server for you, follow these steps to connect:
                    </p>

                    <!-- Step 1 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Get the Server URL</h3>
                        <p class="text-gray-700 mb-3">
                            Ask your server administrator for the MCP server URL. It will look like:
                        </p>
                        <pre class="bg-gray-100 rounded p-3 text-sm"><code>https://your-domain.com/mcp</code></pre>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Configure Claude Desktop</h3>
                        <p class="text-gray-700 mb-3">Edit your Claude Desktop configuration file:</p>
                        <ul class="list-disc list-inside text-gray-700 mb-3 ml-4">
                            <li><strong>macOS:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-sm">~/Library/Application Support/Claude/claude_desktop_config.json</code></li>
                            <li><strong>Windows:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-sm">%APPDATA%\Claude\claude_desktop_config.json</code></li>
                        </ul>

                        <p class="text-gray-700 mb-3">Add this configuration (replace the URL with your actual server URL):</p>
                        <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>{
  "mcpServers": {
    "fivem": {
      "url": "https://your-domain.com/mcp"
    }
  }
}</code></pre>

                        <p class="text-gray-700 mb-3 mt-6"><strong>For VSCode (Cline Extension):</strong></p>
                        <p class="text-gray-700 mb-3">Edit your VSCode <code class="bg-gray-100 px-2 py-1 rounded text-sm">settings.json</code>:</p>
                        <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>{
  "mcp": {
    "servers": {
      "fivem": {
        "url": "https://your-domain.com/mcp"
      }
    }
  }
}</code></pre>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Restart Claude Desktop</h3>
                        <p class="text-gray-700">
                            Completely quit and restart Claude Desktop for changes to take effect.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">4. Verify Connection</h3>
                        <p class="text-gray-700">
                            In Claude Desktop, you should see a small 🔌 icon or indication that the FiveM MCP server is connected.
                        </p>
                    </div>

                    <div class="bg-green-50 border-l-4 border-green-500 p-4">
                        <p class="text-sm text-green-800">
                            <strong>That's it!</strong> You're now connected to the remote MCP server and can start using FiveM development tools with Claude.
                        </p>
                    </div>
                </div>

                <!-- Local Setup Tab -->
                <div data-tab-content="local" class="tab-content hidden">
                    <div class="bg-gray-50 border-l-4 border-gray-400 p-4 mb-6">
                        <p class="text-sm text-gray-700">
                            <strong>Advanced setup.</strong> Run the MCP server on your own machine. Requires PHP and Laravel installation.
                        </p>
                    </div>

                    <p class="text-gray-700 mb-6">
                        If you want to run the MCP server on your own machine:
                    </p>

                    <!-- Step 1 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Test the Server</h3>
                        <p class="text-gray-700 mb-3">First, verify the server works:</p>
                        <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>cd /path/to/fivem-mcp
php artisan mcp:inspector fivem</code></pre>
                        <p class="text-gray-600 text-sm mt-2">This opens a web inspector where you can test all tools.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Configure Claude Desktop</h3>
                        <p class="text-gray-700 mb-3">Edit your Claude Desktop configuration file:</p>
                        <ul class="list-disc list-inside text-gray-700 mb-3 ml-4">
                            <li><strong>macOS:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-sm">~/Library/Application Support/Claude/claude_desktop_config.json</code></li>
                            <li><strong>Windows:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-sm">%APPDATA%\Claude\claude_desktop_config.json</code></li>
                        </ul>

                        <p class="text-gray-700 mb-3">Add this configuration:</p>
                        <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>{
  "mcpServers": {
    "fivem": {
      "command": "php",
      "args": [
        "artisan",
        "mcp:start",
        "fivem"
      ],
      "cwd": "/path/to/fivem-mcp"
    }
  }
}</code></pre>

                        <p class="text-gray-700 mb-3"><strong>Or use the full PHP path (e.g., for Herd):</strong></p>
                        <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>{
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
}</code></pre>

                        <p class="text-gray-700 mb-3 mt-6"><strong>For VSCode (Cline Extension):</strong></p>
                        <p class="text-gray-700 mb-3">Edit your VSCode <code class="bg-gray-100 px-2 py-1 rounded text-sm">settings.json</code>:</p>
                        <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>{
  "mcp": {
    "servers": {
      "fivem": {
        "command": "php",
        "args": ["artisan", "mcp:start", "fivem"],
        "cwd": "/path/to/fivem-mcp"
      }
    }
  }
}</code></pre>

                        <p class="text-gray-700 mb-3"><strong>Or with full paths:</strong></p>
                        <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>{
  "mcp": {
    "servers": {
      "fivem": {
        "command": "/path/to/php",
        "args": ["/path/to/fivem-mcp/artisan", "mcp:start", "fivem"]
      }
    }
  }
}</code></pre>
                    </div>

                    <!-- Step 3 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Restart Claude Desktop</h3>
                        <p class="text-gray-700">
                            Completely quit and restart Claude Desktop for changes to take effect.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">4. Verify Connection</h3>
                        <p class="text-gray-700">
                            In Claude Desktop, you should see a small 🔌 icon or indication that the FiveM MCP server is connected.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Examples -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Quick Examples</h2>
            <p class="text-gray-700 mb-4">Once connected, try these prompts in Claude:</p>

            <div class="space-y-4">
                <div class="border-l-4 border-indigo-500 pl-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Example 1: Search Documentation</h4>
                    <pre class="bg-gray-100 rounded p-3 text-sm"><code>Search FiveM docs for "state bags"</code></pre>
                </div>

                <div class="border-l-4 border-indigo-500 pl-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Example 2: Look Up a Native</h4>
                    <pre class="bg-gray-100 rounded p-3 text-sm"><code>What does the GetPlayerPed native function do? Show me an example.</code></pre>
                </div>

                <div class="border-l-4 border-indigo-500 pl-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Example 3: Generate a Manifest</h4>
                    <pre class="bg-gray-100 rounded p-3 text-sm"><code>Generate a fxmanifest.lua for my resource called "vehicle-shop" using ESX framework</code></pre>
                </div>

                <div class="border-l-4 border-indigo-500 pl-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Example 4: List Events</h4>
                    <pre class="bg-gray-100 rounded p-3 text-sm"><code>Show me all FiveM core server events</code></pre>
                </div>

                <div class="border-l-4 border-indigo-500 pl-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Example 5: Create Boilerplate</h4>
                    <pre class="bg-gray-100 rounded p-3 text-sm"><code>Create a complete resource structure for a QBCore script called "bank-heist" with NUI</code></pre>
                </div>
            </div>
        </section>

        <!-- Troubleshooting -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Troubleshooting</h2>

            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Server Not Connecting</h3>
                <ol class="list-decimal list-inside text-gray-700 space-y-3 ml-4">
                    <li>
                        <strong>Check PHP Path:</strong> Make sure the PHP path in your config is correct
                        <pre class="bg-gray-900 text-gray-100 rounded p-3 mt-2 overflow-x-auto"><code>which php
# or for Herd:
ls -la "~/Library/Application Support/Herd/bin/"</code></pre>
                    </li>
                    <li>
                        <strong>Test Manually:</strong> Run the server command directly
                        <pre class="bg-gray-900 text-gray-100 rounded p-3 mt-2 overflow-x-auto"><code>cd /path/to/fivem-mcp
php artisan mcp:start fivem</code></pre>
                    </li>
                    <li>
                        <strong>Check Logs:</strong> Look at Claude Desktop logs
                        <ul class="list-disc list-inside ml-6 mt-2">
                            <li>macOS: <code class="bg-gray-100 px-2 py-1 rounded text-sm">~/Library/Logs/Claude/</code></li>
                            <li>Windows: Check Event Viewer or app logs</li>
                        </ul>
                    </li>
                </ol>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Permission Issues</h3>
                <p class="text-gray-700 mb-3">If you get permission errors:</p>
                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>cd /path/to/fivem-mcp
chmod +x artisan</code></pre>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Path Issues</h3>
                <p class="text-gray-700">
                    Make sure to use <strong>absolute paths</strong> in your config, not relative paths.
                </p>
            </div>
        </section>

        <!-- Next Steps -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Next Steps</h2>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li><a href="{{ route('docs.documentation') }}" class="text-indigo-600 hover:text-indigo-500">Read the full documentation</a></li>
                <li>Explore all 5 tools in the MCP Inspector</li>
                <li>Try creating a complete FiveM resource with AI assistance</li>
                <li>Contribute new tools or improve existing ones</li>
            </ul>
        </section>

        <!-- Support -->
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Support</h2>
            <p class="text-gray-700 mb-3">For issues:</p>
            <ol class="list-decimal list-inside text-gray-700 space-y-2 ml-4">
                <li>Check <a href="https://laravel.com/docs/mcp" class="text-indigo-600 hover:text-indigo-500">Laravel MCP Documentation</a></li>
                <li>Check <a href="https://docs.fivem.net/" class="text-indigo-600 hover:text-indigo-500">FiveM Documentation</a></li>
                <li>Review server logs</li>
                <li>Open a GitHub issue</li>
            </ol>
            <p class="text-gray-700 mt-6 text-lg font-semibold">Happy coding! 🎮</p>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active class to clicked button
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('active', 'border-indigo-500', 'text-indigo-600');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            // Show target tab content
            const targetContent = document.querySelector(`[data-tab-content="${targetTab}"]`);
            if (targetContent) {
                targetContent.classList.remove('hidden');
                targetContent.classList.add('active');
            }
        });
    });
});
</script>

@endsection
