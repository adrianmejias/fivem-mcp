@extends('docs.layout')

@section('title', 'Full Documentation - FiveM MCP Server')

@section('content')
        <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-6">FiveM Development MCP Server</h1>

                <p class="text-lg text-gray-700 mb-8">
                    A comprehensive Model Context Protocol (MCP) server for FiveM (GTA5) development, built with Laravel MCP.
                    This server provides AI assistants with powerful tools to help you develop FiveM resources more efficiently.
                </p>

                <!-- Installation -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Installation & Connection</h2>
                    <p class="text-gray-700 mb-6">Choose how you want to connect to the FiveM MCP server:</p>

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
                        <!-- Remote Server Option -->
                        <div data-tab-content="remote" class="tab-content active">
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6">
                                <p class="text-gray-700 mb-4">
                                    <strong>Easy setup!</strong> Connect to a hosted MCP server. This is the easiest option and works from anywhere without any local setup.
                                </p>

                                <h4 class="font-semibold text-gray-900 mb-2">Claude Desktop Configuration:</h4>
                                <p class="text-gray-700 mb-3">Edit your configuration file:</p>
                                <ul class="list-disc list-inside text-gray-700 mb-3 ml-4">
                                    <li><strong>macOS:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-sm">~/Library/Application Support/Claude/claude_desktop_config.json</code></li>
                                    <li><strong>Windows:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-sm">%APPDATA%\Claude\claude_desktop_config.json</code></li>
                                </ul>

                                <p class="text-gray-700 mb-3">Add this configuration (replace with your actual server URL):</p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>{
          "mcpServers": {
            "fivem": {
              "url": "https://your-domain.com/fivem"
            }
          }
        }</code></pre>
                            </div>

                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2">VSCode Configuration (Cline Extension):</h4>
                                <p class="text-gray-700 mb-3">If using VSCode with the Cline extension, edit your settings:</p>
                                <ul class="list-disc list-inside text-gray-700 mb-3 ml-4">
                                    <li>Open VSCode Settings (JSON)</li>
                                    <li>Add to your global or workspace <code class="bg-gray-100 px-2 py-1 rounded text-sm">settings.json</code></li>
                                </ul>

                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>{
          "mcp": {
            "servers": {
              "fivem": {
                "url": "https://your-domain.com/fivem"
              }
            }
          }
        }</code></pre>
                            </div>
                        </div>

                        <!-- Local Installation Option -->
                        <div data-tab-content="local" class="tab-content hidden">
                            <div class="bg-gray-50 border-l-4 border-gray-400 p-6">
                                <p class="text-gray-700 mb-4">
                                    <strong>Advanced setup.</strong> Run the MCP server on your own machine. Requires PHP and Laravel setup.
                                </p>

                                <h4 class="font-semibold text-gray-900 mb-2">Claude Desktop Configuration:</h4>
                                <p class="text-gray-700 mb-3">Edit your configuration file and add:</p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>{
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

                                <h4 class="font-semibold text-gray-900 mb-2">Command Line Usage:</h4>
                                <p class="text-gray-700 mb-3">For any MCP-compatible client:</p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>php artisan mcp:start fivem</code></pre>

                                <h4 class="font-semibold text-gray-900 mb-2 mt-6">VSCode Configuration (Cline Extension):</h4>
                                <p class="text-gray-700 mb-3">If using VSCode with the Cline extension, add to <code class="bg-gray-100 px-2 py-1 rounded text-sm">settings.json</code>:</p>
                                <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>{
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

                                <p class="text-gray-700 mb-3 mt-4"><strong>Or use the full PHP path:</strong></p>
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
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6">
                        <p class="text-sm text-blue-800">
                            <strong>💡 Tip:</strong> After configuring Claude Desktop, completely quit and restart the application for changes to take effect.
                        </p>
                    </div>
                </section>

                <!-- Available Tools -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Tools</h2>

                    <!-- Tool 1: SearchFiveMDocs -->
                    <div class="mb-8 border-l-4 border-indigo-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. SearchFiveMDocs</h3>
                        <p class="text-gray-700 mb-4">Search FiveM, QBCore, and COX MySQL documentation for specific topics.</p>

                        <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
                        <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                            <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">query</code> (required): Search query (e.g., "events",
                                "natives", "mysql", "database")</li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">category</code> (optional): Filter by category (all,
                                scripting, natives, networking, resources, qbcore, coxdocs)</li>
                        </ul>

                        <h4 class="font-semibold text-gray-900 mb-2">Examples:</h4>
                        <pre class="bg-gray-100 rounded p-3 text-sm mb-2"><code>Search for QBCore callbacks and exports</code></pre>
                        <pre class="bg-gray-100 rounded p-3 text-sm"><code>How do I query the database with COX MySQL?</code></pre>
                        </div>

                        <!-- Tool 2: GetNativeFunction -->
                        <div class="mb-8 border-l-4 border-indigo-500 pl-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">2. GetNativeFunction</h3>
                            <p class="text-gray-700 mb-4">Look up GTA5/FiveM native functions.</p>

                            <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">function_name</code> (required): Native function name
                                    (e.g., "GetPlayerPed", "SetEntityCoords")</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">language</code> (optional): Code example language (lua
                                    or js)</li>
                            </ul>

                            <h4 class="font-semibold text-gray-900 mb-2">Common Native Examples:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li>Common natives: <code class="bg-gray-100 px-2 py-1 rounded text-sm">GetPlayerPed</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">SetEntityCoords</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">GetEntityCoords</code></li>
                                <li>CFX natives: <code class="bg-gray-100 px-2 py-1 rounded text-sm">RegisterNetEvent</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">TriggerServerEvent</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">TriggerClientEvent</code></li>
                                <li>ESX: <code class="bg-gray-100 px-2 py-1 rounded text-sm">ESX.GetPlayerFromId</code></li>
                                <li>QBCore: <code class="bg-gray-100 px-2 py-1 rounded text-sm">QBCore.Functions.GetPlayer</code></li>
                            </ul>
                        </div>

                        <!-- Tool 3: GenerateManifest -->
                        <div class="mb-8 border-l-4 border-indigo-500 pl-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">3. GenerateManifest</h3>
                            <p class="text-gray-700 mb-4">Generate a fxmanifest.lua file.</p>

                            <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">resource_name</code> (required): Resource name</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">author</code> (optional): Author name</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">description</code> (optional): Resource description</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">version</code> (optional): Version number (default:
                                    "1.0.0")</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">script_type</code> (optional): lua or js (default: lua)
                                </li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">include_client</code> (optional): Include client scripts
                                    (default: true)</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">include_server</code> (optional): Include server scripts
                                    (default: true)</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">framework</code> (optional): standalone, esx, or qbcore
                                    (default: standalone)</li>
                            </ul>

                            <h4 class="font-semibold text-gray-900 mb-2">Example:</h4>
                            <pre
                                class="bg-gray-100 rounded p-3 text-sm"><code>Generate a manifest for an ESX resource called "my-shop"</code></pre>
                        </div>

                        <!-- Tool 4: GetEventReference -->
                        <div class="mb-8 border-l-4 border-indigo-500 pl-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">4. GetEventReference</h3>
                            <p class="text-gray-700 mb-4">Get information about FiveM, ESX, and QBCore events.</p>

                            <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">event_name</code> (optional): Specific event name to
                                    look up</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">event_type</code> (optional): Filter by type (all, core,
                                    esx, qbcore)</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">language</code> (optional): Code example language (lua
                                    or js)</li>
                            </ul>

                            <h4 class="font-semibold text-gray-900 mb-2">Examples:</h4>
                            <pre class="bg-gray-100 rounded p-3 text-sm mb-2"><code>Show me all core FiveM events</code></pre>
                            <pre class="bg-gray-100 rounded p-3 text-sm mb-2"><code>Look up the QBCore:Client:OnPlayerLoaded event</code></pre>
                            <pre class="bg-gray-100 rounded p-3 text-sm"><code>List all ESX events</code></pre>
                        </div>

                        <!-- Tool 5: GenerateResourceBoilerplate -->
                        <div class="mb-8 border-l-4 border-indigo-500 pl-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">5. GenerateResourceBoilerplate</h3>
                            <p class="text-gray-700 mb-4">Generate complete resource boilerplate code.</p>

                            <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">resource_name</code> (required): Resource name</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">framework</code> (optional): standalone, esx, or qbcore
                                    (default: standalone)</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">script_type</code> (optional): lua or js (default: lua)
                                </li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">include_nui</code> (optional): Include NUI files
                                    (default: false)</li>
                            </ul>

                            <h4 class="font-semibold text-gray-900 mb-2">Example:</h4>
                            <pre
                                class="bg-gray-100 rounded p-3 text-sm"><code>Generate boilerplate for a QBCore resource with NUI called "admin-panel"</code></pre>
                        </div>

                        <!-- Tool 6: GetQBCoreEventReference -->
                        <div class="mb-8 border-l-4 border-indigo-500 pl-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">6. GetQBCoreEventReference</h3>
                            <p class="text-gray-700 mb-4">Get information about QBCore framework events.</p>

                            <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">event_name</code> (optional): Specific event name to
                                    look up</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">event_category</code> (optional): Filter by category
                                    (all, player, job, inventory, vehicle, money)</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">language</code> (optional): Code example language (lua
                                    or js)</li>
                            </ul>

                            <h4 class="font-semibold text-gray-900 mb-2">Examples:</h4>
                            <pre class="bg-gray-100 rounded p-3 text-sm mb-2"><code>What's the QBCore:Client:OnPlayerLoaded event?</code></pre>
                            <pre class="bg-gray-100 rounded p-3 text-sm mb-2"><code>Show me all QBCore player events</code></pre>
                            <pre class="bg-gray-100 rounded p-3 text-sm"><code>List all QBCore inventory events</code></pre>
                        </div>

                        <!-- Tool 7: GetQBCoreFunction -->
                        <div class="mb-8 border-l-4 border-indigo-500 pl-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">7. GetQBCoreFunction</h3>
                            <p class="text-gray-700 mb-4">Look up QBCore functions and exports.</p>

                            <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">function_name</code> (required): QBCore function name
                                    (e.g., "QBCore.Functions.GetPlayer", "AddMoney")</li>
                                <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">language</code> (optional): Code example language (lua
                                    or js)</li>
                            </ul>

                            <h4 class="font-semibold text-gray-900 mb-2">Common QBCore Functions:</h4>
                            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                                <li>Player: <code class="bg-gray-100 px-2 py-1 rounded text-sm">QBCore.Functions.GetPlayer</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">QBCore.Functions.GetPlayerData</code></li>
                                <li>Items: <code class="bg-gray-100 px-2 py-1 rounded text-sm">AddItem</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">RemoveItem</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">GetItemByName</code></li>
                                <li>Money: <code class="bg-gray-100 px-2 py-1 rounded text-sm">AddMoney</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">RemoveMoney</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">GetMoney</code></li>
                                <li>Jobs: <code class="bg-gray-100 px-2 py-1 rounded text-sm">SetJob</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">GetJob</code></li>
                                <li>Vehicles: <code class="bg-gray-100 px-2 py-1 rounded text-sm">AddVehicle</code>, <code
                                        class="bg-gray-100 px-2 py-1 rounded text-sm">DeleteVehicle</code></li>
                            </ul>

                            <h4 class="font-semibold text-gray-900 mb-2">Example:</h4>
                            <pre class="bg-gray-100 rounded p-3 text-sm"><code>How do I use the AddMoney function in QBCore?</code></pre>
                        </div>
    <!-- Tool 8: GetCOXEventReference -->
    <div class="mb-8 border-l-4 border-indigo-500 pl-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-3">8. GetCOXEventReference</h3>
        <p class="text-gray-700 mb-4">Get information about COX MySQL events and callbacks.</p>

        <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
        <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
            <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">event_name</code> (optional): Specific event name to
                look up</li>
            <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">event_type</code> (optional): Filter by type (all,
                query, connection, transaction, error)</li>
            <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">language</code> (optional): Code example language (lua
                or js)</li>
        </ul>

        <h4 class="font-semibold text-gray-900 mb-2">Examples:</h4>
        <pre class="bg-gray-100 rounded p-3 text-sm mb-2"><code>What are the COX MySQL connection events?</code></pre>
        <pre class="bg-gray-100 rounded p-3 text-sm mb-2"><code>Show me all COX transaction events</code></pre>
        <pre class="bg-gray-100 rounded p-3 text-sm"><code>How do I handle query errors in COX?</code></pre>
    </div>

    <!-- Tool 9: GetCOXFunction -->
    <div class="mb-8 border-l-4 border-indigo-500 pl-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-3">9. GetCOXFunction</h3>
        <p class="text-gray-700 mb-4">Look up COX MySQL functions and methods.</p>

        <h4 class="font-semibold text-gray-900 mb-2">Parameters:</h4>
        <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
            <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">function_name</code> (required): COX function name
                (e.g., "MySQL.query", "MySQL.insert")</li>
            <li><code class="bg-gray-100 px-2 py-1 rounded text-sm">language</code> (optional): Code example language (lua
                or js)</li>
        </ul>

        <h4 class="font-semibold text-gray-900 mb-2">Common COX Methods:</h4>
        <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
            <li>Query: <code class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.query</code>, <code
                    class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.single</code>, <code
                    class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.scalar</code></li>
            <li>Modify: <code class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.insert</code>, <code
                    class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.update</code></li>
            <li>Transactions: <code class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.transaction.begin</code>, <code
                    class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.transaction.commit</code></li>
            <li>Connection: <code class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.ready</code>, <code
                    class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.getStatus</code></li>
            <li>Security: <code class="bg-gray-100 px-2 py-1 rounded text-sm">MySQL.prepare</code> (prepared statements)
            </li>
        </ul>

        <h4 class="font-semibold text-gray-900 mb-2">Example:</h4>
        <pre
            class="bg-gray-100 rounded p-3 text-sm"><code>How do I query the database safely with prepared statements?</code></pre>
    </div>
                </section>

                <!-- Available Resources -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Resources</h2>
                    <p class="text-gray-700 mb-6">MCP Resources provide read-only reference documentation that AI assistants can access:</p>

                    <!-- Resource 1: Code Snippets -->
                    <div class="mb-8 border-l-4 border-green-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Code Snippets</h3>
                        <p class="text-gray-700 mb-4">Common FiveM code patterns and snippets for quick reference.</p>
                        <p class="text-gray-600 text-sm">URI: <code class="bg-gray-100 px-2 py-1 rounded text-sm">fivem://snippets/common</code></p>
                        <p class="text-gray-700 mt-2">Includes patterns for distance checks, event handlers, threads with cleanup, database queries, and NUI callbacks in both Lua and JavaScript.</p>
                    </div>

                    <!-- Resource 2: Best Practices -->
                    <div class="mb-8 border-l-4 border-green-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Best Practices</h3>
                        <p class="text-gray-700 mb-4">FiveM development best practices and optimization tips.</p>
                        <p class="text-gray-600 text-sm">URI: <code class="bg-gray-100 px-2 py-1 rounded text-sm">fivem://guides/best-practices</code></p>
                        <p class="text-gray-700 mt-2">Covers performance optimization, security best practices, code organization, resource management, testing, and common pitfalls.</p>
                    </div>

                    <!-- Resource 4: Database Queries -->
                    <div class="mb-8 border-l-4 border-green-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">4. Database Queries</h3>
                        <p class="text-gray-700 mb-4">FiveM database query examples and common patterns for COX MySQL.</p>
                        <p class="text-gray-600 text-sm">URI: <code
                            class="bg-gray-100 px-2 py-1 rounded text-sm">fivem://guides/database-queries</code></p>
                        <p class="text-gray-700 mt-2">Covers CRUD operations, prepared statements, transactions, async queries, error handling,
                        performance tips, and common database patterns (SELECT, INSERT, UPDATE, DELETE).</p>
                    </div>
                </section>

                <!-- Available Prompts -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Prompts</h2>
                    <p class="text-gray-700 mb-6">MCP Prompts are pre-built prompt templates for common tasks:</p>

                    <!-- Prompt 1: Create New Resource -->
                    <div class="mb-8 border-l-4 border-purple-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Create New Resource</h3>
                        <p class="text-gray-700 mb-4">Create a new FiveM resource with framework and language selection.</p>
                        <p class="text-gray-600 text-sm">Parameters: resource_name, framework (standalone/esx/qbcore), language (lua/js)</p>
                    </div>

                    <!-- Prompt 2: Debug Issues -->
                    <div class="mb-8 border-l-4 border-purple-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Debug Issues</h3>
                        <p class="text-gray-700 mb-4">Debug common FiveM issues and errors with detailed analysis.</p>
                        <p class="text-gray-600 text-sm">Parameters: issue (description), error_message (from console)</p>
                    </div>

                    <!-- Prompt 3: Optimize Performance -->
                    <div class="mb-8 border-l-4 border-purple-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Optimize Performance</h3>
                        <p class="text-gray-700 mb-4">Optimize FiveM script performance with detailed suggestions.</p>
                        <p class="text-gray-600 text-sm">Parameters: code (script to optimize), script_type (client/server/shared)</p>
                    </div>

                    <!-- Prompt 4: Convert Language -->
                    <div class="mb-8 border-l-4 border-purple-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">4. Convert Language</h3>
                        <p class="text-gray-700 mb-4">Convert code between Lua and JavaScript with proper syntax.</p>
                        <p class="text-gray-600 text-sm">Parameters: code, from (lua/js), to (lua/js)</p>
                    </div>

                    <!-- Prompt 5: Add Framework Integration -->
                    <div class="mb-8 border-l-4 border-purple-500 pl-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">5. Add Framework Integration</h3>
                        <p class="text-gray-700 mb-4">Add ESX or QBCore framework integration to existing resource.</p>
                        <p class="text-gray-600 text-sm">Parameters: code (current resource), current_framework, target_framework (esx/qbcore)</p>
                    </div>
                </section>

                <!-- Testing -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Testing the Server</h2>
                    <p class="text-gray-700 mb-4">You can test the MCP server locally using the inspector:</p>
                    <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>php artisan mcp:inspector fivem</code></pre>
                    <p class="text-gray-600 text-sm mt-2">This will open the MCP Inspector in your browser where you can test all the tools interactively.</p>
                </section>

                <!-- Development -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Development</h2>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Adding New Tools</h3>
                    <p class="text-gray-700 mb-4">1. Create a new tool class in <code class="bg-gray-100 px-2 py-1 rounded text-sm">app/Mcp/Tools/</code>:</p>

                    <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>&lt;?php

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
        }</code></pre>

                    <p class="text-gray-700 mb-4">2. Register the tool in <code class="bg-gray-100 px-2 py-1 rounded text-sm">app/Mcp/Servers/FiveMServer.php</code>:</p>

                    <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto mb-4"><code>protected array $tools = [
            // ... existing tools
            \App\Mcp\Tools\YourTool::class,
        ];</code></pre>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3 mt-6">Code Formatting</h3>
                    <p class="text-gray-700 mb-4">Format code with Laravel Pint:</p>
                    <pre class="bg-gray-900 text-gray-100 rounded p-4 overflow-x-auto"><code>vendor/bin/pint</code></pre>
                </section>

                <!-- Frameworks & Languages -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Supported Frameworks</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li><strong>Standalone:</strong> Basic FiveM resources without framework dependencies</li>
                        <li><strong>ESX:</strong> ESX Framework (es_extended)</li>
                        <li><strong>QBCore:</strong> QBCore Framework (qb-core)</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4 mt-6">Scripting Languages</h2>
                    <p class="text-gray-700">Both Lua and JavaScript are fully supported across all tools and generators.</p>
                </section>

                <!-- Resources -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Resources</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li><a href="https://docs.fivem.net/docs/" class="text-indigo-600 hover:text-indigo-500">FiveM Documentation</a></li>
                        <li><a href="https://docs.fivem.net/natives/" class="text-indigo-600 hover:text-indigo-500">FiveM Natives Reference</a></li>
                        <li><a href="https://coxdocs.dev/" class="text-indigo-600 hover:text-indigo-500">COX MySQL Documentation</a> - Required
                            for database operations</li>
                        <li><a href="https://docs.qbcore.org/" class="text-indigo-600 hover:text-indigo-500">QBCore Documentation</a></li>
                        <li><a href="https://laravel.com/docs/mcp" class="text-indigo-600 hover:text-indigo-500">Laravel MCP Documentation</a></li>
                        <li><a href="https://modelcontextprotocol.io/" class="text-indigo-600 hover:text-indigo-500">Model Context Protocol</a></li>
                    </ul>
                </section>

                <!-- Contributing -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Contributing</h2>
                    <p class="text-gray-700 mb-4">Contributions are welcome! Areas for expansion:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>More native functions in the database</li>
                        <li>Additional framework support (vRP, etc.)</li>
                        <li>More event types</li>
                        <li>Advanced code generators</li>
                        <li>Database query builders</li>
                    </ul>
                </section>

                <!-- License -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">License</h2>
                    <p class="text-gray-700">MIT License</p>
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
