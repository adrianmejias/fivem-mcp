<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up GTA5/FiveM native functions by name. Returns function signature, parameters, return type, and usage examples.')]
class GetNativeFunction extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $functionName = $request->get('function_name');
        $language = $request->get('language', 'lua');

        $native = $this->findNative($functionName);

        if (! $native) {
            return Response::text(sprintf("Native function '%s' not found. Try searching at https://docs.fivem.net/natives/", $functionName));
        }

        $output = $this->formatNativeInfo($native, $language);

        return Response::text($output);
    }

    /**
     * Find a native function.
     */
    protected function findNative(string $name): ?array
    {
        // Common FiveM/GTA5 natives database
        $natives = [
            // Player/Ped natives
            'GetPlayerPed' => [
                'namespace' => 'PLAYER',
                'description' => 'Gets the local or specified player\'s ped handle',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'int', 'description' => 'The player ID, or -1 for local player'],
                ],
                'returns' => ['type' => 'int', 'description' => 'The player ped handle'],
                'lua_example' => 'local playerPed = GetPlayerPed(-1)',
                'js_example' => 'const playerPed = GetPlayerPed(-1);',
            ],
            'GetPlayerName' => [
                'namespace' => 'PLAYER',
                'description' => 'Returns the name of a player',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'int', 'description' => 'The player ID'],
                ],
                'returns' => ['type' => 'string', 'description' => 'The player name'],
                'lua_example' => 'local name = GetPlayerName(PlayerId())',
                'js_example' => 'const name = GetPlayerName(PlayerId());',
            ],
            'SetEntityCoords' => [
                'namespace' => 'ENTITY',
                'description' => 'Sets the coordinates of an entity',
                'parameters' => [
                    ['name' => 'entity', 'type' => 'int', 'description' => 'The entity handle'],
                    ['name' => 'x', 'type' => 'float', 'description' => 'X coordinate'],
                    ['name' => 'y', 'type' => 'float', 'description' => 'Y coordinate'],
                    ['name' => 'z', 'type' => 'float', 'description' => 'Z coordinate'],
                    ['name' => 'alive', 'type' => 'boolean', 'description' => 'Keep entity alive during teleport'],
                    ['name' => 'deadFlag', 'type' => 'boolean', 'description' => 'Teleport even if dead'],
                    ['name' => 'ragdollFlag', 'type' => 'boolean', 'description' => 'Teleport even in ragdoll'],
                    ['name' => 'clearArea', 'type' => 'boolean', 'description' => 'Clear area at destination'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => 'SetEntityCoords(PlayerPedId(), 0.0, 0.0, 72.0, true, false, false, true)',
                'js_example' => 'SetEntityCoords(PlayerPedId(), 0.0, 0.0, 72.0, true, false, false, true);',
            ],
            'GetEntityCoords' => [
                'namespace' => 'ENTITY',
                'description' => 'Gets the coordinates of an entity',
                'parameters' => [
                    ['name' => 'entity', 'type' => 'int', 'description' => 'The entity handle'],
                    ['name' => 'alive', 'type' => 'boolean', 'description' => 'Whether to get alive coordinates only'],
                ],
                'returns' => ['type' => 'vector3', 'description' => 'The entity coordinates'],
                'lua_example' => 'local coords = GetEntityCoords(PlayerPedId(), true)',
                'js_example' => 'const coords = GetEntityCoords(PlayerPedId(), true);',
            ],
            'RegisterNetEvent' => [
                'namespace' => 'CFX',
                'description' => 'Registers a network event handler (FiveM-specific)',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "RegisterNetEvent('myResource:eventName', function(param1, param2)\n    -- Handle event\nend)",
                'js_example' => "RegisterNetEvent('myResource:eventName', (param1, param2) => {\n    // Handle event\n});",
            ],
            'TriggerServerEvent' => [
                'namespace' => 'CFX',
                'description' => 'Triggers an event on the server from client',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                    ['name' => '...args', 'type' => 'any', 'description' => 'Event arguments'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "TriggerServerEvent('myResource:serverEvent', arg1, arg2)",
                'js_example' => "TriggerServerEvent('myResource:serverEvent', arg1, arg2);",
            ],
            'TriggerClientEvent' => [
                'namespace' => 'CFX',
                'description' => 'Triggers an event on a client from server',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                    ['name' => 'playerId', 'type' => 'int|string', 'description' => 'The player server ID or -1 for all'],
                    ['name' => '...args', 'type' => 'any', 'description' => 'Event arguments'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "TriggerClientEvent('myResource:clientEvent', playerId, arg1, arg2)",
                'js_example' => "TriggerClientEvent('myResource:clientEvent', playerId, arg1, arg2);",
            ],
            'AddEventHandler' => [
                'namespace' => 'CFX',
                'description' => 'Adds an event handler for local events',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'The handler function'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "AddEventHandler('onResourceStart', function(resourceName)\n    if resourceName == GetCurrentResourceName() then\n        print('Resource started!')\n    end\nend)",
                'js_example' => "AddEventHandler('onResourceStart', (resourceName) => {\n    if (resourceName === GetCurrentResourceName()) {\n        console.log('Resource started!');\n    }\n});",
            ],
            'ESX.GetPlayerFromId' => [
                'namespace' => 'ESX',
                'description' => 'Get ESX player object from server ID (server-side)',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'table', 'description' => 'ESX player object'],
                'lua_example' => 'local xPlayer = ESX.GetPlayerFromId(source)',
                'js_example' => 'const xPlayer = ESX.GetPlayerFromId(source);',
            ],
            'QBCore.Functions.GetPlayer' => [
                'namespace' => 'QBCore',
                'description' => 'Get QBCore player object from server ID (server-side)',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'table', 'description' => 'QBCore player object'],
                'lua_example' => 'local Player = QBCore.Functions.GetPlayer(source)',
                'js_example' => 'const Player = QBCore.Functions.GetPlayer(source);',
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($natives as $nativeName => $nativeData) {
            if (strtolower($nativeName) === $nameLower) {
                return array_merge(['name' => $nativeName], $nativeData);
            }
        }

        return null;
    }

    /**
     * Format native function info.
     */
    protected function formatNativeInfo(array $native, string $language): string
    {
        return view('mcp.native-function', [
            'native' => $native,
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
                ->description('The name of the native function to look up (e.g., "GetPlayerPed", "SetEntityCoords")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
