@extends('layouts.app')

@section('title', 'FiveM MCP Server')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                FiveM Development MCP Server
            </h1>
            <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500 dark:text-gray-400">
                A comprehensive Model Context Protocol server for FiveM development with 5 tools, 3 resources, and 5
                prompts.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="mt-12">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">Features</h2>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Documentation Search -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 bg-gta-orange rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Documentation Search</h3>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Search FiveM documentation by topic and category. Get direct links to relevant
                                documentation.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Native Functions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 bg-gta-orange rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Native Functions</h3>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Look up GTA5/FiveM native functions with signatures, parameters, and code examples.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Manifest Generator -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 bg-gta-orange rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Manifest Generator</h3>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Generate fxmanifest.lua files automatically for any framework and scripting language.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Event Reference -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 bg-gta-orange rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Event Reference</h3>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Complete database of FiveM events including core, ESX, and QBCore events with examples.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Boilerplate Generator -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 bg-gta-orange rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Boilerplate Generator</h3>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Generate complete resource structures with client, server, config, and optional NUI files.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Framework Support -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 bg-gta-orange rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Framework Support</h3>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Full support for Standalone, ESX, and QBCore frameworks in both Lua and JavaScript.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-16 bg-gray-900 dark:bg-black border border-gta-orange rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 py-12 sm:px-12">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-white">Ready to get started?</h2>
                    <p class="mt-4 text-lg text-gray-200 dark:text-gray-300">
                        Set up the MCP server in just 5 minutes with our quick start guide.
                    </p>
                    <div class="mt-8 flex justify-center space-x-4">
                        <a href="{{ route('docs.quickstart') }}"
                            class="btn-gta-secondary inline-flex items-center px-6 py-3 text-base">
                            Quick Start Guide
                        </a>
                        <a href="{{ route('docs.documentation') }}"
                            class="btn-gta-primary inline-flex items-center px-6 py-3 text-base">
                            Full Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources -->
        <div class="mt-16">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">Resources</h2>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">MCP Server Configuration</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        To connect to the FiveM MCP server, use the following configuration in your MCP client:
                    </p>
                    <pre
                        class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded p-4 text-sm overflow-x-auto"><code>{
    "servers": {
        "fivem": {
            "url": "{{ route('fivem') }}",
            "type": "http"
        }
    }
}</code></pre>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">External Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="https://docs.fivem.net/docs/"
                                    class="link-gta">FiveM
                                    Documentation</a>
                            </li>
                            <li>
                                <a href="https://docs.fivem.net/natives/"
                                    class="link-gta">FiveM
                                    Natives Reference</a>
                            </li>
                            <li>
                                <a href="https://docs.qbcore.org/"
                                    class="link-gta">QBCore
                                    Documentation</a>
                            </li>
                            <li>
                                <a href="https://coxdocs.dev/"
                                    class="link-gta">COX
                                    MySQL Documentation</a>
                            </li>
                            <li>
                                <a href="https://laravel.com/docs/mcp"
                                    class="link-gta">Laravel
                                    MCP Documentation</a>
                            </li>
                            <li>
                                <a href="https://modelcontextprotocol.io/"
                                    class="link-gta">Model
                                    Context Protocol</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
