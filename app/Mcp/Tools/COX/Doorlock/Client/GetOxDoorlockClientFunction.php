<?php

namespace App\Mcp\Tools\COX\Doorlock\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_doorlock client functions by name. Returns function signature, parameters, return type, and usage examples for client-side door management.')]
class GetOxDoorlockClientFunction extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $functionName = $request->get('function_name');
        $language = $request->get('language', 'lua');

        $function = $this->findFunction($functionName);

        if (! $function) {
            return Response::text(sprintf("ox_doorlock client function '%s' not found. Check ox_doorlock documentation at https://coxdocs.dev/ox_doorlock", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_doorlock client function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'pickClosestDoor' => [
                'namespace' => 'Client',
                'description' => 'Attempt to lockpick the closest door',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => 'exports.ox_doorlock:pickClosestDoor()',
                'js_example' => 'exports.ox_doorlock.pickClosestDoor();',
            ],
            'useClosestDoor' => [
                'namespace' => 'Client',
                'description' => 'Toggle the lock state of the closest door',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => 'exports.ox_doorlock:useClosestDoor()',
                'js_example' => 'exports.ox_doorlock.useClosestDoor();',
            ],
            'getClosestDoor' => [
                'namespace' => 'Client',
                'description' => 'Get data for the closest door',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'table|nil', 'description' => 'Door data or nil if no door nearby'],
                'lua_example' => "local door = exports.ox_doorlock:getClosestDoor()\nif door then\n    print('Closest door ID: '..door.id)\n    print('Door is locked: '..tostring(door.state == 1))\nend",
                'js_example' => "const door = exports.ox_doorlock.getClosestDoor();\nif (door) {\n    console.log('Closest door ID: ' + door.id);\n    console.log('Door is locked: ' + (door.state === 1));\n}",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($functions as $functionName => $functionData) {
            if (strtolower($functionName) === $nameLower) {
                return array_merge(['name' => $functionName], $functionData);
            }
        }

        return null;
    }

    /**
     * Format function info.
     */
    protected function formatFunctionInfo(array $function, string $language): string
    {
        return view('mcp.cox.cox-function', [
            'function' => $function,
            'language' => $language,
        ])->render();
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'function_name' => $schema
                ->string()
                ->description('The name of the ox_doorlock client function to look up (e.g., "pickClosestDoor", "useClosestDoor", "getClosestDoor")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
