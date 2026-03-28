<?php

namespace App\Mcp\Tools\FiveM\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up GTA5/FiveM server-side native functions by name. Returns function signature, parameters, return type, and usage examples.')]
class GetNativeServerFunction extends Tool
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
            return Response::text(sprintf("Server native function '%s' not found. Try searching at https://docs.fivem.net/natives/", $functionName));
        }

        $output = $this->formatNativeInfo($native, $language);

        return Response::text($output);
    }

    /**
     * Find a server native function.
     */
    protected function findNative(string $name): ?array
    {
        // Server-side only FiveM natives
        $natives = [
            'TriggerClientEvent' => [
                'namespace' => 'CFX',
                'description' => 'Triggers an event on a client from server',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                    ['name' => 'playerId', 'type' => 'int|string', 'description' => 'The player server ID or -1 for all'],
                    ['name' => '...args', 'type' => 'any', 'description' => 'Event arguments'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "TriggerClientEvent('myResource:clientEvent', playerId, arg1, arg2)",
                'js_example' => "TriggerClientEvent('myResource:clientEvent', playerId, arg1, arg2);",
            ],
            'TriggerClientEventReliable' => [
                'namespace' => 'CFX',
                'description' => 'Triggers an event on a client with guaranteed delivery',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                    ['name' => 'playerId', 'type' => 'int|string', 'description' => 'The player server ID'],
                    ['name' => '...args', 'type' => 'any', 'description' => 'Event arguments'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "TriggerClientEventReliable('myResource:clientEvent', playerId, arg1, arg2)",
                'js_example' => "TriggerClientEventReliable('myResource:clientEvent', playerId, arg1, arg2);",
            ],
            'GetPlayerName' => [
                'namespace' => 'PLAYER',
                'description' => 'Returns the name of a player (server-side)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'string', 'description' => 'The player name'],
                'lua_example' => 'local name = GetPlayerName(source)',
                'js_example' => 'const name = GetPlayerName(global.source);',
            ],
            'GetPlayerIdentifier' => [
                'namespace' => 'PLAYER',
                'description' => 'Gets a specific identifier for a player',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'int', 'description' => 'The player server ID'],
                    ['name' => 'type', 'type' => 'int', 'description' => 'Identifier type (0 = license, 1 = steam, etc.)'],
                ],
                'returns' => ['type' => 'string', 'description' => 'The player identifier'],
                'lua_example' => "local license = GetPlayerIdentifier(source, 0)\nlocal steam = GetPlayerIdentifier(source, 1)",
                'js_example' => "const license = GetPlayerIdentifier(global.source, 0);\nconst steam = GetPlayerIdentifier(global.source, 1);",
            ],
            'GetPlayerPing' => [
                'namespace' => 'PLAYER',
                'description' => 'Gets the ping of a player in milliseconds',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'int', 'description' => 'Player ping in ms'],
                'lua_example' => 'local ping = GetPlayerPing(source)',
                'js_example' => 'const ping = GetPlayerPing(global.source);',
            ],
            'DropPlayer' => [
                'namespace' => 'PLAYER',
                'description' => 'Disconnects a player from the server',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'playerId', 'type' => 'int', 'description' => 'The player server ID'],
                    ['name' => 'reason', 'type' => 'string', 'description' => 'Reason for dropping the player'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "DropPlayer(source, 'You were kicked by admin')",
                'js_example' => "DropPlayer(global.source, 'You were kicked by admin');",
            ],
            'GetPlayers' => [
                'namespace' => 'PLAYER',
                'description' => 'Gets a list of all player server IDs on the server',
                'side' => 'server',
                'parameters' => [],
                'returns' => ['type' => 'table', 'description' => 'Array of player IDs'],
                'lua_example' => "local players = GetPlayers()\nfor _, playerId in ipairs(players) do\n    print('Player: ' .. playerId)\nend",
                'js_example' => "const players = GetPlayers();\nfor (const playerId of players) {\n    console.log('Player: ' + playerId);\n}",
            ],
            'AddEventHandler' => [
                'namespace' => 'CFX',
                'description' => 'Adds a server-side event handler',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'The handler function'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "AddEventHandler('playerConnecting', function(name, setKickReason, deferrals)\n    -- Handle player connecting\nend)",
                'js_example' => "AddEventHandler('playerConnecting', (name, setKickReason, deferrals) => {\n    // Handle player connecting\n});",
            ],
            'TriggerEvent' => [
                'namespace' => 'CFX',
                'description' => 'Triggers a server-side event',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The name of the event'],
                    ['name' => '...args', 'type' => 'any', 'description' => 'Event arguments'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "TriggerEvent('myEvent', arg1, arg2)",
                'js_example' => "TriggerEvent('myEvent', arg1, arg2);",
            ],
            'ESX.GetPlayerFromId' => [
                'namespace' => 'ESX',
                'description' => 'Get ESX player object from server ID (server-side only)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'table', 'description' => 'ESX player object'],
                'lua_example' => 'local xPlayer = ESX.GetPlayerFromId(source)',
                'js_example' => 'const xPlayer = ESX.GetPlayerFromId(global.source);',
            ],
            'QBCore.Functions.GetPlayer' => [
                'namespace' => 'QBCore',
                'description' => 'Get QBCore player object from server ID (server-side only)',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'source', 'type' => 'int', 'description' => 'The player server ID'],
                ],
                'returns' => ['type' => 'table', 'description' => 'QBCore player object'],
                'lua_example' => 'local Player = QBCore.Functions.GetPlayer(source)',
                'js_example' => 'const Player = QBCore.Functions.GetPlayer(global.source);',
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
        return view('mcp.fivem.native-function', [
            'native' => $native,
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
                ->description('The name of the server-side native function to look up (e.g., "TriggerClientEvent", "GetPlayers", "DropPlayer")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
