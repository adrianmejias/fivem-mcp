<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FiveM MCP Server Documentation')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .code-block-wrapper {
            position: relative;
        }
        .copy-button {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            padding: 0.375rem 0.75rem;
            background-color: rgba(55, 65, 81, 0.8);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            opacity: 0;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .code-block-wrapper:hover .copy-button {
            opacity: 1;
        }
        .copy-button:hover {
            background-color: rgba(55, 65, 81, 1);
        }
        .copy-button.copied {
            background-color: rgba(16, 185, 129, 0.9);
            border-color: rgba(16, 185, 129, 1);
        }
        .copy-button svg {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-gray-900">FiveM MCP Server</h1>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('docs.index') }}" class="@if(request()->routeIs('docs.index')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Home
                            </a>
                            <a href="{{ route('docs.quickstart') }}" class="@if(request()->routeIs('docs.quickstart')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Quick Start
                            </a>
                            <a href="{{ route('docs.documentation') }}" class="@if(request()->routeIs('docs.documentation')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Documentation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    Built with <a href="https://laravel.com/docs/mcp" class="text-indigo-600 hover:text-indigo-500">Laravel MCP</a> for <a href="https://docs.fivem.net" class="text-indigo-600 hover:text-indigo-500">FiveM</a> development
                </p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add copy buttons to all code blocks
            const codeBlocks = document.querySelectorAll('pre');

            codeBlocks.forEach(function(pre) {
                // Wrap the pre in a div if not already wrapped
                if (!pre.parentElement.classList.contains('code-block-wrapper')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'code-block-wrapper';
                    pre.parentNode.insertBefore(wrapper, pre);
                    wrapper.appendChild(pre);
                }

                const wrapper = pre.parentElement;

                // Create copy button
                const copyButton = document.createElement('button');
                copyButton.className = 'copy-button';
                copyButton.innerHTML = `
                    <svg class="copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span class="copy-text">Copy</span>
                `;

                copyButton.addEventListener('click', function() {
                    const code = pre.querySelector('code');
                    const text = code ? code.textContent : pre.textContent;

                    navigator.clipboard.writeText(text).then(function() {
                        // Show success state
                        copyButton.classList.add('copied');
                        copyButton.innerHTML = `
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Copied!</span>
                        `;

                        // Reset after 2 seconds
                        setTimeout(function() {
                            copyButton.classList.remove('copied');
                            copyButton.innerHTML = `
                                <svg class="copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span class="copy-text">Copy</span>
                            `;
                        }, 2000);
                    }).catch(function(err) {
                        console.error('Failed to copy:', err);
                    });
                });

                wrapper.appendChild(copyButton);
            });
        });
    </script>
</body>
</html>
