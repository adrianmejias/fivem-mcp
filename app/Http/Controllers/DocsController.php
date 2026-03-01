<?php

namespace App\Http\Controllers;

use App\Mcp\Servers\FiveMServer;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Mcp\Server\Attributes\Description;
use ReflectionClass;

class DocsController extends Controller
{
    /**
     * Show the documentation index page.
     */
    public function index(): View
    {
        return view('docs.index');
    }

    /**
     * Show the quick start guide.
     */
    public function quickstart(): View
    {
        return view('docs.quickstart');
    }

    /**
     * Show the full documentation.
     */
    public function documentation(): View
    {
        $serverReflection = new ReflectionClass(FiveMServer::class);

        $toolsProperty = $serverReflection->getProperty('tools');
        $resourcesProperty = $serverReflection->getProperty('resources');
        $promptsProperty = $serverReflection->getProperty('prompts');

        $toolsProperty->setAccessible(true);
        $resourcesProperty->setAccessible(true);
        $promptsProperty->setAccessible(true);

        $defaultProperties = $serverReflection->getDefaultProperties();

        $tools = $this->extractComponentDetails($defaultProperties['tools'] ?? [], 'tool');
        $resources = $this->extractComponentDetails($defaultProperties['resources'] ?? [], 'resource');
        $prompts = $this->extractComponentDetails($defaultProperties['prompts'] ?? [], 'prompt');

        return view('docs.documentation', [
            'tools' => $tools,
            'resources' => $resources,
            'prompts' => $prompts,
        ]);
    }

    /**
     * Extract details from MCP components.
     */
    protected function extractComponentDetails(array $components, string $type): array
    {
        return collect($components)->map(function ($componentClass) use ($type) {
            $reflection = new ReflectionClass($componentClass);
            $descriptionAttr = $reflection->getAttributes(Description::class)[0] ?? null;
            $description = $descriptionAttr ? $descriptionAttr->getArguments()[0] : 'No description available';

            // Get the simple class name and convert it to a readable format
            $className = $reflection->getShortName();
            $name = $this->formatClassName($className);

            // Get category from namespace
            $namespace = $reflection->getNamespaceName();
            $parts = explode('\\', $namespace);
            $category = $parts[count($parts) - 1] ?? 'General';

            return [
                'name' => $name,
                'class' => $className,
                'description' => $description,
                'category' => $category,
                'type' => $type,
            ];
        })->toArray();
    }

    /**
     * Format class name to readable format.
     */
    protected function formatClassName(string $className): string
    {
        // Convert PascalCase to Title Case with spaces
        return Str::of($className)->kebab()->replace('-', ' ')->title();
    }
}
