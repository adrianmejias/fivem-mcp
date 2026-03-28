<?php

namespace App\Mcp\Tools\COX\Doorlock\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_doorlock server functions by name. Returns function signature, parameters, return type, and usage examples for server-side door management.')]
class GetOxDoorlockServerFunction extends Tool
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
            return Response::text(sprintf("ox_doorlock server function '%s' not found. Check ox_doorlock documentation at https://coxdocs.dev/ox_doorlock", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_doorlock server function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'getDoor' => [
                'namespace' => 'Server',
                'description' => 'Get door data by door ID',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'doorId', 'type' => 'number', 'description' => 'The door ID'],
                ],
                'returns' => ['type' => 'table|nil', 'description' => 'Door data or nil if not found'],
                'lua_example' => "local door = exports.ox_doorlock:getDoor(1)\nif door then\n    print('Door name: '..door.name)\n    print('Door state: '..door.state)\nend",
                'js_example' => "const door = exports.ox_doorlock.getDoor(1);\nif (door) {\n    console.log('Door name: ' + door.name);\n    console.log('Door state: ' + door.state);\n}",
            ],
            'getDoorFromName' => [
                'namespace' => 'Server',
                'description' => 'Get door data by door name',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'doorName', 'type' => 'string', 'description' => 'The door name'],
                ],
                'returns' => ['type' => 'table|nil', 'description' => 'Door data or nil if not found'],
                'lua_example' => "local door = exports.ox_doorlock:getDoorFromName('mrpd_main')\nif door then\n    print('Door ID: '..door.id)\n    print('Door is locked: '..tostring(door.state == 1))\nend",
                'js_example' => "const door = exports.ox_doorlock.getDoorFromName('mrpd_main');\nif (door) {\n    console.log('Door ID: ' + door.id);\n    console.log('Door is locked: ' + (door.state === 1));\n}",
            ],
            'editDoor' => [
                'namespace' => 'Server',
                'description' => 'Edit door properties by door ID',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'doorId', 'type' => 'number', 'description' => 'The door ID'],
                    ['name' => 'data', 'type' => 'table', 'description' => 'Table with properties to update (state, groups, etc.)'],
                    ['name' => 'playerId', 'type' => 'number|nil', 'description' => 'Optional player ID who triggered the edit'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'True if door was successfully edited'],
                'lua_example' => "local success = exports.ox_doorlock:editDoor(1, {\n    state = 0, -- Unlocked\n    groups = {police = 0} -- Police can use\n})\nif success then\n    print('Door edited successfully')\nend",
                'js_example' => "const success = exports.ox_doorlock.editDoor(1, {\n    state: 0, // Unlocked\n    groups: {police: 0} // Police can use\n});\nif (success) {\n    console.log('Door edited successfully');\n}",
            ],
            'setDoorState' => [
                'namespace' => 'Server',
                'description' => 'Set the lock state of a door',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'doorId', 'type' => 'number', 'description' => 'The door ID'],
                    ['name' => 'state', 'type' => 'number', 'description' => '0 = unlocked, 1 = locked'],
                    ['name' => 'playerId', 'type' => 'number|nil', 'description' => 'Optional player ID who triggered the state change'],
                ],
                'returns' => ['type' => 'boolean', 'description' => 'True if state was successfully changed'],
                'lua_example' => "local success = exports.ox_doorlock:setDoorState(1, 1) -- Lock door ID 1\nif success then\n    print('Door locked successfully')\nend",
                'js_example' => "const success = exports.ox_doorlock.setDoorState(1, 1); // Lock door ID 1\nif (success) {\n    console.log('Door locked successfully');\n}",
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
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'function_name' => $schema
                ->string()
                ->description('The name of the ox_doorlock server function to look up (e.g., "getDoor", "getDoorFromName", "editDoor", "setDoorState")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
