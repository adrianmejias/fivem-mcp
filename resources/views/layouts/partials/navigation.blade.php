<nav class="bg-white dark:bg-gray-800 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">FiveM MCP Server</h1>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('docs.index') }}"
                        class="{{ request()->routeIs('docs.index') ? 'border-gta-orange text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Home
                    </a>
                    <a href="{{ route('docs.quickstart') }}"
                        class="{{ request()->routeIs('docs.quickstart') ? 'border-gta-orange text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Quick Start
                    </a>
                    <a href="{{ route('docs.documentation') }}"
                        class="{{ request()->routeIs('docs.documentation') ? 'border-gta-orange text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
