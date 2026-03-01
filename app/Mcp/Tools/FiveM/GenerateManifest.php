<?php

namespace App\Mcp\Tools\FiveM;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Generate a fxmanifest.lua file for a FiveM resource with the specified configuration options.')]
class GenerateManifest extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $resourceName = $request->get('resource_name');
        $author = $request->get('author', 'Unknown');
        $description = $request->get('description', 'A FiveM resource');
        $version = $request->get('version', '1.0.0');
        $scriptType = $request->get('script_type', 'lua');
        $includeClient = $request->get('include_client', true);
        $includeServer = $request->get('include_server', true);
        $framework = $request->get('framework', 'standalone');

        return Response::text(
            view('mcp.templates.fxmanifest', [
                'resourceName' => $resourceName,
                'author' => $author,
                'description' => $description,
                'version' => $version,
                'scriptExt' => $scriptType === 'js' ? 'js' : 'lua',
                'includeClient' => $includeClient,
                'includeServer' => $includeServer,
                'framework' => $framework,
            ])->render()
        );
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'resource_name' => $schema
                ->string()
                ->description('The name of the FiveM resource')
                ->required(),
            'author' => $schema
                ->string()
                ->description('The author name')
                ->default('Unknown'),
            'description' => $schema
                ->string()
                ->description('Resource description')
                ->default('A FiveM resource'),
            'version' => $schema
                ->string()
                ->description('Resource version (e.g., "1.0.0")')
                ->default('1.0.0'),
            'script_type' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('Scripting language (Lua or JavaScript)')
                ->default('lua'),
            'include_client' => $schema
                ->boolean()
                ->description('Include client-side scripts')
                ->default(true),
            'include_server' => $schema
                ->boolean()
                ->description('Include server-side scripts')
                ->default(true),
            'framework' => $schema
                ->string()
                ->enum(['standalone', 'esx', 'qbcore'])
                ->description('Framework type (standalone, ESX, or QBCore)')
                ->default('standalone'),
        ];
    }
}
